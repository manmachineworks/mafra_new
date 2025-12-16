  <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="mb-4">
      <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
              <p class="mb-2 fs-16 fw-700 deep-blue mb-0"><a class="deep-blue" href="<?php echo e(route('purchase_history.details', encrypt($order->id))); ?>"><?php echo e(translate('Order ID')); ?> - <?php echo e($order->code); ?></a></p>
              <span class="text-muted d-block d-md-none fs-12"><?php echo e(translate('Date')); ?>: <?php echo e(date('d-m-Y', $order->date)); ?></span>
          </div>

          <!-- Mobile-only buttons -->
          <div class="d-flex gap-2 d-md-none">
              <a type="button" href="<?php echo e(route('re_order', encrypt($order->id))); ?>" class="btn btn-sm border  rounded px-4 py-1 text-muted reorder-btn">
                  <?php echo e(translate('Reorder')); ?>

              </a>

              <div class="dropdown">
                  <button type="button"
                      class="btn btn-sm dropdown-toggle text-white px-4 py-1 rounded btn-options ml-2"
                      data-toggle="dropdown">
                      <?php echo e(translate('Options')); ?>

                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                      <a class="dropdown-item text-secondary dropdown-bg-hover" href="<?php echo e(route('purchase_history.details', encrypt($order->id))); ?>"><i class="las la-eye mr-2"></i><?php echo e(translate('View')); ?></a>
                      <a class="dropdown-item text-secondary dropdown-bg-hover" href="<?php echo e(route('invoice.download', $order->id)); ?>"><i class="las la-download mr-2"></i><?php echo e(translate('Invoice')); ?></a>
                      <?php if($order->delivery_status == 'pending' && $order->payment_status == 'unpaid'): ?>
                        <a href="javascript:void(0)"  class="dropdown-item text-secondary dropdown-bg-hover confirm-delete" data-href="<?php echo e(route('purchase_history.destroy', $order->id)); ?>"><i class="las la-trash mr-2"></i> <?php echo e(translate('Cancel')); ?></a>
                      <?php endif; ?>
                  </div>
              </div>
          </div>

      </div>

      <!-- Desktop-only buttons (original position) -->
      <div class="row align-items-center mb-2 d-none d-md-flex">
          <div class="col-md-6">
              <div class="row fs-12">
                  <div class="col-auto w-200px">
                      <span class="font-weight-bold light-blue"><?php echo e(get_shop_by_user_id($order->seller_id)->name??"Inhouse Products"); ?></span>
                  </div>
                  <div class="col">
                      <span class="text-muted"><?php echo e(translate('Date')); ?>: <?php echo e(date('d-m-Y', $order->date)); ?></span>
                  </div>
              </div>
          </div>
          <div class="col-md-6 text-right">
              <a type="button" class="btn btn-sm border rounded px-4 py-1 text-muted reorder-btn" href="<?php echo e(route('re_order', encrypt($order->id))); ?>">
                  <?php echo e(translate('Reorder')); ?>

              </a>

              <div class="d-inline-block dropdown ml-1">
                  <button type="button"
                      class="btn btn-sm dropdown-toggle text-white px-4 py-1 rounded btn-options"
                      data-toggle="dropdown">
                      <?php echo e(translate('Options')); ?>

                  </button>

                  <div class="dropdown-menu dropdown-menu-right ">
                      <a class="dropdown-item text-secondary dropdown-bg-hover" href="<?php echo e(route('purchase_history.details', encrypt($order->id))); ?>"><i class="las la-eye mr-2"></i><?php echo e(translate('View')); ?></a>
                      <a class="dropdown-item text-secondary dropdown-bg-hover" href="<?php echo e(route('invoice.download', $order->id)); ?>"><i class="las la-download mr-2"></i><?php echo e(translate('Invoice')); ?></a>
                      <?php if($order->delivery_status == 'pending' && $order->payment_status == 'unpaid'): ?>
                      <a href="javascript:void(0)"  class="dropdown-item text-secondary dropdown-bg-hover confirm-delete" data-href="<?php echo e(route('purchase_history.destroy', $order->id)); ?>"><i class="las la-trash mr-2"></i> <?php echo e(translate('Cancel')); ?></a>
                      <?php endif; ?>
                  </div>
              </div>
          </div>
      </div>
      <!-- Mobile-only on the way and paid buttons -->
      <div class="d-flex d-md-none text-start mb-2 mt-3">
          <span class="btn btn-sm rounded-pill btn-on-the-way">
              <?php echo e(translate(ucfirst(str_replace('_', ' ', $order->delivery_status)))); ?>

          </span>
          <?php if($order->payment_status == 'paid'): ?>
          <span class="btn btn-sm rounded-pill btn-paid ml-2">
              <?php echo e(translate('Paid')); ?>

          </span>
          <?php else: ?>
          <span class="btn btn-sm rounded-pill btn-unpaid">
              <?php echo e(translate('Unpaid')); ?>

          </span>
          <?php endif; ?>
      </div>

      <hr class="border-dashed">

      <!-- image,product name,price,on the way,paid button row -->
      <!-- image,product name,price,on the way,paid button row -->


      <div class="row align-items-center mb-3">


          <div class="col-md-9">
              <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php if(!$loop->first): ?>
              <hr class="hr-split">
              <?php endif; ?>
              <div class="row">
                  <div class="col-md-8 d-flex align-items-center">
                      <img src="<?php echo e(uploaded_asset($orderDetail->product->thumbnail_img)); ?>"
                          class="img-fluid mr-3 product-history-img">

                      <div class="w-300px text-wrap">
                          <div class="font-weight-semibold fs-14 product-name-color mobile-title-shift text-truncate-2"
                              title="<?php echo e($orderDetail->product->getTranslation('name')); ?>">
                              <?php echo e($orderDetail->product->getTranslation('name')); ?>

                          </div>
                          <div class="text-muted small mb-2 mobile-title-shift"><?php echo e($orderDetail->variation); ?></div>
                      </div>

                  </div>

                  <div class="col-md-4">
                      <div class="font-weight-bold"><?php echo e(single_price($orderDetail->price)); ?></div>
                      <div class="text-muted small"><?php echo e(translate('Qty')); ?> <?php echo e($orderDetail->quantity); ?></div>
                  </div>

              </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>


          <!-- Desktop-only buttons in right column -->
          <div class="col-md-3 text-right d-none d-md-block align-self-start">
              <div>
                  <span class="btn btn-sm rounded-pill btn-on-the-way">
                      <?php echo e(translate(ucfirst(str_replace('_', ' ', $order->delivery_status)))); ?>

                  </span>
              </div>
              <?php if($order->payment_status == 'paid'): ?>
              <div class="mt-2">
                  <span class="btn btn-sm rounded-pill btn-paid">
                      <?php echo e(translate('Paid')); ?>

                  </span>
              </div>
              <?php else: ?>
              <div class="mt-2">
                  <span class="btn btn-sm rounded-pill btn-unpaid">
                      <?php echo e(translate('Unpaid')); ?>

                  </span>    
              </div>
              <?php endif; ?>
          </div>
      </div>


      <hr class="hr-split">
      <hr>

  </div>

  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

  <div class="aiz-pagination mt-4" id="pagination">
      <?php echo e($orders->links()); ?>

  </div><?php /**PATH C:\wamp64\www\mafra_ind\resources\views/frontend/user/single_purchase_history.blade.php ENDPATH**/ ?>