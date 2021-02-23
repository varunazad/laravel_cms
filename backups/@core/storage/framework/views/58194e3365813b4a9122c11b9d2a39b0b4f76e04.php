<?php $__env->startSection('style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/backend/css/colorpicker.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/backend/css/dropzone.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/backend/css/media-uploader.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('Basic Settings')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <?php echo $__env->make('backend.partials.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title"><?php echo e(__("Basic Settings")); ?></h4>
                        <form action="<?php echo e(route('admin.general.basic.settings')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <?php $__currentLoopData = $all_languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a class="nav-item nav-link <?php if($key == 0): ?> active <?php endif; ?>" id="nav-home-tab" data-toggle="tab" href="#nav-home-<?php echo e($lang->slug); ?>" role="tab" aria-controls="nav-home" aria-selected="true"><?php echo e($lang->name); ?></a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </nav>
                            <div class="tab-content margin-top-30" id="nav-tabContent">
                                <?php $__currentLoopData = $all_languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="tab-pane fade <?php if($key == 0): ?> show active <?php endif; ?>" id="nav-home-<?php echo e($lang->slug); ?>" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <div class="form-group">
                                            <label for="site_<?php echo e($lang->slug); ?>_title"><?php echo e(__('Site Title')); ?></label>
                                            <input type="text" name="site_<?php echo e($lang->slug); ?>_title"  class="form-control" value="<?php echo e(get_static_option('site_'.$lang->slug.'_title')); ?>" id="site_<?php echo e($lang->slug); ?>_title">
                                        </div>
                                        <div class="form-group">
                                            <label for="site_<?php echo e($lang->slug); ?>_tag_line"><?php echo e(__('Site Tag Line')); ?></label>
                                            <input type="text" name="site_<?php echo e($lang->slug); ?>_tag_line"  class="form-control" value="<?php echo e(get_static_option('site_'.$lang->slug.'_tag_line')); ?>" id="site_<?php echo e($lang->slug); ?>_tag_line">
                                        </div>
                                        <div class="form-group">
                                            <label for="site_<?php echo e($lang->slug); ?>_footer_copyright"><?php echo e(__('Footer Copyright')); ?></label>
                                            <input type="text" name="site_<?php echo e($lang->slug); ?>_footer_copyright"  class="form-control" value="<?php echo e(get_static_option('site_'.$lang->slug.'_footer_copyright')); ?>" id="site_<?php echo e($lang->slug); ?>_footer_copyright">
                                            <small class="form-text text-muted">{copy} <?php echo e(__('Will replace by &copy; and {year} will be replaced by current year.')); ?></small>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="form-group">
                                <label for="og_meta_image_for_site"><strong><?php echo e(__('Og Meta Image For Site')); ?></strong></label>
                                <div class="media-upload-btn-wrapper">
                                    <div class="img-wrap">
                                        <?php
                                            $blog_img = get_attachment_image_by_id(get_static_option('og_meta_image_for_site'),null,true);
                                            $blog_image_btn_label = 'Upload Image';
                                        ?>
                                        <?php if(!empty($blog_img)): ?>
                                            <div class="attachment-preview">
                                                <div class="thumbnail">
                                                    <div class="centered">
                                                        <img class="avatar user-thumb" src="<?php echo e($blog_img['img_url']); ?>" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                            <?php  $blog_image_btn_label = 'Change Image'; ?>
                                        <?php endif; ?>
                                    </div>
                                    <input type="hidden" id="og_meta_image_for_site" name="og_meta_image_for_site" value="<?php echo e(get_static_option('og_meta_image_for_site')); ?>">
                                    <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="Select Image" data-modaltitle="Upload Image" data-toggle="modal" data-target="#media_upload_modal">
                                        <?php echo e(__($blog_image_btn_label)); ?>

                                    </button>
                                </div>
                                <small class="form-text text-muted"><?php echo e(__('allowed image format: jpg,jpeg,png. Recommended image size 1200x900')); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="site_sticky_navbar_enabled"><strong><?php echo e(__('Sticky Navbar Enable/Disable')); ?></strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="site_sticky_navbar_enabled"  <?php if(!empty(get_static_option('site_sticky_navbar_enabled'))): ?> checked <?php endif; ?> id="site_sticky_navbar_enabled">
                                    <span class="slider onff"></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="site_admin_dark_mode"><strong><?php echo e(__('Dark Mode For Admin Dashboard')); ?></strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="site_admin_dark_mode"  <?php if(!empty(get_static_option('site_admin_dark_mode'))): ?> checked <?php endif; ?> id="site_admin_dark_mode">
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="site_maintenance_mode"><strong><?php echo e(__('Maintenance Mode')); ?></strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="site_maintenance_mode"  <?php if(!empty(get_static_option('site_maintenance_mode'))): ?> checked <?php endif; ?> id="site_maintenance_mode">
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="language_select_option"><strong><?php echo e(__('Language Select Show/Hide')); ?></strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="language_select_option"  <?php if(!empty(get_static_option('language_select_option'))): ?> checked <?php endif; ?> id="language_select_option">
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="site_admin_panel_nav_sticky"><strong><?php echo e(__('Enable/Disable Admin Panel Nav Sticky')); ?></strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="site_admin_panel_nav_sticky"  <?php if(!empty(get_static_option('site_admin_panel_nav_sticky'))): ?> checked <?php endif; ?> >
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="site_payment_gateway"><strong><?php echo e(__('Enable/Disable Payment Gateway')); ?></strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="site_payment_gateway"  <?php if(!empty(get_static_option('site_payment_gateway'))): ?> checked <?php endif; ?> id="site_payment_gateway">
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="disable_backend_preloader"><strong><?php echo e(__('Enable/Disable Backend Preloader')); ?></strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="disable_backend_preloader"  <?php if(!empty(get_static_option('disable_backend_preloader'))): ?> checked <?php endif; ?> id="disable_backend_preloader">
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="site_force_ssl_redirection"><strong><?php echo e(__('Enable/Disable Force SSL Redirection')); ?></strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="site_force_ssl_redirection"  <?php if(!empty(get_static_option('site_force_ssl_redirection'))): ?> checked <?php endif; ?>>
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="disable_user_email_verify"><strong><?php echo e(__('Disable User Email Verify')); ?></strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="disable_user_email_verify"  <?php if(!empty(get_static_option('disable_user_email_verify'))): ?> checked <?php endif; ?> id="disable_user_email_verify">
                                    <span class="slider onff"></span>
                                </label>
                                <small class="info-text"><?php echo e(__('No, means user must have to verify their email account in order access his/her dashboard.')); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="site_color"><?php echo e(__('Site Main Color Settings')); ?></label>
                                <input type="text" name="site_color" style="background-color: <?php echo e(get_static_option('site_color')); ?>;color: #fff;" class="form-control" value="<?php echo e(get_static_option('site_color')); ?>" id="site_color">
                                <small><?php echo e(__('you change site main color from here, it will replace website main color')); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="site_color"><?php echo e(__('Site Base Color Two Settings')); ?></label>
                                <input type="text" name="site_main_color_two" style="background-color: <?php echo e(get_static_option('site_main_color_two')); ?>;color: #fff;" class="form-control" value="<?php echo e(get_static_option('site_main_color_two')); ?>" id="site_main_color_two">
                                <small><?php echo e(__('you change site base color two color from here, it will replace website site base color two color')); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="site_secondary_color"><?php echo e(__('Site Secondary Color Settings')); ?></label>
                                <input type="text" name="site_secondary_color" style="background-color: <?php echo e(get_static_option('site_secondary_color')); ?>;color: #fff;" class="form-control" value="<?php echo e(get_static_option('site_secondary_color')); ?>" id="site_secondary_color">
                                <small><?php echo e(__('you change site secondary color from here, it will replace website secondary color')); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="site_heading_color"><?php echo e(__('Site Heading Color')); ?></label>
                                <input type="text" name="site_heading_color" style="background-color: <?php echo e(get_static_option('site_heading_color')); ?>;color: #fff;" class="form-control" value="<?php echo e(get_static_option('site_heading_color')); ?>" id="site_heading_color">
                                <small><?php echo e(__('you can change site heading color from there , when you chnage this color it will reflect the color in all the heading like (h1,h2,h3,h4.h5.h6)')); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="site_paragraph_color"><?php echo e(__('Site Paragraph Color')); ?></label>
                                <input type="text" name="site_paragraph_color" style="background-color: <?php echo e(get_static_option('site_paragraph_color')); ?>;color: #fff;" class="form-control" value="<?php echo e(get_static_option('site_paragraph_color')); ?>" id="site_paragraph_color">
                                <small><?php echo e(__('you can change site paragraph color from there')); ?></small>
                            </div>
                            <div class="form-group">
                                <label for="portfolio_home_color"><?php echo e(__('Portfolio Home Color')); ?></label>
                                <input type="text" name="portfolio_home_color" style="background-color: <?php echo e(get_static_option('portfolio_home_color')); ?>;color: #fff;" class="form-control" value="<?php echo e(get_static_option('portfolio_home_color')); ?>" >
                            </div>
                            <div class="form-group">
                                <label for="logistics_home_color"><?php echo e(__('Logistics Home Color')); ?></label>
                                <input type="text" name="logistics_home_color" style="background-color: <?php echo e(get_static_option('logistics_home_color')); ?>;color: #fff;" class="form-control" value="<?php echo e(get_static_option('logistics_home_color')); ?>" >
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4"><?php echo e(__('Update Changes')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('backend.partials.media-upload.media-upload-markup', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/backend/js/colorpicker.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/backend/js/dropzone.js')); ?>"></script>
    <?php echo $__env->make('backend.partials.media-upload.media-js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){

                initColorPicker('#site_color');
                initColorPicker('#site_secondary_color');
                initColorPicker('#site_main_color_two');
                initColorPicker('#site_heading_color');
                initColorPicker('#site_paragraph_color');
                initColorPicker('input[name="portfolio_home_color"');
                initColorPicker('input[name="logistics_home_color"');

                function initColorPicker(selector){
                    $(selector).ColorPicker({
                        color: '#852aff',
                        onShow: function (colpkr) {
                            $(colpkr).fadeIn(500);
                            return false;
                        },
                        onHide: function (colpkr) {
                            $(colpkr).fadeOut(500);
                            return false;
                        },
                        onChange: function (hsb, hex, rgb) {
                            $(selector).css('background-color', '#' + hex);
                            $(selector).val('#' + hex);
                        }
                    });
                }
            });
        }(jQuery));
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/sharifur/Desktop/sharifur-backup/localhost/nexelit/@core/resources/views/backend/general-settings/basic.blade.php ENDPATH**/ ?>