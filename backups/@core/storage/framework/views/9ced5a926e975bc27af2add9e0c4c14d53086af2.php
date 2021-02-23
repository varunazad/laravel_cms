<!DOCTYPE html>
<html lang="<?php echo e($user_select_lang_slug); ?>"  dir="<?php echo e(get_user_lang_direction()); ?>">
<head>
    <?php if(!empty(get_static_option('site_google_analytics'))): ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e(get_static_option('site_google_analytics')); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', "<?php echo e(get_static_option('site_google_analytics')); ?>");
    </script>
    <?php endif; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="<?php echo e(get_static_option('site_meta_'.$user_select_lang_slug.'_description')); ?>">
    <meta name="tags" content="<?php echo e(get_static_option('site_meta_'.$user_select_lang_slug.'_tags')); ?>">
    <?php echo render_favicon_by_id(get_static_option('site_favicon')); ?>

    <!-- load fonts dynamically -->
    <?php echo load_google_fonts(); ?>



    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/fontawesome.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/owl.carousel.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/animate.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/flaticon.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/magnific-popup.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/responsive.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/jquery.ihavecookies.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/dynamic-style.css')); ?>">

    <?php if(request()->path() == '/'): ?>
        <meta property="og:title"  content="<?php echo e(get_static_option('site_'.$user_select_lang_slug.'_title')); ?>" />
        <?php echo render_og_meta_image_by_attachment_id(get_static_option('og_meta_image_for_site')); ?>

    <?php endif; ?>

    <style>
        :root {
            --main-color-one: <?php echo e(get_static_option('site_color')); ?>;
            --main-color-two: <?php echo e(get_static_option('site_main_color_two')); ?>;
            --portfolio-color: <?php echo e(get_static_option('portfolio_home_color')); ?>;
            --logistic-color: <?php echo e(get_static_option('logistics_home_color')); ?>;
            --secondary-color: <?php echo e(get_static_option('site_secondary_color')); ?>;
            --heading-color: <?php echo e(get_static_option('site_heading_color')); ?>;
            --paragraph-color: <?php echo e(get_static_option('site_paragraph_color')); ?>;
            <?php $heading_font_family = !empty(get_static_option('heading_font')) ? get_static_option('heading_font_family') :  get_static_option('body_font_family') ?>
            --heading-font: "<?php echo e($heading_font_family); ?>",sans-serif;
            --body-font:"<?php echo e(get_static_option('body_font_family')); ?>",sans-serif;
        }
    </style>
    <?php echo $__env->yieldContent('style'); ?>
    <?php if(!empty(get_static_option('site_rtl_enabled')) || get_user_lang_direction() == 'rtl'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/rtl.css')); ?>">
    <?php endif; ?>
    <?php if(request()->is(get_static_option('blog_page_slug').'/*') || request()->is(get_static_option('work_page_slug').'/*') || request()->is(get_static_option('service_page_slug').'/*')): ?>
    <?php echo $__env->yieldContent('og-meta'); ?>
    <title><?php echo $__env->yieldContent('site-title'); ?></title>
    <?php elseif(request()->is(get_static_option('about_page_slug')) || request()->is(get_static_option('service_page_slug')) || request()->is(get_static_option('work_page_slug')) || request()->is(get_static_option('team_page_slug')) || request()->is(get_static_option('faq_page_slug')) || request()->is(get_static_option('blog_page_slug')) || request()->is(get_static_option('contact_page_slug')) || request()->is('p/*') || request()->is(get_static_option('blog_page_slug').'/*') || request()->is(get_static_option('service_page_slug').'/*') || request()->is(get_static_option('career_with_us_page_slug').'/*') || request()->is(get_static_option('events_page_slug').'/*') || request()->is(get_static_option('knowledgebase_page_slug').'/*')): ?>
    <title><?php echo $__env->yieldContent('site-title'); ?> - <?php echo e(get_static_option('site_'.$user_select_lang_slug.'_title')); ?> </title>
    <?php else: ?>
    <title><?php echo e(get_static_option('site_'.$user_select_lang_slug.'_title')); ?> - <?php echo e(get_static_option('site_'.$user_select_lang_slug.'_tag_line')); ?></title>
    <?php endif; ?>
    <!-- jquery -->
    <script src="<?php echo e(asset('assets/frontend/js/jquery-3.4.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/frontend/js/jquery-migrate-3.1.0.min.js')); ?>"></script>
    <script>var siteurl = "<?php echo e(url('/')); ?>"</script>
    <?php echo get_static_option('site_third_party_tracking_code'); ?>

</head>
<body class="nexelit_version_<?php echo e(getenv('XGENIOUS_NEXELIT_VERSION')); ?> <?php echo e(get_static_option('item_license_status')); ?> apps_key_<?php echo e(get_static_option('site_script_unique_key')); ?> ">
<?php echo $__env->make('frontend.partials.preloader', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('frontend.partials.search-popup', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('frontend.partials.supportbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/xgenxchi/public_html/laravel/nexelit/beta/@core/resources/views/frontend/partials/header.blade.php ENDPATH**/ ?>