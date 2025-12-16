

<?php $__env->startSection('panel_content'); ?>
    <!-- Order id -->
    <div class="aiz-titlebar mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="fs-20 fw-700 text-dark"><?php echo e(translate('Order id')); ?>: <?php echo e($order->code); ?></h1>
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="card rounded-0 shadow-none border mb-4">
        <div class="card-header border-bottom-0">
            <h5 class="fs-16 fw-700 text-dark mb-0"><?php echo e(translate('Order Summary')); ?></h5>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-lg-6">
                    <table class="table-borderless table">
                        <tr>
                            <td class="w-50 fw-600"><?php echo e(translate('Order Code')); ?>:</td>
                            <td><?php echo e($order->code); ?></td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600"><?php echo e(translate('Customer')); ?>:</td>
                            <td><?php echo e(json_decode($order->shipping_address)->name); ?></td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600"><?php echo e(translate('Email')); ?>:</td>
                            <?php if($order->user_id != null): ?>
                                <td><?php echo e($order->user->email); ?></td>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600"><?php echo e(translate('Shipping address')); ?>:</td>
                            <td><?php echo e(json_decode($order->shipping_address)->address); ?>,
                                <?php echo e(json_decode($order->shipping_address)->city); ?>,
                                <?php if(isset(json_decode($order->shipping_address)->state)): ?> <?php echo e(json_decode($order->shipping_address)->state); ?> - <?php endif; ?>
                                <?php echo e(json_decode($order->shipping_address)->postal_code); ?>,
                                <?php echo e(json_decode($order->shipping_address)->country); ?>

                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table class="table-borderless table">
                        <tr>
                            <td class="w-50 fw-600"><?php echo e(translate('Order date')); ?>:</td>
                            <td><?php echo e(date('d-m-Y H:i A', $order->date)); ?></td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600"><?php echo e(translate('Order status')); ?>:</td>
                            <td><?php echo e(translate(ucfirst(str_replace('_', ' ', $order->delivery_status)))); ?></td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600"><?php echo e(translate('Total order amount')); ?>:</td>
                            <td><?php echo e(single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax'))); ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600"><?php echo e(translate('Shipping method')); ?>:</td>
                            <td><?php echo e(translate('Flat shipping rate')); ?></td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600"><?php echo e(translate('Payment method')); ?>:</td>
                            <td><?php echo e(ucfirst(translate(str_replace('_', ' ', $order->payment_type)))); ?></td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold"><?php echo e(translate('Additional Info')); ?></td>
                            <td class=""><?php echo e($order->additional_info); ?></td>
                        </tr>
                        <?php if($order->tracking_code): ?>
                            <tr>
                                <td class="w-50 fw-600"><?php echo e(translate('Tracking code')); ?>:</td>
                                <td><?php echo e($order->tracking_code); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if($order->shiprocket_awb): ?>
                            <tr>
                                <td class="w-50 fw-600"><?php echo e(translate('Shiprocket AWB')); ?>:</td>
                                <td><?php echo e($order->shiprocket_awb); ?></td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-600"><?php echo e(translate('Shiprocket Order ID')); ?>:</td>
                                <td><?php echo e($order->shiprocket_order_id ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-600"><?php echo e(translate('Shiprocket Shipment ID')); ?>:</td>
                                <td><?php echo e($order->shiprocket_shipment_id ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-600"><?php echo e(translate('Shiprocket Status')); ?>:</td>
                                <td><?php echo e(translate($order->shiprocket_status ?? 'N/A')); ?></td>
                            </tr>
                            <?php if($order->shiprocket_label_url): ?>
                                <tr>
                                    <td class="w-50 fw-600"><?php echo e(translate('Track/Label')); ?>:</td>
                                    <td><a href="<?php echo e($order->shiprocket_label_url); ?>" target="_blank"><?php echo e(translate('View')); ?></a></td>
                                </tr>
                            <?php endif; ?>
                        <?php endif; ?>
                    </table>
                    <?php if($order->shiprocket_awb): ?>
                        <button class="btn btn-outline-primary btn-sm mt-2" id="refresh_shiprocket_status_customer">
                            <?php echo e(translate('Refresh Shiprocket Status')); ?>

                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details -->
    <div class="row gutters-16">
        <div class="col-md-9">
            <div class="card rounded-0 shadow-none border mt-2 mb-4">
                <div class="card-header border-bottom-0">
                    <h5 class="fs-16 fw-700 text-dark mb-0"><?php echo e(translate('Order Details')); ?></h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="aiz-table table">
                        <thead class="text-gray fs-12">
                            <tr>
                                <th class="pl-0">#</th>
                                <th width="30%"><?php echo e(translate('Product')); ?></th>
                                <th data-breakpoints="md"><?php echo e(translate('Variation')); ?></th>
                                <th><?php echo e(translate('Quantity')); ?></th>
                                <th data-breakpoints="md"><?php echo e(translate('Delivery Type')); ?></th>
                                <th><?php echo e(translate('Price')); ?></th>
                                <?php if(addon_is_activated('refund_request')): ?>
                                    <th data-breakpoints="md"><?php echo e(translate('Refund')); ?></th>
                                <?php endif; ?>
                                <th data-breakpoints="md" class="text-right pr-0"><?php echo e(translate('Review')); ?></th>
                            </tr>
                        </thead>
                        <tbody class="fs-14">
                            <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $orderDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="pl-0"><?php echo e(sprintf('%02d', $key+1)); ?></td>
                                    <td>
                                        <?php if($orderDetail->product != null && $orderDetail->product->auction_product == 0): ?>
                                            <a href="<?php echo e(route('product', $orderDetail->product->slug)); ?>"
                                                target="_blank"><?php echo e($orderDetail->product->getTranslation('name')); ?></a>
                                        <?php elseif($orderDetail->product != null && $orderDetail->product->auction_product == 1): ?>
                                            <a href="<?php echo e(route('auction-product', $orderDetail->product->slug)); ?>"
                                                target="_blank"><?php echo e($orderDetail->product->getTranslation('name')); ?></a>
                                        <?php else: ?>
                                            <strong><?php echo e(translate('Product Unavailable')); ?></strong>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo e($orderDetail->variation); ?>

                                    </td>
                                    <td>
                                        <?php echo e($orderDetail->quantity); ?>

                                    </td>
                                    <td>
                                        <?php if($order->shipping_type != null && $order->shipping_type == 'home_delivery'): ?>
                                            <?php echo e(translate('Home Delivery')); ?>

                                        <?php elseif($order->shipping_type == 'pickup_point'): ?>
                                            <?php if($order->pickup_point != null): ?>
                                                <?php echo e($order->pickup_point->name); ?> (<?php echo e(translate('Pickip Point')); ?>)
                                            <?php else: ?>
                                                <?php echo e(translate('Pickup Point')); ?>

                                            <?php endif; ?>
                                        <?php elseif($order->shipping_type == 'carrier'): ?>
                                            <?php if($order->carrier != null): ?>
                                                <?php echo e($order->carrier->name); ?> (<?php echo e(translate('Carrier')); ?>)
                                                <br>
                                                <?php echo e(translate('Transit Time').' - '.$order->carrier->transit_time); ?>

                                            <?php else: ?>
                                                <?php echo e(translate('Carrier')); ?>

                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-700"><?php echo e(single_price($orderDetail->price)); ?></td>
                                    <?php if(addon_is_activated('refund_request')): ?>
                                        <?php
                                            $no_of_max_day = $orderDetail->refund_days;

                                            $last_refund_date = null;
                                            if ($order->delivered_date && $no_of_max_day > 0) {
                                                $last_refund_date = Carbon\Carbon::parse($order->delivered_date)->addDays($no_of_max_day);
                                            }
                                            
                                            $today_date = Carbon\Carbon::now();
                                            
                                        ?>
                                        <td>
                                            <?php if(
                                                    $orderDetail->product != null &&
                                                    $orderDetail->refund_request == null &&
                                                    $last_refund_date &&
                                                    $today_date <= $last_refund_date &&
                                                    $order->payment_status == 'paid' &&
                                                    $order->delivery_status == 'delivered'
                                                ): ?>

                                                <a href="<?php echo e(route('refund_request_send_page', $orderDetail->id)); ?>"
                                                    class="btn btn-outline-dark btn-sm rounded-0">
                                                    <?php echo e(translate('Send')); ?>

                                                </a>
                                            <?php elseif($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 0): ?>
                                                <b class="text-info"><?php echo e(translate('Pending')); ?></b>
                                            <?php elseif($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 2): ?>
                                                <b class="text-danger"><?php echo e(translate('Rejected')); ?></b>
                                            <?php elseif($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 1): ?>
                                                <b class="text-success"><?php echo e(translate('Approved')); ?></b>
                                            <?php elseif($orderDetail->product != null && $orderDetail->refund_days != 0): ?>
                                                <b><?php echo e(translate('N/A')); ?></b>
                                            <?php else: ?>
                                                <b><?php echo e(translate('Non-refundable')); ?></b>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                        <td class="text-xl-right pr-0">
                                            <?php if($orderDetail->delivery_status == 'delivered'): ?>
                                                <a href="javascript:void(0);" onclick="product_review('<?php echo e($orderDetail->product_id); ?>', '<?php echo e($order->id); ?>')"
                                                    class="btn btn-outline-dark btn-sm rounded-0"> <?php echo e(translate('Review')); ?> </a>
                                            <?php else: ?>
                                                <span class="text-danger"><?php echo e(translate('Not Delivered Yet')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Ammount -->
        <div class="col-md-3">
            <div class="card rounded-0 shadow-none border mt-2">
                <div class="card-header border-bottom-0">
                    <b class="fs-16 fw-700 text-dark"><?php echo e(translate('Order Ammount')); ?></b>
                </div>
                <div class="card-body pb-0">
                    <table class="table-borderless table">
                        <tbody>
                            <tr>
                                <td class="w-50 fw-600"><?php echo e(translate('Subtotal')); ?></td>
                                <td class="text-right">
                                    <span class="strong-600"><?php echo e(single_price($order->orderDetails->sum('price'))); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-600"><?php echo e(translate('Shipping')); ?></td>
                                <td class="text-right">
                                    <span class="text-italic"><?php echo e(single_price($order->orderDetails->sum('shipping_cost'))); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-600"><?php echo e(translate('Tax')); ?></td>
                                <td class="text-right">
                                    <span class="text-italic"><?php echo e(single_price($order->orderDetails->sum('tax'))); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-600"><?php echo e(translate('Coupon')); ?></td>
                                <td class="text-right">
                                    <span class="text-italic"><?php echo e(single_price($order->coupon_discount)); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-600"><?php echo e(translate('Total')); ?></td>
                                <td class="text-right">
                                    <strong><?php echo e(single_price($order->grand_total)); ?></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if($order->payment_status == 'unpaid' && $order->delivery_status == 'pending' && $order->manual_payment == 0): ?>
                <button
                    <?php if(addon_is_activated('offline_payment')): ?>
                        onclick="select_payment_type(<?php echo e($order->id); ?>)"
                    <?php else: ?>
                        onclick="online_payment(<?php echo e($order->id); ?>)"
                    <?php endif; ?>
                    class="btn btn-block btn-primary">
                    <?php echo e(translate('Make Payment')); ?>

                </button>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    (function() {
        var btn = document.getElementById('refresh_shiprocket_status_customer');
        if (!btn) return;
        btn.addEventListener('click', function() {
            btn.disabled = true;
            $.post('<?php echo e(route('shiprocket.track')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>',
                order_id: <?php echo e($order->id); ?>

            }, function(data) {
                AIZ.plugins.notify('success', data.message || "<?php echo e(translate('Shiprocket status updated')); ?>");
                location.reload();
            }).fail(function(xhr) {
                btn.disabled = false;
                var message = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : "<?php echo e(translate('Failed to fetch Shiprocket status')); ?>";
                AIZ.plugins.notify('danger', message);
            });
        });
    })();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('modal'); ?>
    <!-- Product Review Modal -->
    <div class="modal fade" id="product-review-modal">
        <div class="modal-dialog">
            <div class="modal-content" id="product-review-modal-content">

            </div>
        </div>
    </div>

    <!-- Select Payment Type Modal -->
    <div class="modal fade" id="payment_type_select_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo e(translate('Select Payment Type')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="order_id" name="order_id" value="<?php echo e($order->id); ?>">
                    <div class="row">
                        <div class="col-md-2">
                            <label><?php echo e(translate('Payment Type')); ?></label>
                        </div>
                        <div class="col-md-10">
                            <div class="mb-3">
                                <select class="form-control aiz-selectpicker rounded-0" onchange="payment_modal(this.value)"
                                    data-minimum-results-for-search="Infinity">
                                    <option value=""><?php echo e(translate('Select One')); ?></option>
                                    <option value="online"><?php echo e(translate('Online payment')); ?></option>
                                    <option value="offline"><?php echo e(translate('Offline payment')); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-sm btn-primary rounded-0 transition-3d-hover mr-1"
                            id="payment_select_type_modal_cancel" data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Online payment Modal -->
    <div class="modal fade" id="online_payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo e(translate('Make Payment')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body gry-bg px-3 pt-3" style="overflow-y: inherit;">
                    <form class="" action="<?php echo e(route('order.re_payment')); ?>"
                        method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
                        <div class="row">
                            <div class="col-md-2">
                                <label><?php echo e(translate('Payment Method')); ?></label>
                            </div>
                            <div class="col-md-10">
                            <div class="mb-3">
                                <select class="form-control selectpicker rounded-0" data-live-search="true" name="payment_option" required>
                                    <?php echo $__env->make('partials.online_payment_options', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php if(get_setting('wallet_system') == 1 && (auth()->user()->balance >= $order->grand_total)): ?>
                                        <option value="wallet"><?php echo e(translate('Wallet')); ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="button" class="btn btn-sm btn-secondary rounded-0 transition-3d-hover mr-1"
                                data-dismiss="modal"><?php echo e(translate('cancel')); ?></button>
                            <button type="submit"
                                class="btn btn-sm btn-primary rounded-0 transition-3d-hover mr-1"><?php echo e(translate('Confirm')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- offline payment Modal -->
    <div class="modal fade" id="offline_order_re_payment_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo e(translate('Offline Order Payment')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="offline_order_re_payment_modal_body"></div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script type="text/javascript">

        function product_review(product_id,order_id) {
            $.post('<?php echo e(route('product_review_modal')); ?>', {
                _token: '<?php echo e(@csrf_token()); ?>',
                product_id: product_id,
                order_id: order_id
            }, function(data) {
                $('#product-review-modal-content').html(data);
                $('#product-review-modal').modal('show', {
                    backdrop: 'static'
                });
                AIZ.extra.inputRating();
            });
        }

        function select_payment_type(id) {
            $('#payment_type_select_modal').modal('show');
        }

        function payment_modal(type) {
            if (type == 'online') {
                $("#payment_select_type_modal_cancel").click();
                online_payment();
            } else if (type == 'offline') {
                $("#payment_select_type_modal_cancel").click();
                $.post('<?php echo e(route('offline_order_re_payment_modal')); ?>', {
                    _token: '<?php echo e(csrf_token()); ?>',
                    order_id: '<?php echo e($order->id); ?>'
                }, function(data) {
                    $('#offline_order_re_payment_modal_body').html(data);
                    $('#offline_order_re_payment_modal').modal('show');
                });
            }
        }

        function online_payment() {
            $('input[name=customer_package_id]').val();
            $('#online_payment_modal').modal('show');
        }

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.user_panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\mafra_ind\resources\views/frontend/user/order_details_customer.blade.php ENDPATH**/ ?>