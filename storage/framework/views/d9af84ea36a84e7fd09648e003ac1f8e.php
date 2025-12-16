<div id="bulk-delete-modal" class="modal fade">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h6"><?php echo e(translate('Delete Confirmation')); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mt-1"><?php echo e(translate('Are you sure to delete those?')); ?></p>
                <button type="button" class="btn btn-link mt-2" data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
                <a href="javascript:void(0)" onclick="bulk_delete()" class="btn btn-primary mt-2"><?php echo e(translate('Delete')); ?></a>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\mafra_ind\resources\views/modals/bulk_delete_modal.blade.php ENDPATH**/ ?>