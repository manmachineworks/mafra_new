<!-- sellers -->
<div class="aiz-carousel dashboard-box-carousel half-outside-arrow" data-items="6.5" data-xl-items="4.5"
    data-lg-items="5" data-md-items="7" data-sm-items="5" data-xs-items="3" data-arrows='true'>
    
    <?php $__currentLoopData = $new_top_sellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $top_sellers_product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="carousel-box top_sellers_products <?php if($key == 0): ?> active <?php endif; ?>" onclick="top_sellers_products(<?php echo e($top_sellers_product->shop_id); ?>, this)">
            <div class="size-80px border border-dashed rounded-2 overflow-hidden p-1">
                <div class="h-100 rounded-2 overflow-hidden d-flex align-items-center">
                    <img src="<?php echo e(uploaded_asset($top_sellers_product->logo)); ?>" alt="<?php echo e(translate('sellers')); ?>"
                        class="img-fit lazyload" onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">
                </div>
            </div>
            <p class="fs-11 fw-400 text-soft-dark text-truncate-2 h-30px px-1 w-80px">
                <?php echo e($top_sellers_product->shop_name); ?>

            </p>
        </div>
        
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- seller products -->
<div class="position-relative">
    <?php $__currentLoopData = $new_top_sellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $top_sellers_product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="top_sellers_product_table top-products-table table-responsive c-scrollbar-light <?php if($key == 0): ?> show <?php endif; ?>" 
            style="max-height: 215px; width: 100%;" id="top_sellers_product_table_<?php echo e($top_sellers_product->shop_id); ?>">
            <table class="table dashboard-table mb-0">
                <thead>
                    <tr class="fs-11 fw-600 text-secondary">
                        <th class="pl-0 border-top-0 border-bottom-1"><?php echo e(translate('Item')); ?></th>
                        <th class="border-top-0 border-bottom-1"><?php echo e(translate('Quantity')); ?></th>
                        <th class="text-right pr-1 border-top-0 border-bottom-1"><?php echo e(translate('Total Price')); ?></th>

                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $top_sellers_product->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $product_img = null;
                            if($row->thumbnail){
                                $product_img = ($row->thumbnail->external_link == null) ? my_asset($row->thumbnail->file_name) : $row->thumbnail->external_link;
                            }
                            $product_url = route('product', $row->product_slug);
                            if ($row->auction_product == 1) {
                                $product_url = route('auction-product', $row->product_slug);
                            }
                        ?>
                        <tr>
                            <td class="pl-0" style="vertical-align: middle">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-2 overflow-hidden"
                                        style="min-height: 48px !important; min-width: 48px !important;max-height: 48px !important; max-width: 48px !important;">
                                        <a href="<?php echo e($product_url); ?>" class="d-block" target="_blank">
                                            <img src="<?php echo e($product_img); ?>" alt="<?php echo e(translate('category')); ?>" 
                                                class="h-100 img-fit lazyload" onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">
                                        </a>
                                    </div>
                                    <a href="<?php echo e($product_url); ?>" target="_blank" class="d-block text-soft-dark fw-400 hov-text-primary ml-2 fs-13" title="<?php echo e($row->name); ?>">
                                        <?php echo e(Str::limit($row->name, 50, ' ...')); ?>

                                    </a>
                                </div>
                            </td>
                            <td style="vertical-align: middle" class="text-soft-dark fw-700">
                                X <?php echo e($row->total_quantity); ?>

                            </td>
                            <td style="vertical-align: middle" class="text-soft-dark fw-700 text-right pr-1">
                                <?php echo e(single_price($row->sale)); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH C:\wamp64\www\mafra_ind\resources\views/backend/dashboard/top_sellers_products_section.blade.php ENDPATH**/ ?>