<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['notifications']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['notifications']); ?>
<?php foreach (array_filter((['notifications']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $notificationShowDesign = get_setting('notification_show_type');
    if($notificationShowDesign != 'only_text'){
        $notifyImageDesign = '';
        if($notificationShowDesign == 'design_2'){
            $notifyImageDesign = 'rounded-1';
        }
        elseif($notificationShowDesign == 'design_3'){
            $notifyImageDesign = 'rounded-circle';
        }
    }
?>
<?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <li class="list-group-item d-flex justify-content-between align-items- py-3">
        <div class="media text-inherit">
            <div class="media-body">
                <?php
                    $user_type = auth()->user()->user_type;
                    $notificationType = get_notification_type($notification->notification_type_id, 'id');
                    $notifyContent = $notificationType->getTranslation('default_text');
                ?>
                <div class="d-flex">
                    <?php if($notificationShowDesign != 'only_text'): ?>
                        <div class="size-35px mr-2">
                            <img
                                src="<?php echo e(uploaded_asset($notificationType->image)); ?>"
                                onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/notification.png')); ?>';"
                                class="img-fit h-100 <?php echo e($notifyImageDesign); ?>" >
                        </div>
                    <?php endif; ?>
                    <div>
                        
                        <?php if($notification->type == 'App\Notifications\OrderNotification'): ?>
                            <?php
                                $orderCode  = $notification->data['order_code'];
                                $orderCode = "<span class='text-blue'>".$orderCode."</span>";
                                $notifyContent = str_replace('[[order_code]]', $orderCode, $notifyContent);
                            ?>

                        
                        <?php elseif($notification->type == 'App\Notifications\ShopVerificationNotification'): ?>
                            <?php
                                if($notification->data['status'] == 'submitted'){
                                    $shopName = "<span class='text-blue'>".$notification->data['name']."</span>";
                                    $notifyContent = str_replace('[[shop_name]]', $shopName, $notifyContent);
                                }
                            ?>

                        
                        <?php elseif($notification->type == 'App\Notifications\ShopProductNotification'): ?>
                            <?php
                                $product_id     = $notification->data['id'];
                                $product_type   = $notification->data['type'];
                                $lang           = env('DEFAULT_LANGUAGE');
                                $productName = "<span class='text-blue'>".$notification->data['name']."</span>";
                                $notifyContent = str_replace('[[product_name]]', $productName, $notifyContent);
                            ?>

                        
                        <?php elseif($notification->type == 'App\Notifications\PayoutNotification'): ?>
                            <?php
                                $amount = single_price($notification->data['payment_amount']);
                                $shopName = "<span class='text-blue'>".$notification->data['name']."</span>";

                                $notifyContent = str_replace('[[shop_name]]', $shopName, $notifyContent);
                                $notifyContent = str_replace('[[amount]]', $amount, $notifyContent);
                            ?>

                        
                        <?php elseif($notification->type == 'App\Notifications\PreorderNotification' && addon_is_activated('preorder')): ?>
                            <?php
                                $orderCode  = $notification->data['order_code'];
                                $orderCode = "<span class='text-blue'>".$orderCode."</span>";
                                $notifyContent = str_replace('[[order_code]]', $orderCode, $notifyContent);
                            ?>
                        <?php endif; ?>

                        <a href="<?php echo e(($user_type == 'admin' || $user_type == 'staff') ?
                                    route('admin.notification.read-and-redirect', encrypt($notification->id)) :
                                    route('seller.notification.read-and-redirect', encrypt($notification->id))); ?>">
                            <p class="mb-1 text-dark text-truncate-2">
                                <?php echo $notifyContent; ?>

                            </p>
                            <small class="text-muted">
                                <?php echo e(date('F j Y, g:i a', strtotime($notification->created_at))); ?>

                            </small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </li>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <li class="list-group-item">
        <div class="py-4 text-center fs-16">
            <?php echo e(translate('No notification found')); ?>

        </div>
    </li>
<?php endif; ?>
<?php /**PATH C:\wamp64\www\mafra_ind\resources\views/components/unread_notification.blade.php ENDPATH**/ ?>