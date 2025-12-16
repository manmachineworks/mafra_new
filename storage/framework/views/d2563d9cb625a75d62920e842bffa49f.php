<?php $__currentLoopData = $inhouse_top_brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inhouse_top_brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="d-flex  flex-lg-wrap flex-xxl-nowrap align-items-center justify-content-between" style="margin-bottom: 0.75rem;">
        <div class="d-flex align-items-center mr-2">
            <div class="d-flex align-items-center rounded-2 border overflow-hidden mr-3" style="min-height: 48px !important; min-width: 48px !important;max-height: 48px !important; max-width: 48px !important;">
                <img src="<?php echo e(uploaded_asset($inhouse_top_brand->logo)); ?>" alt="<?php echo e(translate('brand')); ?>"
                    class="h-100 img-fit lazyload" onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">
            </div>
            <h4 class="fs-13 fw-600 text-dark mb-0 text-truncate-2">
                <?php
                    $lang = App::getLocale();
                    $brand = App\Models\BrandTranslation::where('brand_id', $inhouse_top_brand->id)
                        ->where('lang', $lang)
                        ->first();
                ?>
                <?php echo e($brand ? $brand->name : translate('Not Found')); ?>

            </h4>
        </div>
        <h4 class="fs-13 fw-600 text-danger mb-0 py-2">
            <?php echo e(single_price($inhouse_top_brand->total)); ?></h4>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\wamp64\www\mafra_ind\resources\views/backend/dashboard/inhouse_top_brands.blade.php ENDPATH**/ ?>