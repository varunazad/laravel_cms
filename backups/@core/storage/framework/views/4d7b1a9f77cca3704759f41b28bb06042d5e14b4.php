<?php $__env->startSection('page-meta-data'); ?>
<meta name="description" content="<?php echo e($page_post->meta_description); ?>">
<meta name="tags" content="<?php echo e($page_post->meta_tags); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('site-title'); ?>
    <?php echo e($page_post->title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-title'); ?>
    <?php echo e($page_post->title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="dynamic-page-content-area padding-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dynamic-page-content-wrap">
                        <?php echo $page_post->content; ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.frontend-page-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/xgenxchi/public_html/laravel/nexelit/beta/@core/resources/views/frontend/pages/dynamic-single.blade.php ENDPATH**/ ?>