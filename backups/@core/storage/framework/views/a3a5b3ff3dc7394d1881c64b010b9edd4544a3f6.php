<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo" style="max-height: 50px;">
            <a href="<?php echo e(route('admin.home')); ?>">
                <?php
                    $logo_type = 'site_logo';
                    if(!empty(get_static_option('site_admin_dark_mode'))){
                        $logo_type = 'site_white_logo';
                    }
                ?>
                <?php echo render_image_markup_by_attachment_id(get_static_option($logo_type)); ?>

            </a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav id="main_menu_wrap">
                <ul class="metismenu" id="menu">
                    <li class="<?php echo e(active_menu('admin-home')); ?>">
                        <a href="<?php echo e(route('admin.home')); ?>"
                           aria-expanded="true">
                            <i class="ti-dashboard"></i>
                            <span><?php echo app('translator')->get('dashboard'); ?></span>
                        </a>
                    </li>
                    <?php if(check_page_permission('admin_manage')): ?>
                    <li
                        class="main_dropdown <?php echo e(active_menu('admin-home/new-user')); ?>

                        <?php echo e(active_menu('admin-home/all-user')); ?>

                        <?php echo e(active_menu('admin-home/all-user/role')); ?>

                        "
                    >
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-user"></i>
                            <span><?php echo e(__('Admin Manage')); ?></span></a>
                        <ul class="collapse">
                            <li class="<?php echo e(active_menu('admin-home/all-user')); ?>"><a
                                        href="<?php echo e(route('admin.all.user')); ?>"><?php echo e(__('All Admin')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/new-user')); ?>"><a
                                        href="<?php echo e(route('admin.new.user')); ?>"><?php echo e(__('Add New Admin')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/all-user/role')); ?>"><a
                                        href="<?php echo e(route('admin.all.user.role')); ?>"><?php echo e(__('All Admin Role')); ?></a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(check_page_permission_by_string('Users Manage')): ?>
                    <li
                        class="main_dropdown <?php echo e(active_menu('admin-home/frontend/new-user')); ?>

                        <?php echo e(active_menu('admin-home/frontend/all-user')); ?>

                        <?php echo e(active_menu('admin-home/frontend/all-user/role')); ?>

                        "
                    >
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-user"></i>
                            <span><?php echo e(__('Users Manage')); ?></span></a>
                        <ul class="collapse">
                            <li class="<?php echo e(active_menu('admin-home/frontend/all-user')); ?>"><a
                                    href="<?php echo e(route('admin.all.frontend.user')); ?>"><?php echo e(__('All Users')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/frontend/new-user')); ?>"><a
                                    href="<?php echo e(route('admin.frontend.new.user')); ?>"><?php echo e(__('Add New User')); ?></a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(check_page_permission_by_string('Newsletter Manage')): ?>
                    <li
                        class="main_dropdown <?php echo e(active_menu('admin-home/newsletter')); ?> <?php if(request()->is('admin-home/newsletter/*')): ?> active <?php endif; ?>
                     ">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-email"></i>
                            <span><?php echo e(__('Newsletter Manage')); ?></span></a>
                        <ul class="collapse">
                            <li class="<?php echo e(active_menu('admin-home/newsletter')); ?>"><a
                                        href="<?php echo e(route('admin.newsletter')); ?>"><?php echo e(__('All Subscriber')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/newsletter/all')); ?>"><a
                                        href="<?php echo e(route('admin.newsletter.mail')); ?>"><?php echo e(__('Send Mail To All')); ?></a></li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if(check_page_permission_by_string('Pages Manage')): ?>
                        <li
                        class="main_dropdown
                        <?php echo e(active_menu('admin-home/page')); ?>

                        <?php echo e(active_menu('admin-home/new-page')); ?>

                        <?php if(request()->is('admin-home/page-edit/*')): ?> active <?php endif; ?>
                        ">
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-write"></i>
                                <span><?php echo e(__('Pages')); ?></span></a>
                            <ul class="collapse">
                                <li class="<?php echo e(active_menu('admin-home/page')); ?>"><a
                                            href="<?php echo e(route('admin.page')); ?>"><?php echo e(__('All Pages')); ?></a></li>
                                <li class="<?php echo e(active_menu('admin-home/new-page')); ?>"><a
                                            href="<?php echo e(route('admin.page.new')); ?>"><?php echo e(__('Add New Page')); ?></a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if(check_page_permission_by_string('Blogs Manage')): ?>
                        <li
                         class="main_dropdown
                        <?php echo e(active_menu('admin-home/blog')); ?>

                        <?php if(request()->is('admin-home/blog/*')): ?> active <?php endif; ?>
                        "
                        >
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-write"></i>
                                <span><?php echo e(__('Blogs')); ?></span></a>
                            <ul class="collapse">
                                <li class="<?php echo e(active_menu('admin-home/blog')); ?>"><a
                                            href="<?php echo e(route('admin.blog')); ?>"><?php echo e(__('All Blog')); ?></a></li>
                                <li class="<?php echo e(active_menu('admin-home/blog/category')); ?>"><a
                                            href="<?php echo e(route('admin.blog.category')); ?>"><?php echo e(__('Category')); ?></a></li>
                                <li class="<?php echo e(active_menu('admin-home/new-blog')); ?>"><a
                                            href="<?php echo e(route('admin.blog.new')); ?>"><?php echo e(__('Add New Post')); ?></a></li>
                                <li class="<?php echo e(active_menu('admin-home/blog/page-settings')); ?>"><a
                                        href="<?php echo e(route('admin.blog.page.settings')); ?>"><?php echo e(__('Blog Page Settings')); ?></a></li>
                                <li class="<?php echo e(active_menu('admin-home/blog/single-settings')); ?>"><a
                                        href="<?php echo e(route('admin.blog.single.settings')); ?>"><?php echo e(__('Blog Single Settings')); ?></a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if(check_page_permission_by_string('Services')): ?>
                    <li class="main_dropdown
                    <?php if(request()->is('admin-home/services/*')): ?> active <?php endif; ?>
                    <?php echo e(active_menu('admin-home/services')); ?>

                    ">
                        <a href="javascript:void(0)"
                           aria-expanded="true">
                            <i class="ti-layout"></i>
                            <span><?php echo e(__('Services')); ?></span>
                        </a>
                        <ul class="collapse">
                            <li class="<?php echo e(active_menu('admin-home/services')); ?>"><a
                                    href="<?php echo e(route('admin.services')); ?>"><?php echo e(__('All Services')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/services/new')); ?>"><a
                                    href="<?php echo e(route('admin.services.new')); ?>"><?php echo e(__('New Service')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/services/category')); ?>"><a
                                    href="<?php echo e(route('admin.service.category')); ?>"><?php echo e(__('Category')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/services/page-settings')); ?>"><a
                                    href="<?php echo e(route('admin.services.page.settings')); ?>"><?php echo e(__('Service Page')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/services/single-page-settings')); ?>"><a
                                    href="<?php echo e(route('admin.services.single.page.settings')); ?>"><?php echo e(__('Service Single Page')); ?></a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(check_page_permission_by_string('Case Study')): ?>
                    <li class="main_dropdown
                    <?php if(request()->is('admin-home/works/*')): ?> active <?php endif; ?>
                    <?php echo e(active_menu('admin-home/works')); ?>

                            ">
                        <a href="javascript:void(0)"
                           aria-expanded="true">
                            <i class="ti-layout"></i>
                            <span><?php echo e(__('Case Study')); ?></span>
                        </a>
                        <ul class="collapse">
                            <li class="<?php echo e(active_menu('admin-home/works')); ?>"><a
                                        href="<?php echo e(route('admin.work')); ?>"><?php echo e(__('All Case Study')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/works/new')); ?>"><a
                                    href="<?php echo e(route('admin.work.new')); ?>"><?php echo e(__('New Case Study')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/works/category')); ?>"><a
                                        href="<?php echo e(route('admin.work.category')); ?>"><?php echo e(__('Category')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/works/single-page/settings')); ?>"><a
                                    href="<?php echo e(route('admin.work.single.page.settings')); ?>"><?php echo e(__('Case Single Page Settings')); ?></a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(check_page_permission_by_string('Gallery Page')): ?>
                        <li class="main_dropdown
                        <?php echo e(active_menu('admin-home/gallery-page')); ?>

                        <?php if(request()->is('admin-home/gallery-page/*')): ?> active <?php endif; ?>
                                ">
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-write"></i>
                                <span><?php echo e(__('Image Gallery')); ?></span></a>
                            <ul class="collapse">
                                <li class="<?php echo e(active_menu('admin-home/gallery-page')); ?>">
                                    <a href="<?php echo e(route('admin.gallery.all')); ?>" ><?php echo e(__('Image Gallery')); ?></a>
                                </li>
                                <li class="<?php echo e(active_menu('admin-home/gallery-page/category')); ?>">
                                    <a href="<?php echo e(route('admin.gallery.category')); ?>" ><?php echo e(__('Category')); ?></a>
                                </li>
                                <li class="<?php echo e(active_menu('admin-home/gallery-page/page-settings')); ?>">
                                    <a href="<?php echo e(route('admin.gallery.page.settings')); ?>" ><?php echo e(__('Page Settings')); ?></a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if(check_page_permission_by_string('Price Plan')): ?>
                        <li class="main_dropdown <?php echo e(active_menu('admin-home/price-plan')); ?>

                        <?php if(request()->is('admin-home/price-plan/*')): ?> active <?php endif; ?>
                                ">
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-write"></i>
                                <span><?php echo e(__('Price Plan')); ?></span></a>
                            <ul class="collapse">
                                <li class="<?php echo e(active_menu('admin-home/price-plan')); ?>">
                                    <a href="<?php echo e(route('admin.price.plan')); ?>" ><?php echo e(__('All Price Plan')); ?></a>
                                </li>
                                <li class="<?php echo e(active_menu('admin-home/price-plan/new')); ?>">
                                    <a href="<?php echo e(route('admin.price.plan.new')); ?>" ><?php echo e(__('New Price Plan')); ?></a>
                                </li>
                                <li class="<?php echo e(active_menu('admin-home/price-plan/category')); ?>">
                                    <a href="<?php echo e(route('admin.price.plan.category')); ?>" ><?php echo e(__('Category')); ?></a>
                                </li>

                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if(check_page_permission_by_string('Faq')): ?>
                    <li class="main_dropdown <?php echo e(active_menu('admin-home/faq')); ?>">
                        <a href="<?php echo e(route('admin.faq')); ?>" aria-expanded="true"><i class="ti-control-forward"></i>
                            <span><?php echo e(__('Faq')); ?></span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(check_page_permission_by_string('Brand Logos')): ?>
                    <li class="main_dropdown <?php echo e(active_menu('admin-home/brands')); ?>">
                        <a href="<?php echo e(route('admin.brands')); ?>" aria-expanded="true"><i class="ti-control-forward"></i>
                            <span><?php echo e(__('Brand Logos')); ?></span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(check_page_permission_by_string('Team Members')): ?>
                    <li class="main_dropdown <?php echo e(active_menu('admin-home/team-member')); ?>">
                        <a href="<?php echo e(route('admin.team.member')); ?>" aria-expanded="true"><i class="ti-control-forward"></i>
                            <span><?php echo e(__('Team Members')); ?></span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(check_page_permission_by_string('Testimonial')): ?>
                    <li class="main_dropdown <?php echo e(active_menu('admin-home/testimonial')); ?>">
                        <a href="<?php echo e(route('admin.testimonial')); ?>" aria-expanded="true"><i class="ti-control-forward"></i>
                            <span><?php echo e(__('Testimonial')); ?></span></a>
                    </li>
                    <?php endif; ?>
                    <?php if(check_page_permission_by_string('Counterup')): ?>
                    <li class="main_dropdown <?php echo e(active_menu('admin-home/counterup')); ?>">
                        <a href="<?php echo e(route('admin.counterup')); ?>" aria-expanded="true"><i class="ti-exchange-vertical"></i>
                            <span><?php echo e(__('Counterup')); ?></span></a>
                    </li>
                    <?php endif; ?>
                    <li class="main_dropdown
                    <?php if(request()->is('admin-home/quote-manage/*')): ?> active <?php endif; ?>
                    <?php if(request()->is('admin-home/package/*')): ?> active <?php endif; ?>
                    <?php echo e(active_menu('admin-home/payment-logs')); ?>

                    <?php echo e(active_menu('admin-home/payment-logs/report')); ?>

                    <?php echo e(active_menu('admin-home/jobs')); ?>

                    <?php if(request()->is('admin-home/jobs/*')): ?> active <?php endif; ?>
                    <?php echo e(active_menu('admin-home/events')); ?>

                    <?php if(request()->is('admin-home/events/*')): ?> active <?php endif; ?>
                    <?php echo e(active_menu('admin-home/products')); ?>

                    <?php if(request()->is('admin-home/products/*')): ?> active <?php endif; ?>
                    <?php echo e(active_menu('admin-home/donations')); ?>

                    <?php if(request()->is('admin-home/donations/*')): ?> active <?php endif; ?>
                    <?php echo e(active_menu('admin-home/knowledge')); ?> <?php if(request()->is('admin-home/knowledge/*')): ?> active <?php endif; ?>
                        ">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-settings"></i>
                            <span><?php echo e(__('All Modules')); ?></span></a>
                        <ul class="collapse ">
                            <?php if(check_page_permission_by_string('Quote Manage')): ?>
                                <li class="main_dropdown <?php if(request()->is('admin-home/quote-manage/*')): ?> active <?php endif; ?> ">
                                    <a href="javascript:void(0)" aria-expanded="true">
                                        <?php echo e(__('Quote Manage')); ?></a>
                                    <ul class="collapse">
                                        <li class="<?php echo e(active_menu('admin-home/quote-manage/all')); ?>"><a
                                                    href="<?php echo e(route('admin.quote.manage.all')); ?>"><?php echo e(__('All Quote')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/quote-manage/pending')); ?>"><a
                                                    href="<?php echo e(route('admin.quote.manage.pending')); ?>"><?php echo e(__('Pending Quote')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/quote-manage/completed')); ?>"><a
                                                    href="<?php echo e(route('admin.quote.manage.completed')); ?>"><?php echo e(__('Complete Quote')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/quote-manage/quote-page')); ?>">
                                            <a href="<?php echo e(route('admin.quote.page')); ?>">
                                                <?php echo e(__('Quote Page Manage')); ?>

                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if(check_page_permission_by_string('Package Orders Manage')): ?>
                                <li class="main_dropdown <?php if(request()->is('admin-home/package/*')): ?> active <?php endif; ?>
                                <?php echo e(active_menu('admin-home/payment-logs')); ?>

                                <?php echo e(active_menu('admin-home/payment-logs/report')); ?>

                                        ">
                                    <a href="javascript:void(0)" aria-expanded="true">
                                        <?php echo e(__('Package Orders Manage')); ?></a>
                                    <ul class="collapse">
                                        <li class="<?php echo e(active_menu('admin-home/package/order-manage/all')); ?>"><a
                                                    href="<?php echo e(route('admin.package.order.manage.all')); ?>"><?php echo e(__('All Order')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/package/order-manage/pending')); ?>"><a
                                                    href="<?php echo e(route('admin.package.order.manage.pending')); ?>"><?php echo e(__('Pending Order')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/package/order-manage/in-progress')); ?>"><a
                                                    href="<?php echo e(route('admin.package.order.manage.in.progress')); ?>"><?php echo e(__('In Progress Order')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/package/order-manage/completed')); ?>"><a
                                                    href="<?php echo e(route('admin.package.order.manage.completed')); ?>"><?php echo e(__('Completed Order')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/package/order-manage/success-page')); ?>"><a
                                                    href="<?php echo e(route('admin.package.order.success.page')); ?>"><?php echo e(__('Success Order Page')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/package/order-manage/cancel-page')); ?>"><a
                                                    href="<?php echo e(route('admin.package.order.cancel.page')); ?>"><?php echo e(__('Cancel Order Page')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/package/order-page')); ?>">
                                            <a href="<?php echo e(route('admin.package.order.page')); ?>"><?php echo e(__('Order Page Manage')); ?></a>
                                        </li>
                                        <li class="<?php echo e(active_menu('admin-home/package/order-report')); ?>">
                                            <a href="<?php echo e(route('admin.package.order.report')); ?>"><?php echo e(__('Order Report')); ?></a>
                                        </li>
                                        <li class="<?php echo e(active_menu('admin-home/payment-logs')); ?>"><a
                                                    href="<?php echo e(route('admin.payment.logs')); ?>"><?php echo e(__('All Payment Logs')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/payment-logs/report')); ?>"><a
                                                    href="<?php echo e(route('admin.payment.report')); ?>"><?php echo e(__('Payment Report')); ?></a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if(check_page_permission_by_string('Job Post Manage') && !empty(get_static_option('job_module_status'))): ?>
                                <li
                                    class="main_dropdown
                                    <?php echo e(active_menu('admin-home/jobs')); ?>

                                    <?php if(request()->is('admin-home/jobs/*')): ?> active <?php endif; ?>
                                    ">
                                    <a href="javascript:void(0)" aria-expanded="true">
                                        <?php echo e(__('Job Post Manage')); ?></a>
                                    <ul class="collapse">
                                        <li class="<?php echo e(active_menu('admin-home/jobs')); ?>"><a
                                                    href="<?php echo e(route('admin.jobs.all')); ?>"><?php echo e(__('All Jobs')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/jobs/category')); ?>"><a
                                                    href="<?php echo e(route('admin.jobs.category.all')); ?>"><?php echo e(__('Category')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/new-jobs')); ?>"><a
                                                    href="<?php echo e(route('admin.jobs.new')); ?>"><?php echo e(__('Add New Job')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/jobs/page-settings')); ?>"><a
                                                    href="<?php echo e(route('admin.jobs.page.settings')); ?>"><?php echo e(__('Job Page Settings')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/jobs/single-page-settings')); ?>"><a
                                                    href="<?php echo e(route('admin.jobs.single.page.settings')); ?>"><?php echo e(__('Job Single Page Settings')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/jobs/success-page-settings')); ?>">
                                            <a href="<?php echo e(route('admin.jobs.success.page.settings')); ?>"><?php echo e(__('Job Success Page Settings')); ?></a>
                                        </li>
                                        <li class="<?php echo e(active_menu('admin-home/jobs/cancel-page-settings')); ?>">
                                            <a href="<?php echo e(route('admin.jobs.cancel.page.settings')); ?>"><?php echo e(__('Job Cancel Page Settings')); ?></a>
                                        </li>
                                        <li class="<?php echo e(active_menu('admin-home/jobs/applicant')); ?>"><a
                                                    href="<?php echo e(route('admin.jobs.applicant')); ?>"><?php echo e(__('All Applicant')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/jobs/applicant/report')); ?>"><a
                                                    href="<?php echo e(route('admin.jobs.applicant.report')); ?>"><?php echo e(__('Applicant Report')); ?></a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if(check_page_permission_by_string('Events Manage') && !empty(get_static_option('events_module_status'))): ?>
                                    <li class="main_dropdown
                                    <?php echo e(active_menu('admin-home/events')); ?>

                                    <?php if(request()->is('admin-home/events/*')): ?> active <?php endif; ?>
                                            ">
                                        <a href="javascript:void(0)" aria-expanded="true">
                                            <?php echo e(__('Events Manage')); ?></a>
                                        <ul class="collapse">
                                            <li class="<?php echo e(active_menu('admin-home/events')); ?>"><a
                                                        href="<?php echo e(route('admin.events.all')); ?>"><?php echo e(__('All Events')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/events/category')); ?>"><a
                                                        href="<?php echo e(route('admin.events.category.all')); ?>"><?php echo e(__('Category')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/events/new')); ?>"><a
                                                        href="<?php echo e(route('admin.events.new')); ?>"><?php echo e(__('Add New Event')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/events/page-settings')); ?>"><a
                                                        href="<?php echo e(route('admin.events.page.settings')); ?>"><?php echo e(__('Event Page Settings')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/events/single-page-settings')); ?>"><a
                                                        href="<?php echo e(route('admin.events.single.page.settings')); ?>"><?php echo e(__('Event Single Settings')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/events/attendance')); ?>"><a
                                                        href="<?php echo e(route('admin.events.attendance')); ?>"><?php echo e(__('Event Attendance')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/events/event-attendance-logs')); ?>"><a
                                                        href="<?php echo e(route('admin.event.attendance.logs')); ?>"><?php echo e(__('Event Attendance Logs')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/events/event-payment-logs')); ?>"><a
                                                        href="<?php echo e(route('admin.event.payment.logs')); ?>"><?php echo e(__('Event Payment Logs')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/events/payment-success-page-settings')); ?>"><a
                                                        href="<?php echo e(route('admin.events.payment.success.page.settings')); ?>"><?php echo e(__('Payment Success Page Settings')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/events/payment-cancel-pag-settings')); ?>"><a
                                                        href="<?php echo e(route('admin.events.payment.cancel.page.settings')); ?>"><?php echo e(__('Payment Cancel Page Settings')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/events/attendance/report')); ?>"><a
                                                        href="<?php echo e(route('admin.event.attendance.report')); ?>"><?php echo e(__('Attendance Report')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/events/payment/report')); ?>"><a
                                                        href="<?php echo e(route('admin.event.payment.report')); ?>"><?php echo e(__('Payment Log Report')); ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                            <?php if(check_page_permission_by_string('Products Manage') && !empty(get_static_option('product_module_status'))): ?>
                                    <li class="main_dropdown
                                    <?php echo e(active_menu('admin-home/products')); ?>

                                    <?php if(request()->is('admin-home/products/*')): ?> active <?php endif; ?>
                                            ">
                                        <a href="javascript:void(0)" aria-expanded="true">
                                            <?php echo e(__('Products Manage')); ?></a>
                                        <ul class="collapse">
                                            <li class="<?php echo e(active_menu('admin-home/products')); ?>"><a
                                                        href="<?php echo e(route('admin.products.all')); ?>"><?php echo e(__('All Products')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/products/new')); ?>"><a
                                                        href="<?php echo e(route('admin.products.new')); ?>"><?php echo e(__('Add New Product')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/products/category')); ?>"><a
                                                        href="<?php echo e(route('admin.products.category.all')); ?>"><?php echo e(__('Category')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/products/shipping')); ?>"><a
                                                        href="<?php echo e(route('admin.products.shipping.all')); ?>"><?php echo e(__('Shipping')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/products/coupon')); ?>"><a
                                                        href="<?php echo e(route('admin.products.coupon.all')); ?>"><?php echo e(__('Coupon')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/products/page-settings')); ?>"><a
                                                        href="<?php echo e(route('admin.products.page.settings')); ?>"><?php echo e(__('Product Page Settings')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/products/single-page-settings')); ?>"><a
                                                        href="<?php echo e(route('admin.products.single.page.settings')); ?>"><?php echo e(__('Product Single Page Settings')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/products/success-page-settings')); ?>"><a
                                                        href="<?php echo e(route('admin.products.success.page.settings')); ?>"><?php echo e(__('Order Success Page Settings')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/products/cancel-page-settings')); ?>"><a
                                                        href="<?php echo e(route('admin.products.cancel.page.settings')); ?>"><?php echo e(__('Order Cancel Page Settings')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/products/product-order-logs')); ?>"><a
                                                        href="<?php echo e(route('admin.products.order.logs')); ?>"><?php echo e(__('Orders')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/products/product-ratings')); ?>"><a
                                                        href="<?php echo e(route('admin.products.ratings')); ?>"><?php echo e(__('Ratings')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/products/order-report')); ?>">
                                                <a href="<?php echo e(route('admin.products.order.report')); ?>"><?php echo e(__('Order Report')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/products/tax-settings')); ?>">
                                                <a href="<?php echo e(route('admin.products.tax.settings')); ?>"><?php echo e(__('Tax Settings')); ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                            <?php if(check_page_permission_by_string('Donations Manage') && !empty(get_static_option('donations_module_status'))): ?>
                                    <li class="main_dropdown
                                    <?php echo e(active_menu('admin-home/donations')); ?>

                                    <?php if(request()->is('admin-home/donations/*')): ?> active <?php endif; ?>
                                        ">
                                        <a href="javascript:void(0)" aria-expanded="true">
                                           <?php echo e(__('Donations Manage')); ?></a>
                                        <ul class="collapse">
                                            <li class="<?php echo e(active_menu('admin-home/donations')); ?>"><a
                                                        href="<?php echo e(route('admin.donations.all')); ?>"><?php echo e(__('All Donations')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/donations/new')); ?>"><a
                                                        href="<?php echo e(route('admin.donations.new')); ?>"><?php echo e(__('Add New Donation')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/donations/page-settings')); ?>"><a
                                                        href="<?php echo e(route('admin.donations.page.settings')); ?>"><?php echo e(__('Donation Page Settings')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/donations/single-page-settings')); ?>"><a
                                                        href="<?php echo e(route('admin.donations.single.page.settings')); ?>"><?php echo e(__('Donation Single Settings')); ?></a>
                                            </li>

                                            <li class="<?php echo e(active_menu('admin-home/donations/donations-payment-logs')); ?>"><a
                                                        href="<?php echo e(route('admin.donations.payment.logs')); ?>"><?php echo e(__('Donation Payment Logs')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/donations/payment-success-page-settings')); ?>"><a
                                                        href="<?php echo e(route('admin.donations.payment.success.page.settings')); ?>"><?php echo e(__('Payment Success Page Settings')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/donations/payment-cancel-pag-settings')); ?>"><a
                                                        href="<?php echo e(route('admin.donations.payment.cancel.page.settings')); ?>"><?php echo e(__('Payment Cancel Page Settings')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/donations/report')); ?>">
                                                <a href="<?php echo e(route('admin.donations.report')); ?>"><?php echo e(__('Donation Report')); ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                            <?php if(check_page_permission_by_string('Knowledgebase') && !empty(get_static_option('knowledgebase_module_status'))): ?>
                                <li class="main_dropdown <?php echo e(active_menu('admin-home/knowledge')); ?> <?php if(request()->is('admin-home/knowledge/*')): ?> active <?php endif; ?>"
                                >
                                    <a href="javascript:void(0)" aria-expanded="true">
                                        <?php echo e(__('Knowledgebase')); ?></a>
                                    <ul class="collapse">
                                        <li class="<?php echo e(active_menu('admin-home/knowledge')); ?>"><a
                                                    href="<?php echo e(route('admin.knowledge.all')); ?>"><?php echo e(__('All Articles')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/knowledge/category')); ?>"><a
                                                    href="<?php echo e(route('admin.knowledge.category.all')); ?>"><?php echo e(__('Topics')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/knowledge/new')); ?>"><a
                                                    href="<?php echo e(route('admin.knowledge.new')); ?>"><?php echo e(__('Add New Article')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/knowledge/page-settings')); ?>"><a
                                                    href="<?php echo e(route('admin.knowledge.page.settings')); ?>"><?php echo e(__('Knowledgebase Page Settings')); ?></a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="main_dropdown
                        <?php if(request()->is('admin-home/home-page-01/*')  ): ?> active <?php endif; ?>
                        <?php if(request()->is('admin-home/home-05/*')  ): ?> active <?php endif; ?>
                        <?php if(request()->is('admin-home/home-06/*')  ): ?> active <?php endif; ?>
                        <?php echo e(active_menu('admin-home/header')); ?>

                        <?php echo e(active_menu('admin-home/keyfeatures')); ?>

                        <?php if(request()->is('admin-home/about-page/*')  ): ?> active <?php endif; ?>
                        <?php if(request()->is('admin-home/contact-page/*')  ): ?> active <?php endif; ?>
                        <?php if(request()->is('admin-home/feedback-page/*')  ): ?> active <?php endif; ?>
                        <?php echo e(active_menu('admin-home/404-page-manage')); ?>

                        <?php echo e(active_menu('admin-home/maintains-page/settings')); ?>

                        ">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-settings"></i>
                            <span><?php echo e(__('All Page Settings')); ?></span></a>
                        <ul class="collapse ">
                            <?php if(check_page_permission_by_string('Home Page Manage')): ?>
                                <li class="main_dropdown
                                <?php if(request()->is('admin-home/home-page-01/*')  ): ?> active <?php endif; ?>
                                <?php if(request()->is('admin-home/home-05/*')  ): ?> active <?php endif; ?>
                                <?php if(request()->is('admin-home/home-06/*')  ): ?> active <?php endif; ?>
                                <?php echo e(active_menu('admin-home/header')); ?>

                                <?php echo e(active_menu('admin-home/keyfeatures')); ?>

                                ">
                                    <a href="javascript:void(0)"
                                       aria-expanded="true">
                                        <?php echo e(__('Home Page Manage')); ?>

                                    </a>
                                    <ul class="collapse">
                                        <?php if(get_static_option('home_page_variant') == '02'
                                        || get_static_option('home_page_variant') == '03'
                                        || get_static_option('home_page_variant') == '04'
                                        || get_static_option('home_page_variant') == '01'
                                        ): ?>
                                            <li class="<?php echo e(active_menu('admin-home/header')); ?>">
                                                <a href="<?php echo e(route('admin.header')); ?>">
                                                    <?php echo e(__('Header Area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/keyfeatures')); ?>">
                                                <a href="<?php echo e(route('admin.keyfeatures')); ?>"><?php echo e(__('Key Features')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-page-01/about-us')); ?>"><a
                                                        href="<?php echo e(route('admin.homeone.about.us')); ?>"><?php echo e(__('About Us Area')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/home-page-01/service-area')); ?>"><a
                                                        href="<?php echo e(route('admin.homeone.service.area')); ?>"><?php echo e(__('Service Area')); ?></a></li>
                                            <?php if(get_static_option('home_page_variant') != '03'): ?>
                                                <li class="<?php echo e(active_menu('admin-home/home-page-01/quality-area')); ?>"><a
                                                            href="<?php echo e(route('admin.homeone.quality.area')); ?>"><?php echo e(__('Quality Area')); ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <li class="<?php echo e(active_menu('admin-home/home-page-01/case-study-area')); ?>"><a
                                                        href="<?php echo e(route('admin.homeone.case.study.area')); ?>"><?php echo e(__('Case Study Area')); ?></a>
                                            </li>
                                            <?php if(get_static_option('home_page_variant') == '03'): ?>
                                                <li class="<?php echo e(active_menu('admin-home/home-page-01/cta-area')); ?>"><a
                                                            href="<?php echo e(route('admin.homeone.cta.area')); ?>"><?php echo e(__('Call To Action Area')); ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <li class="<?php echo e(active_menu('admin-home/home-page-01/testimonial')); ?>"><a
                                                        href="<?php echo e(route('admin.homeone.testimonial')); ?>"><?php echo e(__('Testimonial Area')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-page-01/price-plan')); ?>"><a
                                                        href="<?php echo e(route('admin.homeone.price.plan')); ?>"><?php echo e(__('Price Plan Area')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/home-page-01/contact-area')); ?>"><a
                                                        href="<?php echo e(route('admin.homeone.contact.area')); ?>"><?php echo e(__('Contact Area')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/home-page-01/latest-news')); ?>"><a
                                                        href="<?php echo e(route('admin.homeone.latest.news')); ?>"><?php echo e(__('Latest News Area')); ?></a>
                                            </li>
                                            <?php if(get_static_option('home_page_variant') == '04' || get_static_option('home_page_variant') == '02'): ?>
                                                <li class="<?php echo e(active_menu('admin-home/home-page-01/team-member')); ?>"><a
                                                            href="<?php echo e(route('admin.homeone.team.member')); ?>"><?php echo e(__('Team Member Area')); ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if(get_static_option('home_page_variant') == '02' || get_static_option('home_page_variant') == '03'): ?>
                                                <li class="<?php echo e(active_menu('admin-home/home-page-01/brand-logos')); ?>"><a
                                                            href="<?php echo e(route('admin.homeone.brand.logos')); ?>"><?php echo e(__('Brands Logos Area')); ?></a></li>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if(get_static_option('home_page_variant') == '05'): ?>
                                            <li class="<?php echo e(active_menu('admin-home/home-05/header')); ?>">
                                                <a href="<?php echo e(route('admin.home05.header')); ?>">
                                                    <?php echo e(__('Header Area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-05/about')); ?>">
                                                <a href="<?php echo e(route('admin.home05.about')); ?>">
                                                    <?php echo e(__('About Area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-05/expertises')); ?>">
                                                <a href="<?php echo e(route('admin.home05.expertises')); ?>">
                                                    <?php echo e(__('Experties Area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-05/what-we-offer')); ?>">
                                                <a href="<?php echo e(route('admin.home05.what.offer.area')); ?>">
                                                    <?php echo e(__('What We Offer Area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-05/recent-work')); ?>">
                                                <a href="<?php echo e(route('admin.home05.recent.work.area')); ?>">
                                                    <?php echo e(__('Recent Work Area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-05/cta-area')); ?>">
                                                <a href="<?php echo e(route('admin.home05.cta.area')); ?>">
                                                    <?php echo e(__('Cta Area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-05/testimonial-area')); ?>">
                                                <a href="<?php echo e(route('admin.home05.testimonial.area')); ?>">
                                                    <?php echo e(__('Testimonial Area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-05/news-area')); ?>">
                                                <a href="<?php echo e(route('admin.home05.news.area')); ?>">
                                                    <?php echo e(__('News Area')); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(get_static_option('home_page_variant') == '06'): ?>
                                            <li class="<?php echo e(active_menu('admin-home/home-06/header')); ?>">
                                                <a href="<?php echo e(route('admin.home06.header')); ?>">
                                                    <?php echo e(__('Header Area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-06/what-we-offer')); ?>">
                                                <a href="<?php echo e(route('admin.home06.what.offer')); ?>">
                                                    <?php echo e(__('What we offer area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-06/video-area')); ?>">
                                                <a href="<?php echo e(route('admin.home06.video.area')); ?>">
                                                    <?php echo e(__('Video area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-06/counterup-area')); ?>">
                                                <a href="<?php echo e(route('admin.home06.counterup.area')); ?>">
                                                    <?php echo e(__('Counterup area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-06/project-area')); ?>">
                                                <a href="<?php echo e(route('admin.home06.project.area')); ?>">
                                                    <?php echo e(__('Project area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-06/quote-faq-area')); ?>">
                                                <a href="<?php echo e(route('admin.home06.quote.faq.area')); ?>">
                                                    <?php echo e(__('Quote & FAQ area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-06/testimonial-area')); ?>">
                                                <a href="<?php echo e(route('admin.home06.testimonial.area')); ?>">
                                                    <?php echo e(__('Testimonial area')); ?>

                                                </a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/home-06/news-area')); ?>">
                                                <a href="<?php echo e(route('admin.home06.news.area')); ?>">
                                                    <?php echo e(__('News area')); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li class="<?php echo e(active_menu('admin-home/home-page-01/section-manage')); ?>">
                                            <a href="<?php echo e(route('admin.homeone.section.manage')); ?>"><?php echo e(__('Section Manage')); ?></a>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if(check_page_permission('about_page_manage')): ?>
                                <li class="main_dropdown <?php if(request()->is('admin-home/about-page/*')  ): ?> active <?php endif; ?> ">
                                    <a href="javascript:void(0)"
                                       aria-expanded="true">
                                       <?php echo e(__('About Page Manage')); ?>

                                    </a>
                                    <ul class="collapse">
                                        <li class="<?php echo e(active_menu('admin-home/about-page/about-us')); ?>"><a
                                                    href="<?php echo e(route('admin.about.page.about')); ?>"><?php echo e(__('About Us Section')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/about-page/global-network')); ?>"><a
                                                    href="<?php echo e(route('admin.about.global.network')); ?>"><?php echo e(__('Global Network Section')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/about-page/experience')); ?>"><a
                                                    href="<?php echo e(route('admin.about.experience')); ?>"><?php echo e(__('Experience Section')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/about-page/team-member')); ?>"><a
                                                    href="<?php echo e(route('admin.about.team.member')); ?>"><?php echo e(__('Team Member Section')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/about-page/testimonial')); ?>"><a
                                                    href="<?php echo e(route('admin.about.testimonial')); ?>"><?php echo e(__('Testimonial Section')); ?></a></li>
                                        <li class="<?php echo e(active_menu('admin-home/about-page/section-manage')); ?>"><a
                                                    href="<?php echo e(route('admin.about.page.section.manage')); ?>"><?php echo e(__('Section Manage')); ?></a>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if(check_page_permission_by_string('Contact Page Manage')): ?>
                                <li class="main_dropdown <?php if(request()->is('admin-home/contact-page/*')  ): ?> active <?php endif; ?>">
                                    <a href="javascript:void(0)"
                                       aria-expanded="true">
                                        <?php echo e(__('Contact Page Manage')); ?>

                                    </a>
                                    <ul class="collapse">
                                        <li class="<?php echo e(active_menu('admin-home/contact-page/contact-info')); ?>">
                                            <a href="<?php echo e(route('admin.contact.info')); ?>"><?php echo e(__('Contact Info')); ?></a>
                                        </li>
                                        <li class="<?php echo e(active_menu('admin-home/contact-page/form-area')); ?>">
                                            <a href="<?php echo e(route('admin.contact.page.form.area')); ?>"><?php echo e(__('Form Area')); ?></a>
                                        </li>
                                        <li class="<?php echo e(active_menu('admin-home/contact-page/map')); ?>">
                                            <a href="<?php echo e(route('admin.contact.page.map')); ?>"><?php echo e(__('Google Map Area')); ?></a>
                                        </li>
                                        <li class="<?php echo e(active_menu('admin-home/contact-page/section-manage')); ?>">
                                            <a href="<?php echo e(route('admin.contact.page.section.manage')); ?>"><?php echo e(__('Section Manage')); ?></a>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if(check_page_permission_by_string('Feedback Page Manage')): ?>
                                <li class="main_dropdown <?php if(request()->is('admin-home/feedback-page/*')  ): ?> active <?php endif; ?>">
                                    <a href="javascript:void(0)"
                                       aria-expanded="true">
                                        <?php echo e(__('Feedback Page Manage')); ?>

                                    </a>
                                    <ul class="collapse">
                                        <li class="<?php echo e(active_menu('admin-home/feedback-page/page-settings')); ?>">
                                            <a href="<?php echo e(route('admin.feedback.page.settings')); ?>"><?php echo e(__('Page Settings')); ?></a>
                                        </li>
                                        <li class="<?php echo e(active_menu('admin-home/feedback-page/form-builder')); ?>">
                                            <a href="<?php echo e(route('admin.feedback.page.form.builder')); ?>"><?php echo e(__('Form Builder')); ?></a>
                                        </li>
                                        <li class="<?php echo e(active_menu('admin-home/feedback-page/all-feedback')); ?>">
                                            <a href="<?php echo e(route('admin.feedback.all')); ?>"><?php echo e(__('All Feedback')); ?></a>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if(check_page_permission_by_string('404 Page Manage')): ?>
                                <li class="main_dropdown <?php echo e(active_menu('admin-home/404-page-manage')); ?>">
                                    <a href="<?php echo e(route('admin.404.page.settings')); ?>" aria-expanded="true">
                                        <?php echo e(__('404 Page Manage')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(!empty(get_static_option('site_maintenance_mode'))): ?>
                                <li class="main_dropdown <?php echo e(active_menu('admin-home/maintains-page/settings')); ?>">
                                    <a href="<?php echo e(route('admin.maintains.page.settings')); ?>"
                                       aria-expanded="true">
                                       <?php echo e(__('Maintain Page Manage')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="main_dropdown
                    <?php if(request()->is('admin-home/appearance-settings/*')): ?> active <?php endif; ?>
                    <?php echo e(active_menu('admin-home/menu')); ?>

                    <?php if(request()->is('admin-home/menu-edit/*')): ?> active <?php endif; ?>
                    <?php echo e(active_menu('admin-home/widgets')); ?>

                    <?php if(request()->is('admin-home/widgets/*')): ?> active <?php endif; ?>
                    <?php if(request()->is('admin-home/popup-builder/*')): ?> active <?php endif; ?>
                    <?php if(request()->is('admin-home/form-builder/*')): ?> active <?php endif; ?>
                    ">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-settings"></i>
                            <span><?php echo e(__('Appearance Settings')); ?></span></a>
                        <ul class="collapse ">
                            <?php if(check_page_permission_by_string('Topbar Settings')): ?>
                                <li class="<?php echo e(active_menu('admin-home/appearance-settings/topbar-settings')); ?>">
                                    <a href="<?php echo e(route('admin.topbar.settings')); ?>"
                                       aria-expanded="true">
                                        <?php echo e(__('Topbar Settings')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="<?php echo e(active_menu('admin-home/appearance-settings/navbar-manage')); ?>">
                                <a href="<?php echo e(route('admin.general.site.identity')); ?>"><?php echo e(__('Navbar Manage')); ?></a>
                            </li>
                            <?php if(check_page_permission_by_string('Home Variant')): ?>
                                <li class="main_dropdown <?php echo e(active_menu('admin-home/appearance-settings/home-variant')); ?>">
                                    <a href="<?php echo e(route('admin.home.variant')); ?>"
                                       aria-expanded="true">
                                        <?php echo e(__('Home Variant')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if(check_page_permission_by_string('Menus Manage')): ?>
                                <li
                                        class="main_dropdown
                                        <?php echo e(active_menu('admin-home/menu')); ?>

                                        <?php if(request()->is('admin-home/menu-edit/*')): ?> active <?php endif; ?>
                                        ">
                                    <a href="javascript:void(0)" aria-expanded="true">
                                        <?php echo e(__('Menus Manage')); ?></a>
                                    <ul class="collapse">
                                        <li class="<?php echo e(active_menu('admin-home/menu')); ?>"><a
                                                    href="<?php echo e(route('admin.menu')); ?>"><?php echo e(__('All Menus')); ?></a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                                <?php if(check_page_permission_by_string('Widgets Manage')): ?>
                                    <li
                                            class="main_dropdown
                                            <?php echo e(active_menu('admin-home/widgets')); ?>

                                            <?php if(request()->is('admin-home/widgets/*')): ?> active <?php endif; ?>
                                                    ">
                                        <a href="javascript:void(0)" aria-expanded="true">
                                            <?php echo e(__('Widgets Manage')); ?></a>
                                        <ul class="collapse">
                                            <li class="<?php echo e(active_menu('admin-home/widgets')); ?>"><a
                                                        href="<?php echo e(route('admin.widgets')); ?>"><?php echo e(__('All Widgets')); ?></a></li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <?php if(check_page_permission_by_string('Popup Builder')): ?>
                                    <li class="main_dropdown <?php if(request()->is('admin-home/popup-builder/*')): ?> active <?php endif; ?>">
                                        <a href="javascript:void(0)"
                                           aria-expanded="true">
                                            <?php echo e(__('Popup Builder')); ?>

                                        </a>
                                        <ul class="collapse">
                                            <li class="<?php echo e(active_menu('admin-home/popup-builder/all')); ?>"><a
                                                        href="<?php echo e(route('admin.popup.builder.all')); ?>"><?php echo e(__('All Popup')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/popup-builder/new')); ?>"><a
                                                        href="<?php echo e(route('admin.popup.builder.new')); ?>"><?php echo e(__('New Popup')); ?></a></li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <?php if(check_page_permission_by_string('Form Builder')): ?>
                                    <li class="main_dropdown <?php if(request()->is('admin-home/form-builder/*')): ?> active <?php endif; ?>">
                                        <a href="javascript:void(0)"
                                           aria-expanded="true">
                                            <?php echo e(__('Form Builder')); ?>

                                        </a>
                                        <ul class="collapse">
                                            <li class="<?php echo e(active_menu('admin-home/form-builder/get-in-touch')); ?>"><a
                                                        href="<?php echo e(route('admin.form.builder.get.in.touch')); ?>"><?php echo e(__('Get In Touch Form')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/form-builder/service-query')); ?>"><a
                                                        href="<?php echo e(route('admin.form.builder.service.query')); ?>"><?php echo e(__('Service Query Form')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/form-builder/case-study-query')); ?>"><a
                                                        href="<?php echo e(route('admin.form.builder.case.study.query')); ?>"><?php echo e(__('Case Study Query Form')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/form-builder/quote-form')); ?>"><a
                                                        href="<?php echo e(route('admin.form.builder.quote')); ?>"><?php echo e(__('Quote Form')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/form-builder/order-form')); ?>"><a
                                                        href="<?php echo e(route('admin.form.builder.order')); ?>"><?php echo e(__('Order Form')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/form-builder/contact-form')); ?>"><a
                                                        href="<?php echo e(route('admin.form.builder.contact')); ?>"><?php echo e(__('Contact Form')); ?></a></li>
                                            <li class="<?php echo e(active_menu('admin-home/form-builder/apply-job-form')); ?>"><a
                                                        href="<?php echo e(route('admin.form.builder.apply.job.form')); ?>"><?php echo e(__('Apply Job Form')); ?></a>
                                            </li>
                                            <li class="<?php echo e(active_menu('admin-home/form-builder/event-attendance')); ?>"><a
                                                        href="<?php echo e(route('admin.form.builder.event.attendance.form')); ?>"><?php echo e(__('Event Attendance Form')); ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                        </ul>
                    </li>
                    <?php if(check_page_permission_by_string('General Settings')): ?>
                    <li class="main_dropdown <?php if(request()->is('admin-home/general-settings/*')): ?> active <?php endif; ?>">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-settings"></i>
                            <span><?php echo e(__('General Settings')); ?></span></a>
                        <ul class="collapse ">
                            <li class="<?php echo e(active_menu('admin-home/general-settings/site-identity')); ?>"><a
                                        href="<?php echo e(route('admin.general.site.identity')); ?>"><?php echo e(__('Site Identity')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/basic-settings')); ?>"><a
                                        href="<?php echo e(route('admin.general.basic.settings')); ?>"><?php echo e(__('Basic Settings')); ?></a>
                            </li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/typography-settings')); ?>"><a
                                        href="<?php echo e(route('admin.general.typography.settings')); ?>"><?php echo e(__('Typography Settings')); ?></a>
                            </li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/seo-settings')); ?>"><a
                                        href="<?php echo e(route('admin.general.seo.settings')); ?>"><?php echo e(__('SEO Settings')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/scripts')); ?>"><a
                                        href="<?php echo e(route('admin.general.scripts.settings')); ?>"><?php echo e(__('Third Party Scripts')); ?></a>
                            </li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/email-template')); ?>"><a
                                        href="<?php echo e(route('admin.general.email.template')); ?>"><?php echo e(__('Email Template')); ?></a>
                            </li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/email-settings')); ?>"><a
                                        href="<?php echo e(route('admin.general.email.settings')); ?>"><?php echo e(__('Email Settings')); ?></a>
                            </li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/smtp-settings')); ?>"><a
                                        href="<?php echo e(route('admin.general.smtp.settings')); ?>"><?php echo e(__('SMTP Settings')); ?></a>
                            </li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/regenerate-image')); ?>"><a
                                        href="<?php echo e(route('admin.general.regenerate.thumbnail')); ?>"><?php echo e(__('Regenerate Media Image')); ?></a>
                            </li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/page-settings')); ?>"><a
                                        href="<?php echo e(route('admin.general.page.settings')); ?>"><?php echo e(__('Page Settings')); ?></a></li>
                            <?php if(!empty(get_static_option('site_payment_gateway'))): ?>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/payment-settings')); ?>"><a
                                        href="<?php echo e(route('admin.general.payment.settings')); ?>"><?php echo e(__('Payment Gateway Settings')); ?></a></li>
                            <?php endif; ?>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/custom-css')); ?>"><a
                                        href="<?php echo e(route('admin.general.custom.css')); ?>"><?php echo e(__('Custom CSS')); ?></a></li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/custom-js')); ?>"><a
                                        href="<?php echo e(route('admin.general.custom.js')); ?>"><?php echo e(__('Custom JS')); ?></a></li>

                            <li class="<?php echo e(active_menu('admin-home/general-settings/cache-settings')); ?>"><a
                                        href="<?php echo e(route('admin.general.cache.settings')); ?>"><?php echo e(__('Cache Settings')); ?></a>
                            </li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/gdpr-settings')); ?>"><a
                                        href="<?php echo e(route('admin.general.gdpr.settings')); ?>"><?php echo e(__('GDPR Compliant Cookies Settings')); ?></a>
                            </li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/preloader-settings')); ?>"><a
                                    href="<?php echo e(route('admin.general.preloader.settings')); ?>"><?php echo e(__('Preloader Settings')); ?></a>
                            </li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/popup-settings')); ?>"><a
                                    href="<?php echo e(route('admin.general.popup.settings')); ?>"><?php echo e(__('Popup Settings')); ?></a>
                            </li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/sitemap-settings')); ?>"><a
                                    href="<?php echo e(route('admin.general.sitemap.settings')); ?>"><?php echo e(__('Sitemap Settings')); ?></a>
                            </li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/rss-settings')); ?>"><a
                                    href="<?php echo e(route('admin.general.rss.feed.settings')); ?>"><?php echo e(__('RSS Feed Settings')); ?></a>
                            </li>
                            
                            <li class="<?php echo e(active_menu('admin-home/general-settings/module-settings')); ?>"><a
                                    href="<?php echo e(route('admin.general.module.settings')); ?>"><?php echo e(__('Module Settings')); ?></a>
                            </li>
                            <li class="<?php echo e(active_menu('admin-home/general-settings/license-setting')); ?>"><a
                                        href="<?php echo e(route('admin.general.license.settings')); ?>"><?php echo e(__('Licence Settings')); ?></a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if(check_page_permission('languages')): ?>
                    <li class="main_dropdown <?php if(request()->is('admin-home/languages/*') || request()->is('admin-home/languages') ): ?> active <?php endif; ?>">
                        <a href="<?php echo e(route('admin.languages')); ?>" aria-expanded="true"><i class="ti-signal"></i>
                            <span><?php echo e(__('Languages')); ?></span></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
<?php /**PATH /home/xgenxchi/public_html/laravel/nexelit/beta/@core/resources/views/backend/partials/sidebar.blade.php ENDPATH**/ ?>