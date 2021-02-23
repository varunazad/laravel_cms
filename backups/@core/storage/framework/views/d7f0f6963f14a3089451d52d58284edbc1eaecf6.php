<div class="header-style-01  header-variant-<?php echo e(get_static_option('home_page_variant')); ?>">
    <nav class="navbar navbar-area nav-absolute navbar-expand-lg nav-style-01">
        <div class="container nav-container">
            <div class="responsive-mobile-menu">
                <div class="logo-wrapper">
                    <a href="<?php echo e(url('/')); ?>" class="logo">
                        <?php if(!empty(get_static_option('site_white_logo'))): ?>
                            <?php echo render_image_markup_by_attachment_id(get_static_option('site_white_logo')); ?>

                        <?php else: ?>
                            <h2 class="site-title"><?php echo e(get_static_option('site_'.$user_select_lang_slug.'_title')); ?></h2>
                        <?php endif; ?>
                    </a>
                </div>
                <?php if(!empty(get_static_option('product_module_status'))): ?>
                    <div class="mobile-cart"><a href="<?php echo e(route('frontend.products.cart')); ?>"><i class="flaticon-shopping-cart"></i> <span class="pcount"><?php echo e(cart_total_items()); ?></span></a></div>
                <?php endif; ?>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bizcoxx_main_menu"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bizcoxx_main_menu">
                <ul class="navbar-nav">
                    <?php echo render_menu_by_id($primary_menu); ?>

                </ul>
            </div>
            <div class="nav-right-content">
                <div class="icon-part">
                    <ul>
                        <li id="search"><a href="#"><i class="flaticon-search-1"></i></a></li>
                        <?php if(!empty(get_static_option('product_module_status'))): ?>
                        <li class="cart"><a href="<?php echo e(route('frontend.products.cart')); ?>"><i class="flaticon-shopping-cart"></i> <span class="pcount"><?php echo e(cart_total_items()); ?></span></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>
<?php /**PATH /Users/sharifur/Desktop/sharifur-backup/localhost/nexelit/@core/resources/views/frontend/partials/navbar.blade.php ENDPATH**/ ?>