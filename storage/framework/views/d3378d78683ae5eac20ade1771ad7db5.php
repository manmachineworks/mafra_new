<div class="modal fade" id="wallet_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(translate('Recharge Wallet')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body gry-bg px-3 pt-3" style="overflow-y: inherit;">
                <form class="" action="<?php echo e(route('wallet.recharge')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <label><?php echo e(translate('Payment Method')); ?> <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <select class="form-control selectpicker rounded-0"
                                    data-minimum-results-for-search="Infinity" name="payment_option"
                                    data-live-search="true">
                                    <?php echo $__env->make('partials.online_payment_options', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label><?php echo e(translate('Amount')); ?> <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-8">
                            <input type="number" lang="en" class="form-control mb-3 rounded-0" name="amount"
                                placeholder="<?php echo e(translate('Amount')); ?>" required>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit"
                            class="btn btn-sm btn-primary rounded-0 transition-3d-hover mr-1"><?php echo e(translate('Confirm')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\wamp64\www\mafra_ind\resources\views/frontend/partials/wallet_modal.blade.php ENDPATH**/ ?>