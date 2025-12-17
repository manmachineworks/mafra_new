<div class="z-3 sticky-top-lg">
    <div class="card border os-card">

        @php
            $subtotal_for_min_order_amount = 0;
            $subtotal = 0;
            $tax = 0;
            $product_shipping_cost = 0;
            $shipping = 0;
            $coupon_code = null;
            $coupon_discount = 0;
            $total_point = 0;
        @endphp

        @foreach ($carts as $key => $cartItem)
            @php
                $product = get_single_product($cartItem['product_id']);
                $subtotal_for_min_order_amount += cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity'];
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $product_shipping_cost = $cartItem['shipping_cost'];
                $shipping += $product_shipping_cost;

                if ((get_setting('coupon_system') == 1) && ($cartItem->coupon_applied == 1)) {
                    $coupon_code = $cartItem->coupon_code;
                    $coupon_discount = $carts->sum('discount');
                }

                if (addon_is_activated('club_point')) {
                    $total_point += $product->earn_point * $cartItem['quantity'];
                }
            @endphp
        @endforeach

        @php
            $total = $subtotal + $tax + $shipping;
            if (Session::has('club_point')) { $total -= Session::get('club_point'); }
            if ($coupon_discount > 0) { $total -= $coupon_discount; }
        @endphp

        {{-- Header --}}
        <div class="card-header os-header">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="os-title">{{ translate('Order Summary') }}</div>
                    <div class="os-subtitle">
                        {{ sprintf("%02d", count($carts)) }} {{ translate('items') }}
                    </div>
                </div>

                <!-- {{-- NEW OFFER badge --}}
                <span class="os-offer-badge">
                    {{ translate('NEW OFFER') }}
                </span> -->
            </div>

            {{-- Minimum Order Amount --}}
            @if (get_setting('minimum_order_amount_check') == 1 && $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
                <div class="mt-3 os-alert">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div class="fw-700">
                            {{ translate('Minimum Order Amount') }}
                        </div>
                        <div class="fw-900">
                            {{ single_price(get_setting('minimum_order_amount')) }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="card-body os-body">

            {{-- Summary chips --}}
            <div class="row gutters-10">
                <div class="@if (addon_is_activated('club_point')) col-6 @else col-12 @endif">
                    <div class="os-chip">
                        <span class="os-chip-label">{{ translate('Total Products') }}</span>
                        <span class="os-chip-value">{{ sprintf("%02d", count($carts)) }}</span>
                    </div>
                </div>

                @if (addon_is_activated('club_point'))
                    <div class="col-6">
                        <div class="os-chip os-chip-dark">
                            <span class="os-chip-label">{{ translate('Total Clubpoint') }}</span>
                            <span class="os-chip-value">{{ sprintf("%02d", $total_point) }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <input type="hidden" id="sub_total" value="{{ $subtotal }}">

            {{-- Price breakdown --}}
            <div class="os-breakdown mt-3">

                <div class="os-row">
                    <div class="os-row-left">
                        <div class="os-row-title">{{ translate('Subtotal') }}</div>
                        <div class="os-row-meta">
                            ({{ sprintf("%02d", count($carts)) }} {{ translate('Products') }})
                        </div>
                    </div>
                    <div class="os-row-right">{{ single_price($subtotal) }}</div>
                </div>

                <div class="os-row">
                    <div class="os-row-left">
                        <div class="os-row-title">{{ translate('Tax') }}</div>
                    </div>
                    <div class="os-row-right">{{ single_price($tax) }}</div>
                </div>

                @if ($proceed != 1)
                    <div class="os-row">
                        <div class="os-row-left">
                            <div class="os-row-title">{{ translate('Total Shipping') }}</div>
                        </div>
                        <div class="os-row-right">{{ single_price($shipping) }}</div>
                    </div>
                @endif

                @if (Session::has('club_point'))
                    <div class="os-row os-row-discount">
                        <div class="os-row-left">
                            <div class="os-row-title">{{ translate('Redeem point') }}</div>
                        </div>
                        <div class="os-row-right">- {{ single_price(Session::get('club_point')) }}</div>
                    </div>
                @endif

                @if ($coupon_discount > 0)
                    <div class="os-row os-row-discount">
                        <div class="os-row-left">
                            <div class="os-row-title">{{ translate('Coupon Discount') }}</div>
                        </div>
                        <div class="os-row-right">- {{ single_price($coupon_discount) }}</div>
                    </div>
                @endif

                <div class="os-divider"></div>

                <div class="os-total">
                    <div class="os-total-left">
                        <div class="os-total-title">{{ translate('Total') }}</div>
                        <div class="os-total-meta">{{ translate('Inclusive of taxes') }}</div>
                    </div>
                    <div class="os-total-right">{{ single_price($total) }}</div>
                </div>
            </div>

            {{-- Coupon --}}
            @if (get_setting('coupon_system') == 1)
                @if ($coupon_discount > 0 && $coupon_code)
                    <div class="mt-3">
                        <form id="remove-coupon-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="proceed" value="{{ $proceed }}">

                            <div class="os-coupon-applied">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-white fw-800" style="font-size:13px;">{{ translate('Coupon Applied') }}</div>
                                        <div class="text-white" style="font-size:12px;opacity:.9;">{{ $coupon_code }}</div>
                                    </div>
                                    <button type="button" id="coupon-remove" class="btn os-btn-light">
                                        {{ translate('Change') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="mt-3">
                        <form id="apply-coupon-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="proceed" value="{{ $proceed }}">

                            <div class="os-coupon-wrap">
                                <input type="text" class="form-control os-input"
                                       name="code"
                                       onkeydown="return event.key != 'Enter';"
                                       placeholder="{{ translate('Have a coupon? Enter code') }}" required>

                                <button type="button" id="coupon-apply" class="btn os-btn-primary">
                                    {{ translate('Apply') }}
                                </button>
                            </div>

                            @if (!auth()->check())
                                <small class="d-block mt-2 text-secondary" style="font-size:12px;">
                                    {{ translate('You must Login as customer to apply coupon') }}
                                </small>
                            @endif
                        </form>
                    </div>
                @endif
            @endif

            {{-- Checkout --}}
            @if ($proceed == 1)
                <div class="mt-4">
                    <a href="{{ route('checkout') }}" class="btn os-btn-checkout btn-block">
                        {{ translate('Proceed to Checkout') }} ({{ sprintf("%02d", count($carts)) }})
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>
<style>
    /* Theme colors */
:root{
  --os-red:#c70a04;
  --os-white:#ffffff;
  --os-black:#212121;
}

/* Card */
.os-card{
  border-radius:16px !important;
  overflow:hidden;
  box-shadow: 0 10px 28px rgba(0,0,0,.08);
}

/* Header */
.os-header{
  background: var(--os-white);
  border-bottom: 1px solid rgba(0,0,0,.06);
  padding: 18px 18px 14px;
}
.os-title{
  font-size:16px;
  font-weight:900;
  color: var(--os-black);
}
.os-subtitle{
  font-size:12px;
  color: rgba(33,33,33,.65);
  margin-top:4px;
}

/* NEW OFFER badge */
.os-offer-badge{
  background: var(--os-red);
  color: var(--os-white);
  font-size:11px;
  font-weight:900;
  padding:8px 10px;
  border-radius:999px;
  letter-spacing:.5px;
  text-transform:uppercase;
}

/* Alert */
.os-alert{
  background: rgba(199,10,4,.08);
  border: 1px solid rgba(199,10,4,.22);
  color: var(--os-black);
  border-radius:12px;
  padding:10px 12px;
  font-size:12px;
}

/* Body */
.os-body{ padding: 16px 18px 18px; }

/* Chips */
.os-chip{
  background: rgba(33,33,33,.06);
  border: 1px solid rgba(33,33,33,.10);
  border-radius:14px;
  padding:10px 12px;
  display:flex;
  align-items:center;
  justify-content:space-between;
}
.os-chip-dark{
  background: rgba(199,10,4,.08);
  border-color: rgba(199,10,4,.22);
}
.os-chip-label{ font-size:12px; color: rgba(33,33,33,.70); font-weight:700; }
.os-chip-value{ font-size:14px; color: var(--os-black); font-weight:900; }

/* Breakdown */
.os-breakdown{
  border: 1px solid rgba(0,0,0,.08);
  border-radius:16px;
  padding:12px;
  background:#fff;
}
.os-row{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  padding:10px 0;
}
.os-row + .os-row{ border-top: 1px dashed rgba(0,0,0,.10); }
.os-row-left{ min-width:0; padding-right:10px; }
.os-row-title{ font-size:13px; color: var(--os-black); font-weight:800; }
.os-row-meta{ font-size:12px; color: rgba(33,33,33,.55); margin-top:2px; }
.os-row-right{ font-size:13px; font-weight:900; color: var(--os-black); text-align:right; }
.os-row-discount .os-row-right{ color: var(--os-red); }

/* Divider */
.os-divider{
  height:1px;
  background: rgba(0,0,0,.10);
  margin: 12px 0;
}

/* Total */
.os-total{
  display:flex;
  align-items:center;
  justify-content:space-between;
  background: rgba(199,10,4,.06);
  border: 1px solid rgba(199,10,4,.18);
  border-radius:14px;
  padding:12px;
}
.os-total-title{
  font-size:12px;
  font-weight:900;
  color: var(--os-black);
  text-transform:uppercase;
  letter-spacing:.5px;
}
.os-total-meta{
  font-size:12px;
  color: rgba(33,33,33,.65);
  margin-top:2px;
}
.os-total-right{
  font-size:18px;
  font-weight:1000;
  color: var(--os-red);
}

/* Coupon */
.os-coupon-wrap{
  display:flex;
  gap:10px;
  align-items:center;
}
.os-input{
  border-radius:12px !important;
  border:1px solid rgba(0,0,0,.12) !important;
  height:44px;
}
.os-btn-primary{
  background: var(--os-red) !important;
  border-color: var(--os-red) !important;
  color: var(--os-white) !important;
  border-radius:12px !important;
  height:44px;
  font-weight:900;
  padding:0 14px;
}
.os-coupon-applied{
  background: var(--os-red);
  border-radius:16px;
  padding:14px;
}
.os-btn-light{
  background: #fff !important;
  border: 1px solid rgba(255,255,255,.55) !important;
  border-radius:12px !important;
  font-weight:900;
  height:40px;
}

/* Checkout */
.os-btn-checkout{
  background: var(--os-black) !important;
  border-color: var(--os-black) !important;
  color: var(--os-white) !important;
  border-radius:14px !important;
  padding:14px 16px !important;
  font-weight:1000;
}
.os-btn-checkout:hover{
  filter: brightness(1.05);
}

</style>
    