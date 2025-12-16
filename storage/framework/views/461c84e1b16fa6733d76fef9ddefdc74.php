<!-- New Address Modal -->
<div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(translate('New Address')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-default" role="form" action="<?php echo e(route('addresses.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body c-scrollbar-light">
                    <div class="p-3">
                        <!-- Address -->
                        <div class="row">
                            <div class="col-md-2">
                                <label><?php echo e(translate('Address')); ?></label>
                            </div>
                            <div class="col-md-10">
                                <textarea class="form-control mb-3 rounded-0" placeholder="<?php echo e(translate('Your Address')); ?>" rows="2" name="address" required></textarea>
                            </div>
                        </div>

                        <?php if(get_active_countries()->count() > 1): ?>
                        <!-- Country -->
                        <div class="row">
                            <div class="col-md-2">
                                <label><?php echo e(translate('Country')); ?></label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control aiz-selectpicker rounded-0" data-live-search="true" data-placeholder="<?php echo e(translate('Select your country')); ?>" name="country_id" required>
                                        <option value=""><?php echo e(translate('Select your country')); ?></option>
                                        <?php $__currentLoopData = get_active_countries(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country->id); ?>"><?php echo e($country->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php elseif(get_active_countries()->count() == 1): ?>
                        <input type="hidden" name="country_id" value="<?php echo e(get_active_countries()[0]->id); ?>">
                        <?php endif; ?>
                        <?php if(get_setting('has_state') == 1): ?>
                        <!-- State -->
                        <div class="row">
                            <div class="col-md-2">
                                <label><?php echo e(translate('State')); ?></label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="state_id" required>

                                </select>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- City -->
                        <div class="row ">
                            <div class="col-md-2">
                                <label><?php echo e(translate('City')); ?></label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="city_id" required>

                                </select>
                            </div>
                        </div>

                         <!--Area-->
                        <div class="row area-field d-none">
                            <div class="col-md-2">
                                <label><?php echo e(translate('Area')); ?></label>
                            </div>
                            <div class="col-md-10">
                                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="area_id">

                                </select>
                            </div>
                        </div>

                        <?php if(get_setting('google_map') == 1): ?>
                            <!-- Google Map -->
                            <div class="row mt-3 mb-3">
                                <input id="searchInput" class="controls" type="text" placeholder="<?php echo e(translate('Enter a location')); ?>">
                                <div id="map"></div>
                                <ul id="geoData">
                                    <li style="display: none;">Full Address: <span id="location"></span></li>
                                    <li style="display: none;">Postal Code: <span id="postal_code"></span></li>
                                    <li style="display: none;">Country: <span id="country"></span></li>
                                    <li style="display: none;">Latitude: <span id="lat"></span></li>
                                    <li style="display: none;">Longitude: <span id="lon"></span></li>
                                </ul>
                            </div>
                            <!-- Longitude -->
                            <div class="row">
                                <div class="col-md-2" id="">
                                    <label for="exampleInputuname"><?php echo e(translate('Longitude')); ?></label>
                                </div>
                                <div class="col-md-10" id="">
                                    <input type="text" class="form-control mb-3 rounded-0" id="longitude" name="longitude" readonly="">
                                </div>
                            </div>
                            <!-- Latitude -->
                            <div class="row">
                                <div class="col-md-2" id="">
                                    <label for="exampleInputuname"><?php echo e(translate('Latitude')); ?></label>
                                </div>
                                <div class="col-md-10" id="">
                                    <input type="text" class="form-control mb-3 rounded-0" id="latitude" name="latitude" readonly="">
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Postal code -->
                        <div class="row">
                            <div class="col-md-2">
                                <label><?php echo e(translate('Postal code')); ?></label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control mb-3 rounded-0" placeholder="<?php echo e(translate('Your Postal Code')); ?>" name="postal_code" value="" required>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label><?php echo e(translate('Phone')); ?></label>
                            </div>
                            <div class="col-md-10">
                                <input type="tel" id="phone-code" class="form-control rounded-0" placeholder="" name="phone" autocomplete="off" required>
                                <input type="hidden" name="country_code" value="">
                            </div>
                        </div>

                        <!-- Save button -->
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary rounded-0 w-150px"><?php echo e(translate('Save')); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="edit-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(translate('Edit Address')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body c-scrollbar-light" id="edit_modal_body">

            </div>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\mafra_ind\resources\views/frontend/partials/address/address_modal.blade.php ENDPATH**/ ?>