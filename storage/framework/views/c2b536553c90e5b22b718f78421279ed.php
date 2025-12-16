

<?php $__env->startSection('content'); ?>

    <div class="card">
        <form class="" action="" id="sort_orders" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6"><?php echo e(translate('All Orders')); ?></h5>
                </div>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['delete_order', 'export_order'])): ?>
                    <div class="dropdown mb-2 mb-md-0">
                        <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                            <?php echo e(translate('Bulk Action')); ?>

                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete_order')): ?>
                                <a class="dropdown-item confirm-alert" href="javascript:void(0)"  data-target="#bulk-delete-modal"><?php echo e(translate('Delete selection')); ?></a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('export_order')): ?>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="order_bulk_export()"><?php echo e(translate('Export')); ?></a>
                            <?php endif; ?>
                            <?php if(auth()->user()->can('unpaid_order_payment_notification_send') && $unpaid_order_payment_notification->status == 1 && Route::currentRouteName() == 'unpaid_orders.index'): ?>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="bulk_unpaid_order_payment_notification()"><?php echo e(translate('Unpaid Order Payment Notification')); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(Route::currentRouteName() == 'offline_payment_orders.index'): ?>
                    <div class="col-lg-2 ml-auto">
                        <select class="form-control aiz-selectpicker" name="order_type" id="order_type">
                            <option value=""><?php echo e(translate('Filter by Order Type')); ?></option>
                            <option value="inhouse_orders" <?php if($order_type == 'inhouse_orders'): ?> selected <?php endif; ?>><?php echo e(translate('Inhouse Orders')); ?></option>
                            <option value="seller_orders" <?php if($order_type == 'seller_orders'): ?> selected <?php endif; ?>><?php echo e(translate('Seller Orders')); ?></option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="col-lg-2 ml-auto">
                    <select class="form-control aiz-selectpicker" name="delivery_status" id="delivery_status">
                        <option value=""><?php echo e(translate('Filter by Delivery Status')); ?></option>
                        <option value="pending" <?php if($delivery_status == 'pending'): ?> selected <?php endif; ?>><?php echo e(translate('Pending')); ?>

                        </option>
                        <option value="confirmed" <?php if($delivery_status == 'confirmed'): ?> selected <?php endif; ?>>
                            <?php echo e(translate('Confirmed')); ?></option>
                        <option value="picked_up" <?php if($delivery_status == 'picked_up'): ?> selected <?php endif; ?>>
                            <?php echo e(translate('Picked Up')); ?></option>
                        <option value="on_the_way" <?php if($delivery_status == 'on_the_way'): ?> selected <?php endif; ?>>
                            <?php echo e(translate('On The Way')); ?></option>
                        <option value="delivered" <?php if($delivery_status == 'delivered'): ?> selected <?php endif; ?>>
                            <?php echo e(translate('Delivered')); ?></option>
                        <option value="cancelled" <?php if($delivery_status == 'cancelled'): ?> selected <?php endif; ?>>
                            <?php echo e(translate('Cancel')); ?></option>
                    </select>
                </div>
                <?php if(Route::currentRouteName() != 'unpaid_orders.index'): ?>
                    <div class="col-lg-2 ml-auto">
                        <select class="form-control aiz-selectpicker" name="payment_status" id="payment_status">
                            <option value=""><?php echo e(translate('Filter by Payment Status')); ?></option>
                            <option value="paid"
                                <?php if(isset($payment_status)): ?> <?php if($payment_status == 'paid'): ?> selected <?php endif; ?> <?php endif; ?>>
                                <?php echo e(translate('Paid')); ?></option>
                            <option value="unpaid"
                                <?php if(isset($payment_status)): ?> <?php if($payment_status == 'unpaid'): ?> selected <?php endif; ?> <?php endif; ?>>
                                <?php echo e(translate('Unpaid')); ?></option>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="col-lg-1">
                    <div class="form-group mb-0">
                        <input type="text" class="aiz-date-range form-control" value="<?php echo e($date); ?>"
                            name="date" placeholder="<?php echo e(translate('Filter by date')); ?>" data-format="DD-MM-Y"
                            data-separator=" to " data-advanced-range="true" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search"
                            name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?>
                            placeholder="<?php echo e(translate('Type Order code & hit Enter')); ?>">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary"><?php echo e(translate('Filter')); ?></button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <?php if(auth()->user()->can('delete_order') || auth()->user()->can('export_order')): ?>
                                <th>
                                    <div class="form-group">
                                        <div class="aiz-checkbox-inline">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-all">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                </th>
                            <?php else: ?>
                                <th data-breakpoints="lg">#</th>
                            <?php endif; ?>

                            <th><?php echo e(translate('Order Code')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Num. of Products')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Customer')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Seller')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Amount')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Delivery Status')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Payment method')); ?></th>
                            <th data-breakpoints="md"><?php echo e(translate('Payment Status')); ?></th>
                            <?php if(addon_is_activated('refund_request')): ?>
                                <th><?php echo e(translate('Refund')); ?></th>
                            <?php endif; ?>
                            <th class="text-right" width="15%"><?php echo e(translate('options')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <?php if(auth()->user()->can('delete_order') || auth()->user()->can('export_order')): ?>
                                    <td>
                                        <div class="form-group">
                                            <div class="aiz-checkbox-inline">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" class="check-one" name="id[]"
                                                        value="<?php echo e($order->id); ?>">
                                                    <span class="aiz-square-check"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                <?php else: ?>
                                    <td><?php echo e($key + 1 + ($orders->currentPage() - 1) * $orders->perPage()); ?></td>
                                <?php endif; ?>
                                <td>
                                    <?php echo e($order->code); ?>

                                    <?php if($order->viewed == 0): ?>
                                        <span class="badge badge-inline badge-info"><?php echo e(translate('New')); ?></span>
                                    <?php endif; ?>
                                    <?php if(addon_is_activated('pos_system') && $order->order_from == 'pos'): ?>
                                        <span class="badge badge-inline badge-danger"><?php echo e(translate('POS')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo e(count($order->orderDetails)); ?>

                                </td>
                                <td>
                                    <?php if($order->user != null): ?>
                                        <?php echo e($order->user->name); ?>

                                    <?php else: ?>
                                        Guest (<?php echo e($order->guest_id); ?>)
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($order->shop): ?>
                                        <?php echo e($order->shop->name); ?>

                                    <?php else: ?>
                                        <?php echo e(translate('Inhouse Order')); ?>

                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo e(single_price($order->grand_total)); ?>

                                </td>
                                <td>
                                    <?php echo e(translate(ucfirst(str_replace('_', ' ', $order->delivery_status)))); ?>

                                </td>
                                <td>
                                    <?php echo e(translate(ucfirst(str_replace('_', ' ', $order->payment_type)))); ?>

                                </td>
                                <td>
                                    <?php if($order->payment_status == 'paid'): ?>
                                        <span class="badge badge-inline badge-success"><?php echo e(translate('Paid')); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-inline badge-danger"><?php echo e(translate('Unpaid')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <?php if(addon_is_activated('refund_request')): ?>
                                    <td>
                                        <?php if(count($order->refund_requests) > 0): ?>
                                            <?php echo e(count($order->refund_requests)); ?> <?php echo e(translate('Refund')); ?>

                                        <?php else: ?>
                                            <?php echo e(translate('No Refund')); ?>

                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <td class="text-right">
                                    <?php if(addon_is_activated('pos_system') && $order->order_from == 'pos'): ?>
                                        <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                            href="<?php echo e(route('admin.invoice.thermal_printer', $order->id)); ?>" target="_blank"
                                            title="<?php echo e(translate('Thermal Printer')); ?>">
                                            <i class="las la-print"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_order_details')): ?>
                                        <?php
                                            $order_detail_route = route('orders.show', encrypt($order->id));
                                            if (Route::currentRouteName() == 'seller_orders.index') {
                                                $order_detail_route = route('seller_orders.show', encrypt($order->id));
                                            } elseif (Route::currentRouteName() == 'pick_up_point.index') {
                                                $order_detail_route = route('pick_up_point.order_show', encrypt($order->id));
                                            }
                                            if (Route::currentRouteName() == 'inhouse_orders.index') {
                                                $order_detail_route = route('inhouse_orders.show', encrypt($order->id));
                                            }
                                        ?>
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="<?php echo e($order_detail_route); ?>" title="<?php echo e(translate('View')); ?>">
                                            <i class="las la-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                        href="<?php echo e(route('invoice.download', $order->id)); ?>"
                                        title="<?php echo e(translate('Download Invoice')); ?>">
                                        <i class="las la-download"></i>
                                    </a>
                                    <?php if(auth()->user()->can('unpaid_order_payment_notification_send') && $order->payment_status == 'unpaid' && $unpaid_order_payment_notification->status == 1): ?>
                                        <a class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                            href="javascript:void();" onclick="unpaid_order_payment_notification('<?php echo e($order->id); ?>');"
                                            title="<?php echo e(translate('Unpaid Order Payment Notification')); ?>">
                                            <i class="las la-bell"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete_order')): ?>
                                        <a href="#"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="<?php echo e(route('orders.destroy', $order->id)); ?>"
                                            title="<?php echo e(translate('Delete')); ?>">
                                            <i class="las la-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <div class="aiz-pagination">
                    <?php echo e($orders->appends(request()->input())->links()); ?>

                </div>

            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <!-- Delete modal -->
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Bulk Delete modal -->
    <?php echo $__env->make('modals.bulk_delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div id="complete_unpaid_order_payment" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content pb-2rem px-2rem">
                <div class="modal-header border-0">
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <form class="form-horizontal" action="<?php echo e(route('unpaid_order_payment_notification')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body text-center">
                        <input type="hidden" name="order_ids" value="" id="order_ids">
                        <p class="mt-2 mb-2 fs-16 fw-700"><?php echo e(translate('Are you sure to send notification for the selected orders?')); ?></p>
                        <button type="submit" class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-250px"><?php echo e(translate('Send Notification')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        function sort_orders(el){
            $('#sort_orders').submit();
        }

        function bulk_delete() {
            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "<?php echo e(route('bulk-order-delete')); ?>",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', 'Selected items deleted successfully');
                        location.reload();
                    }
                }
            });
        }
        
        function order_bulk_export (){
            var url = '<?php echo e(route('order-bulk-export')); ?>';
            $("#sort_orders").attr("action", url);
            $('#sort_orders').submit();
            $("#sort_orders").attr("action", '');
        }

        // Unpaid Order Payment Notification
        function unpaid_order_payment_notification(order_id){
            var orderIds = [];
            orderIds.push(order_id);
            $('#order_ids').val(orderIds);
            $('#complete_unpaid_order_payment').modal('show', {backdrop: 'static'});
        }

        // Unpaid Order Payment Notification
        function bulk_unpaid_order_payment_notification(){
            var orderIds = [];
            $(".check-one[name='id[]']:checked").each(function() {
                orderIds.push($(this).val());
            });
            if(orderIds.length > 0){
                $('#order_ids').val(orderIds);
                $('#complete_unpaid_order_payment').modal('show', {backdrop: 'static'});
            }
            else{
                AIZ.plugins.notify('danger', '<?php echo e(translate('Please Select Order first.')); ?>');
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\mafra_ind\resources\views/backend/sales/index.blade.php ENDPATH**/ ?>