<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ShiprocketService
{
    private const TOKEN_CACHE_KEY = 'shiprocket_api_token';

    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('shiprocket.base_url'), '/');
    }

    public function pushOrder(Order $order, array $options = []): array
    {
        try {
            if ($order->shiprocket_shipment_id) {
                return [
                    'ok' => true,
                    'message' => 'Order already synced with Shiprocket',
                ];
            }

            $order->loadMissing('orderDetails.product');

            $payload = $this->buildOrderPayload($order, $options);
            $response = $this->client()->post('/orders/create/adhoc', $payload);

            if (!$response->successful()) {
                $this->logFailure('Order push failed', $response, ['order_id' => $order->id]);
                return [
                    'ok' => false,
                    'message' => $response->json('message') ?? 'Shiprocket order creation failed',
                    'response' => $response->json(),
                ];
            }

            $data = $response->json();

            $order->shiprocket_order_id = $data['order_id'] ?? ($data['data']['order_id'] ?? null);
            $order->shiprocket_shipment_id = $data['shipment_id'] ?? ($data['data']['shipment_id'] ?? null);
            $order->shiprocket_awb = $data['awb_code'] ?? ($data['data']['awb_code'] ?? null);
            $order->shiprocket_status = $data['current_status'] ?? ($data['data']['status'] ?? 'created');
            $order->shiprocket_label_url = $data['label_url'] ?? ($data['data']['label_url'] ?? null);
            $order->shiprocket_manifest_url = $data['manifest_url'] ?? ($data['data']['manifest_url'] ?? null);
            $order->shiprocket_courier_name = $data['assigned_courier_name'] ?? ($data['data']['courier_name'] ?? null);
            $order->shiprocket_last_synced_at = now();
            if (empty($order->tracking_code) && $order->shiprocket_awb) {
                $order->tracking_code = $order->shiprocket_awb;
            }
            $order->save();

            return [
                'ok' => true,
                'message' => 'Order pushed to Shiprocket',
                'response' => $data,
            ];
        } catch (Throwable $e) {
            $this->logException('Order push exception', $e, ['order_id' => $order->id]);
            return [
                'ok' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function schedulePickup(Order $order, ?Carbon $pickupDate = null): array
    {
        if (!$order->shiprocket_shipment_id) {
            return ['ok' => false, 'message' => 'Shipment not created yet'];
        }

        try {
            $payload = [
                'shipment_id' => [$order->shiprocket_shipment_id],
                'pickup_date' => ($pickupDate ?? now())->toDateString(),
            ];

            $response = $this->client()->post('/courier/generate/pickup', $payload);
            if (!$response->successful()) {
                $this->logFailure('Pickup scheduling failed', $response, ['order_id' => $order->id]);
                return [
                    'ok' => false,
                    'message' => $response->json('message') ?? 'Shiprocket pickup scheduling failed',
                    'response' => $response->json(),
                ];
            }

            $order->shiprocket_pickup_scheduled_at = now();
            $order->shiprocket_status = $response->json('status') ?? $order->shiprocket_status;
            $order->save();

            return [
                'ok' => true,
                'message' => 'Pickup scheduled',
                'response' => $response->json(),
            ];
        } catch (Throwable $e) {
            $this->logException('Pickup scheduling exception', $e, ['order_id' => $order->id]);
            return [
                'ok' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function track(Order $order): array
    {
        if (!$order->shiprocket_awb) {
            return ['ok' => false, 'message' => 'AWB not available'];
        }

        try {
            $response = $this->client()->get('/courier/track/awb/' . $order->shiprocket_awb);

            if (!$response->successful()) {
                $this->logFailure('Tracking fetch failed', $response, ['order_id' => $order->id]);
                return [
                    'ok' => false,
                    'message' => $response->json('message') ?? 'Shiprocket tracking fetch failed',
                    'response' => $response->json(),
                ];
            }

            $data = $response->json();
            $order->shiprocket_status = $data['tracking_data']['shipment_status'] ?? ($data['tracking_status'] ?? $order->shiprocket_status);
            if (isset($data['tracking_data']['track_url']) && empty($order->shiprocket_label_url)) {
                $order->shiprocket_label_url = $data['tracking_data']['track_url'];
            }
            $order->shiprocket_last_synced_at = now();
            $order->save();

            return ['ok' => true, 'message' => 'Tracking updated', 'response' => $data];
        } catch (Throwable $e) {
            $this->logException('Tracking fetch exception', $e, ['order_id' => $order->id]);
            return [
                'ok' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function handleWebhook(array $payload, ?string $token = null): array
    {
        $expectedToken = config('shiprocket.webhook_token');
        if ($expectedToken && $token !== $expectedToken) {
            return ['ok' => false, 'message' => 'Invalid webhook token'];
        }

        $awb = $payload['awb'] ?? $payload['awb_code'] ?? null;
        if (!$awb) {
            return ['ok' => false, 'message' => 'AWB missing in webhook'];
        }

        $order = Order::where('shiprocket_awb', $awb)->first();
        if (!$order) {
            return ['ok' => false, 'message' => 'Order not found for AWB'];
        }

        $order->shiprocket_status = $payload['status'] ?? ($payload['current_status'] ?? $order->shiprocket_status);
        if (!empty($payload['pickup_scheduled_date'])) {
            $order->shiprocket_pickup_scheduled_at = Carbon::parse($payload['pickup_scheduled_date']);
        }
        $order->shiprocket_last_synced_at = now();
        $order->save();

        return ['ok' => true, 'message' => 'Webhook processed'];
    }

    private function buildOrderPayload(Order $order, array $options = []): array
    {
        $shippingAddress = json_decode($order->shipping_address ?? '{}', true) ?: [];
        $paymentMethod = ($order->payment_type === 'cash_on_delivery' || $order->payment_status !== 'paid') ? 'COD' : 'Prepaid';
        $weight = $this->calculateWeight($order);

        $items = $order->orderDetails->map(function ($detail) {
            $product = $detail->product;

            return [
                'name' => $product?->getTranslation('name') ?? $product?->name ?? 'Item',
                'sku' => $product?->sku ?? ('ITEM-' . $detail->id),
                'units' => $detail->quantity,
                'selling_price' => round($detail->price / max(1, $detail->quantity), 2),
                'discount' => 0,
                'tax' => round($detail->tax / max(1, $detail->quantity), 2),
                'hsn' => $product?->hsn,
            ];
        })->values()->toArray();

        return [
            'order_id' => $order->code,
            'order_date' => Carbon::createFromTimestamp($order->date)->format('Y-m-d H:i'),
            'pickup_location' => config('shiprocket.pickup_location'),
            'channel_id' => '',
            'comment' => $order->additional_info,
            'billing_customer_name' => $shippingAddress['name'] ?? $order->user->name,
            'billing_last_name' => '',
            'billing_address' => $shippingAddress['address'] ?? '',
            'billing_address_2' => $shippingAddress['address_2'] ?? '',
            'billing_city' => $shippingAddress['city'] ?? '',
            'billing_pincode' => $shippingAddress['postal_code'] ?? '',
            'billing_state' => $shippingAddress['state'] ?? '',
            'billing_country' => $shippingAddress['country'] ?? 'India',
            'billing_email' => $shippingAddress['email'] ?? $order->user->email,
            'billing_phone' => $shippingAddress['phone'] ?? $order->user->phone,
            'shipping_is_billing' => true,
            'order_items' => $items,
            'payment_method' => $paymentMethod,
            'sub_total' => round($order->grand_total, 2),
            'length' => $options['length'] ?? config('shiprocket.default_length'),
            'breadth' => $options['breadth'] ?? config('shiprocket.default_breadth'),
            'height' => $options['height'] ?? config('shiprocket.default_height'),
            'weight' => $options['weight'] ?? $weight,
        ];
    }

    private function calculateWeight(Order $order): float
    {
        $total = 0;
        foreach ($order->orderDetails as $detail) {
            $productWeight = optional($detail->product)->weight ?? config('shiprocket.default_weight');
            $total += ($productWeight ?: config('shiprocket.default_weight')) * $detail->quantity;
        }

        return round(max($total, config('shiprocket.default_weight')), 3);
    }

    private function client()
    {
        return Http::baseUrl($this->baseUrl)
            ->timeout(config('shiprocket.timeout'))
            ->withToken($this->token())
            ->acceptJson();
    }

    private function token(): string
    {
        return Cache::remember(self::TOKEN_CACHE_KEY, now()->addMinutes(50), function () {
            $payload = [
                'email' => config('shiprocket.email'),
                'password' => config('shiprocket.password'),
            ];

            if (config('shiprocket.api_key') && config('shiprocket.api_secret')) {
                $payload['api_key'] = config('shiprocket.api_key');
                $payload['api_secret'] = config('shiprocket.api_secret');
            }

            $response = Http::baseUrl($this->baseUrl)
                ->timeout(config('shiprocket.timeout'))
                ->post('/auth/login', $payload);

            if (!$response->successful()) {
                $this->logFailure('Shiprocket auth failed', $response);
                throw new \RuntimeException('Shiprocket authentication failed');
            }

            $token = $response->json('token');
            if (!$token) {
                throw new \RuntimeException('Shiprocket token missing from response');
            }

            $expires = $response->json('expires_in') ?? 50;

            Cache::put(self::TOKEN_CACHE_KEY, $token, now()->addMinutes((int) $expires));

            return $token;
        });
    }

    public function refreshToken(): array
    {
        try {
            Cache::forget(self::TOKEN_CACHE_KEY);
            $token = $this->token();

            return [
                'ok' => true,
                'message' => 'Shiprocket token refreshed',
                'token' => $token,
            ];
        } catch (Throwable $e) {
            $this->logException('Shiprocket token refresh failed', $e);
            return [
                'ok' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    private function logFailure(string $message, $response, array $context = []): void
    {
        Log::channel(config('shiprocket.log_channel'))
            ->error($message, array_merge($context, [
                'status' => $response->status(),
                'body' => $response->json(),
            ]));
    }

    private function logException(string $message, Throwable $e, array $context = []): void
    {
        Log::channel(config('shiprocket.log_channel'))
            ->error($message, array_merge($context, [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]));
    }
}
