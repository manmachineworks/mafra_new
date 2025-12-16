

<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0"><?php echo e(translate('Order Details')); ?></h1>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                </div>
                <?php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                    $admin_user_id = get_admin()->id;
                ?>
                <?php if($order->seller_id == $admin_user_id || get_setting('product_manage_by_admin') == 1): ?>

                    <!--Assign Delivery Boy-->
                    <?php if(addon_is_activated('delivery_boy')): ?>
                        <div class="col-md-3 ml-auto">
                            <label for="assign_deliver_boy"><?php echo e(translate('Assign Deliver Boy')); ?></label>
                            <?php if(($delivery_status == 'pending' || $delivery_status == 'confirmed' || $delivery_status == 'picked_up') && auth()->user()->can('assign_delivery_boy_for_orders')): ?>
                                <select class="form-control aiz-selectpicker" data-live-search="true"
                                    data-minimum-results-for-search="Infinity" id="assign_deliver_boy">
                                    <option value=""><?php echo e(translate('Select Delivery Boy')); ?></option>
                                    <?php $__currentLoopData = $delivery_boys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery_boy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($delivery_boy->id); ?>"
                                            <?php if($order->assign_delivery_boy == $delivery_boy->id): ?> selected <?php endif; ?>>
                                            <?php echo e($delivery_boy->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            <?php else: ?>
                                <input type="text" class="form-control" value="<?php echo e(optional($order->delivery_boy)->name); ?>"
                                    disabled>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="col-md-3 ml-auto">
                        <label for="update_payment_status"><?php echo e(translate('Payment Status')); ?></label>
                        <?php if(auth()->user()->can('update_order_payment_status') && $payment_status == 'unpaid'): ?>
                            
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity" id="update_payment_status" onchange="confirm_payment_status()">
                                <option value="unpaid" <?php if($payment_status == 'unpaid'): ?> selected <?php endif; ?>>
                                    <?php echo e(translate('Unpaid')); ?>

                                </option>
                                <option value="paid" <?php if($payment_status == 'paid'): ?> selected <?php endif; ?>>
                                    <?php echo e(translate('Paid')); ?>

                                </option>
                            </select>
                        <?php else: ?>
                            <input type="text" class="form-control" value="<?php echo e(ucfirst($payment_status)); ?>" disabled>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3 ml-auto">
                        <label for="update_delivery_status"><?php echo e(translate('Delivery Status')); ?></label>
                        <?php if(auth()->user()->can('update_order_delivery_status') && $delivery_status != 'delivered' && $delivery_status != 'cancelled'): ?>
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                id="update_delivery_status">
                                <option value="pending" <?php if($delivery_status == 'pending'): ?> selected <?php endif; ?>>
                                    <?php echo e(translate('Pending')); ?>

                                </option>
                                <option value="confirmed" <?php if($delivery_status == 'confirmed'): ?> selected <?php endif; ?>>
                                    <?php echo e(translate('Confirmed')); ?>

                                </option>
                                <option value="picked_up" <?php if($delivery_status == 'picked_up'): ?> selected <?php endif; ?>>
                                    <?php echo e(translate('Picked Up')); ?>

                                </option>
                                <option value="on_the_way" <?php if($delivery_status == 'on_the_way'): ?> selected <?php endif; ?>>
                                    <?php echo e(translate('On The Way')); ?>

                                </option>
                                <option value="delivered" <?php if($delivery_status == 'delivered'): ?> selected <?php endif; ?>>
                                    <?php echo e(translate('Delivered')); ?>

                                </option>
                                <option value="cancelled" <?php if($delivery_status == 'cancelled'): ?> selected <?php endif; ?>>
                                    <?php echo e(translate('Cancel')); ?>

                                </option>
                            </select>
                        <?php else: ?>
                            <input type="text" class="form-control" value="<?php echo e($delivery_status); ?>" disabled>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3 ml-auto">
                        <label for="update_tracking_code">
                            <?php echo e(translate('Tracking Code (optional)')); ?>

                        </label>
                        <input type="text" class="form-control" id="update_tracking_code"
                            value="<?php echo e($order->tracking_code); ?>">
                    </div>
                    <div class="col-md-3 ml-auto">
                        <label><?php echo e(translate('Shiprocket')); ?></label>
                        <?php if($order->shiprocket_shipment_id): ?>
                            <div class="card border shadow-none">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge badge-inline badge-success text-capitalize">
                                            <?php echo e(translate($order->shiprocket_status ?? 'created')); ?>

                                        </span>
                                        <?php if($order->shiprocket_awb): ?>
                                            <span class="text-muted"><?php echo e(translate('AWB')); ?>: <?php echo e($order->shiprocket_awb); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="small text-muted mt-2">
                                        <div><?php echo e(translate('Shiprocket Order ID')); ?>: <?php echo e($order->shiprocket_order_id ?? '-'); ?></div>
                                        <div><?php echo e(translate('Shiprocket Shipment ID')); ?>: <?php echo e($order->shiprocket_shipment_id ?? '-'); ?></div>
                                    </div>
                                    <?php if($order->shiprocket_label_url): ?>
                                        <a href="<?php echo e($order->shiprocket_label_url); ?>" target="_blank" class="d-block small mt-2">
                                            <?php echo e(translate('Label/Tracking')); ?>

                                        </a>
                                    <?php endif; ?>
                                    <button class="btn btn-soft-primary btn-sm mt-2" id="refresh_shiprocket_status">
                                        <?php echo e(translate('Refresh Status')); ?>

                                    </button>
                                </div>
                            </div>
                        <?php else: ?>
                            <button class="btn btn-primary w-100" id="push_to_shiprocket">
                                <?php echo e(translate('Push to Shiprocket')); ?>

                            </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <?php
                    $removedXML = '<?xml version="1.0" encoding="UTF-8"?>';
                ?>
                <?php echo str_replace($removedXML, '', QrCode::size(100)->generate($order->code)); ?>

            </div>
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                    <?php if(json_decode($order->shipping_address)): ?>
                        <address>
                            <strong class="text-main">
                                <?php echo e(json_decode($order->shipping_address)->name); ?>

                            </strong><br>
                            <?php echo e(json_decode($order->shipping_address)->email); ?><br>
                            <?php echo e(json_decode($order->shipping_address)->phone); ?><br>
                            <?php echo e(json_decode($order->shipping_address)->address); ?>, <?php echo e(json_decode($order->shipping_address)->city); ?>, <?php if(isset(json_decode($order->shipping_address)->state)): ?> <?php echo e(json_decode($order->shipping_address)->state); ?> - <?php endif; ?> <?php echo e(json_decode($order->shipping_address)->postal_code); ?><br>
                            <?php echo e(json_decode($order->shipping_address)->country); ?>

                        </address>
                    <?php else: ?>
                        <address>
                            <strong class="text-main">
                                <?php echo e($order->user->name); ?>

                            </strong><br>
                            <?php echo e($order->user->email); ?><br>
                            <?php echo e($order->user->phone); ?><br>
                        </address>
                    <?php endif; ?>
                    <?php if($order->manual_payment && is_array(json_decode($order->manual_payment_data, true))): ?>
                        <br>
                        <strong class="text-main"><?php echo e(translate('Payment Information')); ?></strong><br>
                        <?php echo e(translate('Name')); ?>: <?php echo e(json_decode($order->manual_payment_data)->name); ?>,
                        <?php echo e(translate('Amount')); ?>:
                        <?php echo e(single_price(json_decode($order->manual_payment_data)->amount)); ?>,
                        <?php echo e(translate('TRX ID')); ?>: <?php echo e(json_decode($order->manual_payment_data)->trx_id); ?>

                        <br>
                        <a href="<?php echo e(uploaded_asset(json_decode($order->manual_payment_data)->photo)); ?>" target="_blank">
                            <img src="<?php echo e(uploaded_asset(json_decode($order->manual_payment_data)->photo)); ?>" alt=""
                                height="100">
                        </a>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <table class="ml-auto">
                        <tbody>
                            <tr>
                                <td class="text-main text-bold"><?php echo e(translate('Order #')); ?></td>
                                <td class="text-info text-bold text-right"> <?php echo e($order->code); ?></td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold"><?php echo e(translate('Order Status')); ?></td>
                                <td class="text-right">
                                    <?php if($delivery_status == 'delivered'): ?>
                                        <span class="badge badge-inline badge-success">
                                            <?php echo e(translate(ucfirst(str_replace('_', ' ', $delivery_status)))); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-inline badge-info">
                                            <?php echo e(translate(ucfirst(str_replace('_', ' ', $delivery_status)))); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold"><?php echo e(translate('Order Date')); ?> </td>
                                <td class="text-right"><?php echo e(date('d-m-Y h:i A', $order->date)); ?></td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    <?php echo e(translate('Total amount')); ?>

                                </td>
                                <td class="text-right">
                                    <?php echo e(single_price($order->grand_total)); ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold"><?php echo e(translate('Payment method')); ?></td>
                                <td class="text-right">
                                    <?php echo e(translate(ucfirst(str_replace('_', ' ', $order->payment_type)))); ?></td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold"><?php echo e(translate('Additional Info')); ?></td>
                                <td class="text-right"><?php echo e($order->additional_info); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table-bordered aiz-table invoice-summary table">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th data-breakpoints="lg" class="min-col">#</th>
                                <th width="10%"><?php echo e(translate('Photo')); ?></th>
                                <th class="text-uppercase"><?php echo e(translate('Description')); ?></th>
                                <th data-breakpoints="lg" class="text-uppercase"><?php echo e(translate('Delivery Type')); ?></th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    <?php echo e(translate('Qty')); ?>

                                </th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    <?php echo e(translate('Price')); ?></th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-right">
                                    <?php echo e(translate('Total')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $orderDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td>
                                        <?php if($orderDetail->product != null && $orderDetail->product->auction_product == 0): ?>
                                            <a href="<?php echo e(route('product', $orderDetail->product->slug)); ?>" target="_blank">
                                                <img height="50" src="<?php echo e(uploaded_asset($orderDetail->product->thumbnail_img)); ?>">
                                            </a>
                                        <?php elseif($orderDetail->product != null && $orderDetail->product->auction_product == 1): ?>
                                            <a href="<?php echo e(route('auction-product', $orderDetail->product->slug)); ?>" target="_blank">
                                                <img height="50" src="<?php echo e(uploaded_asset($orderDetail->product->thumbnail_img)); ?>">
                                            </a>
                                        <?php else: ?>
                                            <strong><?php echo e(translate('N/A')); ?></strong>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($orderDetail->product != null && $orderDetail->product->auction_product == 0): ?>
                                            <strong>
                                                <a href="<?php echo e(route('product', $orderDetail->product->slug)); ?>" target="_blank"
                                                    class="text-muted">
                                                    <?php echo e($orderDetail->product->getTranslation('name')); ?>

                                                </a>
                                            </strong>
                                            <small>
                                                <?php echo e($orderDetail->variation); ?>

                                            </small>
                                            <br>
                                            <small>
                                                <?php
                                                    $product_stock = $orderDetail->product->stocks->where('variant', $orderDetail->variation)->first();
                                                ?>
                                                <?php echo e(translate('SKU')); ?>: <?php echo e($product_stock['sku'] ?? ''); ?>

                                            </small>
                                        <?php elseif($orderDetail->product != null && $orderDetail->product->auction_product == 1): ?>
                                            <strong>
                                                <a href="<?php echo e(route('auction-product', $orderDetail->product->slug)); ?>" target="_blank"
                                                    class="text-muted">
                                                    <?php echo e($orderDetail->product->getTranslation('name')); ?>

                                                </a>
                                            </strong>
                                        <?php else: ?>
                                            <strong><?php echo e(translate('Product Unavailable')); ?></strong>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($order->shipping_type != null && $order->shipping_type == 'home_delivery'): ?>
                                            <?php echo e(translate('Home Delivery')); ?>

                                        <?php elseif($order->shipping_type == 'pickup_point'): ?>
                                            <?php if($order->pickup_point != null): ?>
                                                <?php echo e($order->pickup_point->getTranslation('name')); ?>

                                                (<?php echo e(translate('Pickup Point')); ?>)
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
                                    <td class="text-center">
                                        <?php echo e($orderDetail->quantity); ?>

                                    </td>
                                    <td class="text-center">
                                        <?php echo e(single_price($orderDetail->price / $orderDetail->quantity)); ?>

                                    </td>
                                    <td class="text-center">
                                        <?php echo e(single_price($orderDetail->price)); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted"><?php echo e(translate('Sub Total')); ?> :</strong>
                            </td>
                            <td>
                                <?php echo e(single_price($order->orderDetails->sum('price'))); ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted"><?php echo e(translate('Tax')); ?> :</strong>
                            </td>
                            <td>
                                <?php echo e(single_price($order->orderDetails->sum('tax'))); ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted"><?php echo e(translate('Shipping')); ?> :</strong>
                            </td>
                            <td>
                                <?php echo e(single_price($order->orderDetails->sum('shipping_cost'))); ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted"><?php echo e(translate('Coupon')); ?> :</strong>
                            </td>
                            <td>
                                <?php echo e(single_price($order->coupon_discount)); ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted"><?php echo e(translate('TOTAL')); ?> :</strong>
                            </td>
                            <td class="text-muted h5">
                                <?php echo e(single_price($order->grand_total)); ?>

                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="no-print text-right">
                    <a href="<?php echo e(route('invoice.download', $order->id)); ?>" type="button" class="btn btn-icon btn-light"><i
                            class="las la-print"></i></a>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>

    <!-- confirm payment Status Modal -->
    <div id="confirm-payment-status" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content p-2rem">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <g id="Octicons" transform="translate(-0.14 -1.02)">
                          <g id="alert" transform="translate(0.14 1.02)">
                            <path id="Shape" d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" transform="translate(-0.14 -1.02)" fill="#ffc700" fill-rule="evenodd"/>
                          </g>
                        </g>
                    </svg>
                    <p class="mt-3 mb-3 fs-16 fw-700"><?php echo e(translate('Are you sure you want to change the payment status?')); ?></p>
                    <button type="button" class="btn btn-light rounded-2 mt-2 fs-13 fw-700 w-150px" data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
                    <button type="button" onclick="update_payment_status()" class="btn btn-success rounded-2 mt-2 fs-13 fw-700 w-150px"><?php echo e(translate('Confirm')); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        $('#assign_deliver_boy').on('change', function() {
            var order_id = <?php echo e($order->id); ?>;
            var delivery_boy = $('#assign_deliver_boy').val();
            $.post('<?php echo e(route('orders.delivery-boy-assign')); ?>', {
                _token: '<?php echo e(@csrf_token()); ?>',
                order_id: order_id,
                delivery_boy: delivery_boy
            }, function(data) {
                AIZ.plugins.notify('success', '<?php echo e(translate('Delivery boy has been assigned')); ?>');
            });
        });
        $('#update_delivery_status').on('change', function() {
            var order_id = <?php echo e($order->id); ?>;
            var status = $('#update_delivery_status').val();
            $.post('<?php echo e(route('orders.update_delivery_status')); ?>', {
                _token: '<?php echo e(@csrf_token()); ?>',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '<?php echo e(translate('Delivery status has been updated')); ?>');
                location.reload();
            });
        });

        // Payment Status Update
        function confirm_payment_status(value){
            $('#confirm-payment-status').modal('show');
        }

        function update_payment_status(){
            $('#confirm-payment-status').modal('hide');
            var order_id = <?php echo e($order->id); ?>;
            $.post('<?php echo e(route('orders.update_payment_status')); ?>', {
                _token: '<?php echo e(@csrf_token()); ?>',
                order_id: order_id,
                status: 'paid'
            }, function(data) {
                $('#update_payment_status').prop('disabled', true);
                AIZ.plugins.bootstrapSelect('refresh');
                AIZ.plugins.notify('success', '<?php echo e(translate('Payment status has been updated')); ?>');
                location.reload();
            });
        }

        $('#update_tracking_code').on('change', function() {
            var order_id = <?php echo e($order->id); ?>;
            var tracking_code = $('#update_tracking_code').val();
            $.post('<?php echo e(route('orders.update_tracking_code')); ?>', {
                _token: '<?php echo e(@csrf_token()); ?>',
                order_id: order_id,
                tracking_code: tracking_code
            }, function(data) {
                AIZ.plugins.notify('success', '<?php echo e(translate('Order tracking code has been updated')); ?>');
            });
        });

        $('#push_to_shiprocket').on('click', function() {
            var button = $(this);
            button.prop('disabled', true);
            $.post('<?php echo e(route('orders.push_to_shiprocket')); ?>', {
                _token: '<?php echo e(@csrf_token()); ?>',
                order_id: <?php echo e($order->id); ?>

            }, function(data) {
                AIZ.plugins.notify('success', data.message ?? "<?php echo e(translate('Pushed to Shiprocket')); ?>");
                location.reload();
            }).fail(function(xhr) {
                button.prop('disabled', false);
                var message = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : "<?php echo e(translate('Failed to push to Shiprocket')); ?>";
                AIZ.plugins.notify('danger', message);
            });
        });

        $('#refresh_shiprocket_status').on('click', function() {
            var button = $(this);
            button.prop('disabled', true);
            $.post('<?php echo e(route('orders.shiprocket_track')); ?>', {
                _token: '<?php echo e(@csrf_token()); ?>',
                order_id: <?php echo e($order->id); ?>

            }, function(data) {
                AIZ.plugins.notify('success', data.message || "<?php echo e(translate('Shiprocket status updated')); ?>");
                location.reload();
            }).fail(function(xhr) {
                button.prop('disabled', false);
                var message = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : "<?php echo e(translate('Failed to fetch Shiprocket status')); ?>";
                AIZ.plugins.notify('danger', message);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\mafra_ind\resources\views/backend/sales/show.blade.php ENDPATH**/ ?>