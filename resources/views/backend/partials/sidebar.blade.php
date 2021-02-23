<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo" style="max-height: 50px;">
            <a href="{{route('admin.home')}}">
                @php
                    $logo_type = 'site_logo';
                    if(!empty(get_static_option('site_admin_dark_mode'))){
                        $logo_type = 'site_white_logo';
                    }
                @endphp
                {!! render_image_markup_by_attachment_id(get_static_option($logo_type)) !!}
            </a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav id="main_menu_wrap">
                <ul class="metismenu" id="menu">
                    <li class="{{active_menu('admin-home')}}">
                        <a href="{{route('admin.home')}}"
                           aria-expanded="true">
                            <i class="ti-dashboard"></i>
                            <span>@lang('dashboard')</span>
                        </a>
                    </li>
                    @if(check_page_permission('admin_manage'))
                    <li
                        class="main_dropdown {{active_menu('admin-home/new-user')}}
                        {{active_menu('admin-home/all-user')}}
                        {{active_menu('admin-home/all-user/role')}}
                        "
                    >
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-user"></i>
                            <span>{{__('Admin Manage')}}</span></a>
                        <ul class="collapse">
                            <li class="{{active_menu('admin-home/all-user')}}"><a
                                        href="{{route('admin.all.user')}}">{{__('All Admin')}}</a></li>
                            <li class="{{active_menu('admin-home/new-user')}}"><a
                                        href="{{route('admin.new.user')}}">{{__('Add New Admin')}}</a></li>
                            <li class="{{active_menu('admin-home/all-user/role')}}"><a
                                        href="{{route('admin.all.user.role')}}">{{__('All Admin Role')}}</a></li>
                        </ul>
                    </li>
                    @endif
                    @if(check_page_permission_by_string('Users Manage'))
                    <li
                        class="main_dropdown {{active_menu('admin-home/frontend/new-user')}}
                        {{active_menu('admin-home/frontend/all-user')}}
                        {{active_menu('admin-home/frontend/all-user/role')}}
                        "
                    >
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-user"></i>
                            <span>{{__('Users Manage')}}</span></a>
                        <ul class="collapse">
                            <li class="{{active_menu('admin-home/frontend/all-user')}}"><a
                                    href="{{route('admin.all.frontend.user')}}">{{__('All Users')}}</a></li>
                            <li class="{{active_menu('admin-home/frontend/new-user')}}"><a
                                    href="{{route('admin.frontend.new.user')}}">{{__('Add New User')}}</a></li>
                        </ul>
                    </li>
                    @endif
                    @if(check_page_permission_by_string('Newsletter Manage'))
                    <li
                        class="main_dropdown {{active_menu('admin-home/newsletter')}} @if(request()->is('admin-home/newsletter/*')) active @endif
                     ">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-email"></i>
                            <span>{{__('Newsletter Manage')}}</span></a>
                        <ul class="collapse">
                            <li class="{{active_menu('admin-home/newsletter')}}"><a
                                        href="{{route('admin.newsletter')}}">{{__('All Subscriber')}}</a></li>
                            <li class="{{active_menu('admin-home/newsletter/all')}}"><a
                                        href="{{route('admin.newsletter.mail')}}">{{__('Send Mail To All')}}</a></li>
                        </ul>
                    </li>
                    @endif

                    @if(check_page_permission_by_string('Pages Manage'))
                        <li
                        class="main_dropdown
                        {{active_menu('admin-home/page')}}
                        {{active_menu('admin-home/new-page')}}
                        @if(request()->is('admin-home/page-edit/*')) active @endif
                        ">
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-write"></i>
                                <span>{{__('Pages')}}</span></a>
                            <ul class="collapse">
                                <li class="{{active_menu('admin-home/page')}}"><a
                                            href="{{route('admin.page')}}">{{__('All Pages')}}</a></li>
                                <li class="{{active_menu('admin-home/new-page')}}"><a
                                            href="{{route('admin.page.new')}}">{{__('Add New Page')}}</a></li>
                            </ul>
                        </li>
                    @endif

                    @if(check_page_permission_by_string('Blogs Manage'))
                        <li
                         class="main_dropdown
                        {{active_menu('admin-home/blog')}}
                        @if(request()->is('admin-home/blog/*')) active @endif
                        "
                        >
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-write"></i>
                                <span>{{__('Blogs')}}</span></a>
                            <ul class="collapse">
                                <li class="{{active_menu('admin-home/blog')}}"><a
                                            href="{{route('admin.blog')}}">{{__('All Blog')}}</a></li>
                                <li class="{{active_menu('admin-home/blog/category')}}"><a
                                            href="{{route('admin.blog.category')}}">{{__('Category')}}</a></li>
                                <li class="{{active_menu('admin-home/new-blog')}}"><a
                                            href="{{route('admin.blog.new')}}">{{__('Add New Post')}}</a></li>
                                <li class="{{active_menu('admin-home/blog/page-settings')}}"><a
                                        href="{{route('admin.blog.page.settings')}}">{{__('Blog Page Settings')}}</a></li>
                                <li class="{{active_menu('admin-home/blog/single-settings')}}"><a
                                        href="{{route('admin.blog.single.settings')}}">{{__('Blog Single Settings')}}</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(check_page_permission_by_string('Services'))
                    <li class="main_dropdown
                    @if(request()->is('admin-home/services/*')) active @endif
                    {{active_menu('admin-home/services')}}
                    ">
                        <a href="javascript:void(0)"
                           aria-expanded="true">
                            <i class="ti-layout"></i>
                            <span>{{__('Services')}}</span>
                        </a>
                        <ul class="collapse">
                            <li class="{{active_menu('admin-home/services')}}"><a
                                    href="{{route('admin.services')}}">{{__('All Services')}}</a></li>
                            <li class="{{active_menu('admin-home/services/new')}}"><a
                                    href="{{route('admin.services.new')}}">{{__('New Service')}}</a></li>
                            <li class="{{active_menu('admin-home/services/category')}}"><a
                                    href="{{route('admin.service.category')}}">{{__('Category')}}</a></li>
                            <li class="{{active_menu('admin-home/services/page-settings')}}"><a
                                    href="{{route('admin.services.page.settings')}}">{{__('Service Page')}}</a></li>
                            <li class="{{active_menu('admin-home/services/single-page-settings')}}"><a
                                    href="{{route('admin.services.single.page.settings')}}">{{__('Service Single Page')}}</a></li>
                        </ul>
                    </li>
                    @endif
                    @if(check_page_permission_by_string('Case Study'))
                    <li class="main_dropdown
                    @if(request()->is('admin-home/works/*')) active @endif
                    {{active_menu('admin-home/works')}}
                            ">
                        <a href="javascript:void(0)"
                           aria-expanded="true">
                            <i class="ti-layout"></i>
                            <span>{{__('Case Study')}}</span>
                        </a>
                        <ul class="collapse">
                            <li class="{{active_menu('admin-home/works')}}"><a
                                        href="{{route('admin.work')}}">{{__('All Case Study')}}</a></li>
                            <li class="{{active_menu('admin-home/works/new')}}"><a
                                    href="{{route('admin.work.new')}}">{{__('New Case Study')}}</a></li>
                            <li class="{{active_menu('admin-home/works/category')}}"><a
                                        href="{{route('admin.work.category')}}">{{__('Category')}}</a></li>
                            <li class="{{active_menu('admin-home/works/single-page/settings')}}"><a
                                    href="{{route('admin.work.single.page.settings')}}">{{__('Case Single Page Settings')}}</a></li>
                        </ul>
                    </li>
                    @endif
                    @if(check_page_permission_by_string('Gallery Page'))
                        <li class="main_dropdown
                        {{active_menu('admin-home/gallery-page')}}
                        @if(request()->is('admin-home/gallery-page/*')) active @endif
                                ">
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-write"></i>
                                <span>{{__('Image Gallery')}}</span></a>
                            <ul class="collapse">
                                <li class="{{active_menu('admin-home/gallery-page')}}">
                                    <a href="{{route('admin.gallery.all')}}" >{{__('Image Gallery')}}</a>
                                </li>
                                <li class="{{active_menu('admin-home/gallery-page/category')}}">
                                    <a href="{{route('admin.gallery.category')}}" >{{__('Category')}}</a>
                                </li>
                                <li class="{{active_menu('admin-home/gallery-page/page-settings')}}">
                                    <a href="{{route('admin.gallery.page.settings')}}" >{{__('Page Settings')}}</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if(check_page_permission_by_string('Price Plan'))
                        <li class="main_dropdown {{active_menu('admin-home/price-plan')}}
                        @if(request()->is('admin-home/price-plan/*')) active @endif
                                ">
                            <a href="javascript:void(0)" aria-expanded="true"><i class="ti-write"></i>
                                <span>{{__('Price Plan')}}</span></a>
                            <ul class="collapse">
                                <li class="{{active_menu('admin-home/price-plan')}}">
                                    <a href="{{route('admin.price.plan')}}" >{{__('All Price Plan')}}</a>
                                </li>
                                <li class="{{active_menu('admin-home/price-plan/new')}}">
                                    <a href="{{route('admin.price.plan.new')}}" >{{__('New Price Plan')}}</a>
                                </li>
                                <li class="{{active_menu('admin-home/price-plan/category')}}">
                                    <a href="{{route('admin.price.plan.category')}}" >{{__('Category')}}</a>
                                </li>

                            </ul>
                        </li>
                    @endif
                    @if(check_page_permission_by_string('Faq'))
                    <li class="main_dropdown {{active_menu('admin-home/faq')}}">
                        <a href="{{route('admin.faq')}}" aria-expanded="true"><i class="ti-control-forward"></i>
                            <span>{{__('Faq')}}</span></a>
                    </li>
                    @endif
                    @if(check_page_permission_by_string('Brand Logos'))
                    <li class="main_dropdown {{active_menu('admin-home/brands')}}">
                        <a href="{{route('admin.brands')}}" aria-expanded="true"><i class="ti-control-forward"></i>
                            <span>{{__('Brand Logos')}}</span></a>
                    </li>
                    @endif
                    @if(check_page_permission_by_string('Team Members'))
                    <li class="main_dropdown {{active_menu('admin-home/team-member')}}">
                        <a href="{{route('admin.team.member')}}" aria-expanded="true"><i class="ti-control-forward"></i>
                            <span>{{__('Team Members')}}</span></a>
                    </li>
                    @endif
                    @if(check_page_permission_by_string('Testimonial'))
                    <li class="main_dropdown {{active_menu('admin-home/testimonial')}}">
                        <a href="{{route('admin.testimonial')}}" aria-expanded="true"><i class="ti-control-forward"></i>
                            <span>{{__('Testimonial')}}</span></a>
                    </li>
                    @endif
                    @if(check_page_permission_by_string('Counterup'))
                    <li class="main_dropdown {{active_menu('admin-home/counterup')}}">
                        <a href="{{route('admin.counterup')}}" aria-expanded="true"><i class="ti-exchange-vertical"></i>
                            <span>{{__('Counterup')}}</span></a>
                    </li>
                    @endif
                    <li class="main_dropdown
                    @if(request()->is('admin-home/quote-manage/*')) active @endif
                    @if(request()->is('admin-home/package/*')) active @endif
                    {{active_menu('admin-home/payment-logs')}}
                    {{active_menu('admin-home/payment-logs/report')}}
                    {{active_menu('admin-home/jobs')}}
                    @if(request()->is('admin-home/jobs/*')) active @endif
                    {{active_menu('admin-home/events')}}
                    @if(request()->is('admin-home/events/*')) active @endif
                    {{active_menu('admin-home/products')}}
                    @if(request()->is('admin-home/products/*')) active @endif
                    {{active_menu('admin-home/donations')}}
                    @if(request()->is('admin-home/donations/*')) active @endif
                    {{active_menu('admin-home/knowledge')}} @if(request()->is('admin-home/knowledge/*')) active @endif
                        ">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-settings"></i>
                            <span>{{__('All Modules')}}</span></a>
                        <ul class="collapse ">
                            @if(check_page_permission_by_string('Quote Manage'))
                                <li class="main_dropdown @if(request()->is('admin-home/quote-manage/*')) active @endif ">
                                    <a href="javascript:void(0)" aria-expanded="true">
                                        {{__('Quote Manage')}}</a>
                                    <ul class="collapse">
                                        <li class="{{active_menu('admin-home/quote-manage/all')}}"><a
                                                    href="{{route('admin.quote.manage.all')}}">{{__('All Quote')}}</a></li>
                                        <li class="{{active_menu('admin-home/quote-manage/pending')}}"><a
                                                    href="{{route('admin.quote.manage.pending')}}">{{__('Pending Quote')}}</a></li>
                                        <li class="{{active_menu('admin-home/quote-manage/completed')}}"><a
                                                    href="{{route('admin.quote.manage.completed')}}">{{__('Complete Quote')}}</a></li>
                                        <li class="{{active_menu('admin-home/quote-manage/quote-page')}}">
                                            <a href="{{route('admin.quote.page')}}">
                                                {{__('Quote Page Manage')}}
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            @if(check_page_permission_by_string('Package Orders Manage'))
                                <li class="main_dropdown @if(request()->is('admin-home/package/*')) active @endif
                                {{active_menu('admin-home/payment-logs')}}
                                {{active_menu('admin-home/payment-logs/report')}}
                                        ">
                                    <a href="javascript:void(0)" aria-expanded="true">
                                        {{__('Package Orders Manage')}}</a>
                                    <ul class="collapse">
                                        <li class="{{active_menu('admin-home/package/order-manage/all')}}"><a
                                                    href="{{route('admin.package.order.manage.all')}}">{{__('All Order')}}</a></li>
                                        <li class="{{active_menu('admin-home/package/order-manage/pending')}}"><a
                                                    href="{{route('admin.package.order.manage.pending')}}">{{__('Pending Order')}}</a></li>
                                        <li class="{{active_menu('admin-home/package/order-manage/in-progress')}}"><a
                                                    href="{{route('admin.package.order.manage.in.progress')}}">{{__('In Progress Order')}}</a></li>
                                        <li class="{{active_menu('admin-home/package/order-manage/completed')}}"><a
                                                    href="{{route('admin.package.order.manage.completed')}}">{{__('Completed Order')}}</a></li>
                                        <li class="{{active_menu('admin-home/package/order-manage/success-page')}}"><a
                                                    href="{{route('admin.package.order.success.page')}}">{{__('Success Order Page')}}</a></li>
                                        <li class="{{active_menu('admin-home/package/order-manage/cancel-page')}}"><a
                                                    href="{{route('admin.package.order.cancel.page')}}">{{__('Cancel Order Page')}}</a></li>
                                        <li class="{{active_menu('admin-home/package/order-page')}}">
                                            <a href="{{route('admin.package.order.page')}}">{{__('Order Page Manage')}}</a>
                                        </li>
                                        <li class="{{active_menu('admin-home/package/order-report')}}">
                                            <a href="{{route('admin.package.order.report')}}">{{__('Order Report')}}</a>
                                        </li>
                                        <li class="{{active_menu('admin-home/payment-logs')}}"><a
                                                    href="{{route('admin.payment.logs')}}">{{__('All Payment Logs')}}</a></li>
                                        <li class="{{active_menu('admin-home/payment-logs/report')}}"><a
                                                    href="{{route('admin.payment.report')}}">{{__('Payment Report')}}</a></li>
                                    </ul>
                                </li>
                            @endif
                            @if(check_page_permission_by_string('Job Post Manage') && !empty(get_static_option('job_module_status')))
                                <li
                                    class="main_dropdown
                                    {{active_menu('admin-home/jobs')}}
                                    @if(request()->is('admin-home/jobs/*')) active @endif
                                    ">
                                    <a href="javascript:void(0)" aria-expanded="true">
                                        {{__('Job Post Manage')}}</a>
                                    <ul class="collapse">
                                        <li class="{{active_menu('admin-home/jobs')}}"><a
                                                    href="{{route('admin.jobs.all')}}">{{__('All Jobs')}}</a></li>
                                        <li class="{{active_menu('admin-home/jobs/category')}}"><a
                                                    href="{{route('admin.jobs.category.all')}}">{{__('Category')}}</a></li>
                                        <li class="{{active_menu('admin-home/new-jobs')}}"><a
                                                    href="{{route('admin.jobs.new')}}">{{__('Add New Job')}}</a></li>
                                        <li class="{{active_menu('admin-home/jobs/page-settings')}}"><a
                                                    href="{{route('admin.jobs.page.settings')}}">{{__('Job Page Settings')}}</a></li>
                                        <li class="{{active_menu('admin-home/jobs/single-page-settings')}}"><a
                                                    href="{{route('admin.jobs.single.page.settings')}}">{{__('Job Single Page Settings')}}</a></li>
                                        <li class="{{active_menu('admin-home/jobs/success-page-settings')}}">
                                            <a href="{{route('admin.jobs.success.page.settings')}}">{{__('Job Success Page Settings')}}</a>
                                        </li>
                                        <li class="{{active_menu('admin-home/jobs/cancel-page-settings')}}">
                                            <a href="{{route('admin.jobs.cancel.page.settings')}}">{{__('Job Cancel Page Settings')}}</a>
                                        </li>
                                        <li class="{{active_menu('admin-home/jobs/applicant')}}"><a
                                                    href="{{route('admin.jobs.applicant')}}">{{__('All Applicant')}}</a></li>
                                        <li class="{{active_menu('admin-home/jobs/applicant/report')}}"><a
                                                    href="{{route('admin.jobs.applicant.report')}}">{{__('Applicant Report')}}</a></li>
                                    </ul>
                                </li>
                            @endif
                            @if(check_page_permission_by_string('Events Manage') && !empty(get_static_option('events_module_status')))
                                    <li class="main_dropdown
                                    {{active_menu('admin-home/events')}}
                                    @if(request()->is('admin-home/events/*')) active @endif
                                            ">
                                        <a href="javascript:void(0)" aria-expanded="true">
                                            {{__('Events Manage')}}</a>
                                        <ul class="collapse">
                                            <li class="{{active_menu('admin-home/events')}}"><a
                                                        href="{{route('admin.events.all')}}">{{__('All Events')}}</a></li>
                                            <li class="{{active_menu('admin-home/events/category')}}"><a
                                                        href="{{route('admin.events.category.all')}}">{{__('Category')}}</a></li>
                                            <li class="{{active_menu('admin-home/events/new')}}"><a
                                                        href="{{route('admin.events.new')}}">{{__('Add New Event')}}</a></li>
                                            <li class="{{active_menu('admin-home/events/page-settings')}}"><a
                                                        href="{{route('admin.events.page.settings')}}">{{__('Event Page Settings')}}</a></li>
                                            <li class="{{active_menu('admin-home/events/single-page-settings')}}"><a
                                                        href="{{route('admin.events.single.page.settings')}}">{{__('Event Single Settings')}}</a></li>
                                            <li class="{{active_menu('admin-home/events/attendance')}}"><a
                                                        href="{{route('admin.events.attendance')}}">{{__('Event Attendance')}}</a></li>
                                            <li class="{{active_menu('admin-home/events/event-attendance-logs')}}"><a
                                                        href="{{route('admin.event.attendance.logs')}}">{{__('Event Attendance Logs')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/events/event-payment-logs')}}"><a
                                                        href="{{route('admin.event.payment.logs')}}">{{__('Event Payment Logs')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/events/payment-success-page-settings')}}"><a
                                                        href="{{route('admin.events.payment.success.page.settings')}}">{{__('Payment Success Page Settings')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/events/payment-cancel-pag-settings')}}"><a
                                                        href="{{route('admin.events.payment.cancel.page.settings')}}">{{__('Payment Cancel Page Settings')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/events/attendance/report')}}"><a
                                                        href="{{route('admin.event.attendance.report')}}">{{__('Attendance Report')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/events/payment/report')}}"><a
                                                        href="{{route('admin.event.payment.report')}}">{{__('Payment Log Report')}}</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                            @if(check_page_permission_by_string('Products Manage') && !empty(get_static_option('product_module_status')))
                                    <li class="main_dropdown
                                    {{active_menu('admin-home/products')}}
                                    @if(request()->is('admin-home/products/*')) active @endif
                                            ">
                                        <a href="javascript:void(0)" aria-expanded="true">
                                            {{__('Products Manage')}}</a>
                                        <ul class="collapse">
                                            <li class="{{active_menu('admin-home/products')}}"><a
                                                        href="{{route('admin.products.all')}}">{{__('All Products')}}</a></li>
                                            <li class="{{active_menu('admin-home/products/new')}}"><a
                                                        href="{{route('admin.products.new')}}">{{__('Add New Product')}}</a></li>
                                            <li class="{{active_menu('admin-home/products/category')}}"><a
                                                        href="{{route('admin.products.category.all')}}">{{__('Category')}}</a></li>
                                            <li class="{{active_menu('admin-home/products/shipping')}}"><a
                                                        href="{{route('admin.products.shipping.all')}}">{{__('Shipping')}}</a></li>
                                            <li class="{{active_menu('admin-home/products/coupon')}}"><a
                                                        href="{{route('admin.products.coupon.all')}}">{{__('Coupon')}}</a></li>
                                            <li class="{{active_menu('admin-home/products/page-settings')}}"><a
                                                        href="{{route('admin.products.page.settings')}}">{{__('Product Page Settings')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/products/single-page-settings')}}"><a
                                                        href="{{route('admin.products.single.page.settings')}}">{{__('Product Single Page Settings')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/products/success-page-settings')}}"><a
                                                        href="{{route('admin.products.success.page.settings')}}">{{__('Order Success Page Settings')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/products/cancel-page-settings')}}"><a
                                                        href="{{route('admin.products.cancel.page.settings')}}">{{__('Order Cancel Page Settings')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/products/product-order-logs')}}"><a
                                                        href="{{route('admin.products.order.logs')}}">{{__('Orders')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/products/product-ratings')}}"><a
                                                        href="{{route('admin.products.ratings')}}">{{__('Ratings')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/products/order-report')}}">
                                                <a href="{{route('admin.products.order.report')}}">{{__('Order Report')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/products/tax-settings')}}">
                                                <a href="{{route('admin.products.tax.settings')}}">{{__('Tax Settings')}}</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                            @if(check_page_permission_by_string('Donations Manage') && !empty(get_static_option('donations_module_status')))
                                    <li class="main_dropdown
                                    {{active_menu('admin-home/donations')}}
                                    @if(request()->is('admin-home/donations/*')) active @endif
                                        ">
                                        <a href="javascript:void(0)" aria-expanded="true">
                                           {{__('Donations Manage')}}</a>
                                        <ul class="collapse">
                                            <li class="{{active_menu('admin-home/donations')}}"><a
                                                        href="{{route('admin.donations.all')}}">{{__('All Donations')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/donations/new')}}"><a
                                                        href="{{route('admin.donations.new')}}">{{__('Add New Donation')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/donations/page-settings')}}"><a
                                                        href="{{route('admin.donations.page.settings')}}">{{__('Donation Page Settings')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/donations/single-page-settings')}}"><a
                                                        href="{{route('admin.donations.single.page.settings')}}">{{__('Donation Single Settings')}}</a>
                                            </li>

                                            <li class="{{active_menu('admin-home/donations/donations-payment-logs')}}"><a
                                                        href="{{route('admin.donations.payment.logs')}}">{{__('Donation Payment Logs')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/donations/payment-success-page-settings')}}"><a
                                                        href="{{route('admin.donations.payment.success.page.settings')}}">{{__('Payment Success Page Settings')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/donations/payment-cancel-pag-settings')}}"><a
                                                        href="{{route('admin.donations.payment.cancel.page.settings')}}">{{__('Payment Cancel Page Settings')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/donations/report')}}">
                                                <a href="{{route('admin.donations.report')}}">{{__('Donation Report')}}</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                            @if(check_page_permission_by_string('Knowledgebase') && !empty(get_static_option('knowledgebase_module_status')))
                                <li class="main_dropdown {{active_menu('admin-home/knowledge')}} @if(request()->is('admin-home/knowledge/*')) active @endif"
                                >
                                    <a href="javascript:void(0)" aria-expanded="true">
                                        {{__('Knowledgebase')}}</a>
                                    <ul class="collapse">
                                        <li class="{{active_menu('admin-home/knowledge')}}"><a
                                                    href="{{route('admin.knowledge.all')}}">{{__('All Articles')}}</a></li>
                                        <li class="{{active_menu('admin-home/knowledge/category')}}"><a
                                                    href="{{route('admin.knowledge.category.all')}}">{{__('Topics')}}</a></li>
                                        <li class="{{active_menu('admin-home/knowledge/new')}}"><a
                                                    href="{{route('admin.knowledge.new')}}">{{__('Add New Article')}}</a></li>
                                        <li class="{{active_menu('admin-home/knowledge/page-settings')}}"><a
                                                    href="{{route('admin.knowledge.page.settings')}}">{{__('Knowledgebase Page Settings')}}</a></li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li class="main_dropdown
                        @if(request()->is('admin-home/home-page-01/*')  ) active @endif
                        @if(request()->is('admin-home/home-05/*')  ) active @endif
                        @if(request()->is('admin-home/home-06/*')  ) active @endif
                        {{active_menu('admin-home/header')}}
                        {{active_menu('admin-home/keyfeatures')}}
                        @if(request()->is('admin-home/about-page/*')  ) active @endif
                        @if(request()->is('admin-home/contact-page/*')  ) active @endif
                        @if(request()->is('admin-home/feedback-page/*')  ) active @endif
                        {{active_menu('admin-home/404-page-manage')}}
                        {{active_menu('admin-home/maintains-page/settings')}}
                        ">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-settings"></i>
                            <span>{{__('All Page Settings')}}</span></a>
                        <ul class="collapse ">
                            @if(check_page_permission_by_string('Home Page Manage'))
                                <li class="main_dropdown
                                @if(request()->is('admin-home/home-page-01/*')  ) active @endif
                                @if(request()->is('admin-home/home-05/*')  ) active @endif
                                @if(request()->is('admin-home/home-06/*')  ) active @endif
                                {{active_menu('admin-home/header')}}
                                {{active_menu('admin-home/keyfeatures')}}
                                ">
                                    <a href="javascript:void(0)"
                                       aria-expanded="true">
                                        {{__('Home Page Manage')}}
                                    </a>
                                    <ul class="collapse">
                                        @if(get_static_option('home_page_variant') == '02'
                                        || get_static_option('home_page_variant') == '03'
                                        || get_static_option('home_page_variant') == '04'
                                        || get_static_option('home_page_variant') == '01'
                                        )
                                            <li class="{{active_menu('admin-home/header')}}">
                                                <a href="{{route('admin.header')}}">
                                                    {{__('Header Area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/keyfeatures')}}">
                                                <a href="{{route('admin.keyfeatures')}}">{{__('Key Features')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-page-01/about-us')}}"><a
                                                        href="{{route('admin.homeone.about.us')}}">{{__('About Us Area')}}</a></li>
                                            <li class="{{active_menu('admin-home/home-page-01/service-area')}}"><a
                                                        href="{{route('admin.homeone.service.area')}}">{{__('Service Area')}}</a></li>
                                            @if(get_static_option('home_page_variant') != '03')
                                                <li class="{{active_menu('admin-home/home-page-01/quality-area')}}"><a
                                                            href="{{route('admin.homeone.quality.area')}}">{{__('Quality Area')}}</a>
                                                </li>
                                            @endif
                                            <li class="{{active_menu('admin-home/home-page-01/case-study-area')}}"><a
                                                        href="{{route('admin.homeone.case.study.area')}}">{{__('Case Study Area')}}</a>
                                            </li>
                                            @if(get_static_option('home_page_variant') == '03')
                                                <li class="{{active_menu('admin-home/home-page-01/cta-area')}}"><a
                                                            href="{{route('admin.homeone.cta.area')}}">{{__('Call To Action Area')}}</a>
                                                </li>
                                            @endif
                                            <li class="{{active_menu('admin-home/home-page-01/testimonial')}}"><a
                                                        href="{{route('admin.homeone.testimonial')}}">{{__('Testimonial Area')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-page-01/price-plan')}}"><a
                                                        href="{{route('admin.homeone.price.plan')}}">{{__('Price Plan Area')}}</a></li>
                                            <li class="{{active_menu('admin-home/home-page-01/contact-area')}}"><a
                                                        href="{{route('admin.homeone.contact.area')}}">{{__('Contact Area')}}</a></li>
                                            <li class="{{active_menu('admin-home/home-page-01/latest-news')}}"><a
                                                        href="{{route('admin.homeone.latest.news')}}">{{__('Latest News Area')}}</a>
                                            </li>
                                            @if(get_static_option('home_page_variant') == '04' || get_static_option('home_page_variant') == '02')
                                                <li class="{{active_menu('admin-home/home-page-01/team-member')}}"><a
                                                            href="{{route('admin.homeone.team.member')}}">{{__('Team Member Area')}}</a>
                                                </li>
                                            @endif
                                            @if(get_static_option('home_page_variant') == '02' || get_static_option('home_page_variant') == '03')
                                                <li class="{{active_menu('admin-home/home-page-01/brand-logos')}}"><a
                                                            href="{{route('admin.homeone.brand.logos')}}">{{__('Brands Logos Area')}}</a></li>
                                            @endif
                                        @endif
                                        @if(get_static_option('home_page_variant') == '05')
                                            <li class="{{active_menu('admin-home/home-05/header')}}">
                                                <a href="{{route('admin.home05.header')}}">
                                                    {{__('Header Area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-05/about')}}">
                                                <a href="{{route('admin.home05.about')}}">
                                                    {{__('About Area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-05/expertises')}}">
                                                <a href="{{route('admin.home05.expertises')}}">
                                                    {{__('Experties Area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-05/what-we-offer')}}">
                                                <a href="{{route('admin.home05.what.offer.area')}}">
                                                    {{__('What We Offer Area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-05/recent-work')}}">
                                                <a href="{{route('admin.home05.recent.work.area')}}">
                                                    {{__('Recent Work Area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-05/cta-area')}}">
                                                <a href="{{route('admin.home05.cta.area')}}">
                                                    {{__('Cta Area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-05/testimonial-area')}}">
                                                <a href="{{route('admin.home05.testimonial.area')}}">
                                                    {{__('Testimonial Area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-05/news-area')}}">
                                                <a href="{{route('admin.home05.news.area')}}">
                                                    {{__('News Area')}}
                                                </a>
                                            </li>
                                        @endif
                                        @if(get_static_option('home_page_variant') == '06')
                                            <li class="{{active_menu('admin-home/home-06/header')}}">
                                                <a href="{{route('admin.home06.header')}}">
                                                    {{__('Header Area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-06/what-we-offer')}}">
                                                <a href="{{route('admin.home06.what.offer')}}">
                                                    {{__('What we offer area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-06/video-area')}}">
                                                <a href="{{route('admin.home06.video.area')}}">
                                                    {{__('Video area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-06/counterup-area')}}">
                                                <a href="{{route('admin.home06.counterup.area')}}">
                                                    {{__('Counterup area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-06/project-area')}}">
                                                <a href="{{route('admin.home06.project.area')}}">
                                                    {{__('Project area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-06/quote-faq-area')}}">
                                                <a href="{{route('admin.home06.quote.faq.area')}}">
                                                    {{__('Quote & FAQ area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-06/testimonial-area')}}">
                                                <a href="{{route('admin.home06.testimonial.area')}}">
                                                    {{__('Testimonial area')}}
                                                </a>
                                            </li>
                                            <li class="{{active_menu('admin-home/home-06/news-area')}}">
                                                <a href="{{route('admin.home06.news.area')}}">
                                                    {{__('News area')}}
                                                </a>
                                            </li>
                                        @endif
                                        <li class="{{active_menu('admin-home/home-page-01/section-manage')}}">
                                            <a href="{{route('admin.homeone.section.manage')}}">{{__('Section Manage')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            @if(check_page_permission('about_page_manage'))
                                <li class="main_dropdown @if(request()->is('admin-home/about-page/*')  ) active @endif ">
                                    <a href="javascript:void(0)"
                                       aria-expanded="true">
                                       {{__('About Page Manage')}}
                                    </a>
                                    <ul class="collapse">
                                        <li class="{{active_menu('admin-home/about-page/about-us')}}"><a
                                                    href="{{route('admin.about.page.about')}}">{{__('About Us Section')}}</a></li>
                                        <li class="{{active_menu('admin-home/about-page/global-network')}}"><a
                                                    href="{{route('admin.about.global.network')}}">{{__('Global Network Section')}}</a></li>
                                        <li class="{{active_menu('admin-home/about-page/experience')}}"><a
                                                    href="{{route('admin.about.experience')}}">{{__('Experience Section')}}</a></li>
                                        <li class="{{active_menu('admin-home/about-page/team-member')}}"><a
                                                    href="{{route('admin.about.team.member')}}">{{__('Team Member Section')}}</a></li>
                                        <li class="{{active_menu('admin-home/about-page/testimonial')}}"><a
                                                    href="{{route('admin.about.testimonial')}}">{{__('Testimonial Section')}}</a></li>
                                        <li class="{{active_menu('admin-home/about-page/section-manage')}}"><a
                                                    href="{{route('admin.about.page.section.manage')}}">{{__('Section Manage')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            @if(check_page_permission_by_string('Contact Page Manage'))
                                <li class="main_dropdown @if(request()->is('admin-home/contact-page/*')  ) active @endif">
                                    <a href="javascript:void(0)"
                                       aria-expanded="true">
                                        {{__('Contact Page Manage')}}
                                    </a>
                                    <ul class="collapse">
                                        <li class="{{active_menu('admin-home/contact-page/contact-info')}}">
                                            <a href="{{route('admin.contact.info')}}">{{__('Contact Info')}}</a>
                                        </li>
                                        <li class="{{active_menu('admin-home/contact-page/form-area')}}">
                                            <a href="{{route('admin.contact.page.form.area')}}">{{__('Form Area')}}</a>
                                        </li>
                                        <li class="{{active_menu('admin-home/contact-page/map')}}">
                                            <a href="{{route('admin.contact.page.map')}}">{{__('Google Map Area')}}</a>
                                        </li>
                                        <li class="{{active_menu('admin-home/contact-page/section-manage')}}">
                                            <a href="{{route('admin.contact.page.section.manage')}}">{{__('Section Manage')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            @if(check_page_permission_by_string('Feedback Page Manage'))
                                <li class="main_dropdown @if(request()->is('admin-home/feedback-page/*')  ) active @endif">
                                    <a href="javascript:void(0)"
                                       aria-expanded="true">
                                        {{__('Feedback Page Manage')}}
                                    </a>
                                    <ul class="collapse">
                                        <li class="{{active_menu('admin-home/feedback-page/page-settings')}}">
                                            <a href="{{route('admin.feedback.page.settings')}}">{{__('Page Settings')}}</a>
                                        </li>
                                        <li class="{{active_menu('admin-home/feedback-page/form-builder')}}">
                                            <a href="{{route('admin.feedback.page.form.builder')}}">{{__('Form Builder')}}</a>
                                        </li>
                                        <li class="{{active_menu('admin-home/feedback-page/all-feedback')}}">
                                            <a href="{{route('admin.feedback.all')}}">{{__('All Feedback')}}</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            @if(check_page_permission_by_string('404 Page Manage'))
                                <li class="main_dropdown {{active_menu('admin-home/404-page-manage')}}">
                                    <a href="{{route('admin.404.page.settings')}}" aria-expanded="true">
                                        {{__('404 Page Manage')}}</a>
                                </li>
                            @endif
                            @if(!empty(get_static_option('site_maintenance_mode')))
                                <li class="main_dropdown {{active_menu('admin-home/maintains-page/settings')}}">
                                    <a href="{{route('admin.maintains.page.settings')}}"
                                       aria-expanded="true">
                                       {{__('Maintain Page Manage')}}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li class="main_dropdown
                    @if(request()->is('admin-home/appearance-settings/*')) active @endif
                    {{active_menu('admin-home/menu')}}
                    @if(request()->is('admin-home/menu-edit/*')) active @endif
                    {{active_menu('admin-home/widgets')}}
                    @if(request()->is('admin-home/widgets/*')) active @endif
                    @if(request()->is('admin-home/popup-builder/*')) active @endif
                    @if(request()->is('admin-home/form-builder/*')) active @endif
                    ">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-settings"></i>
                            <span>{{__('Appearance Settings')}}</span></a>
                        <ul class="collapse ">
                            @if(check_page_permission_by_string('Topbar Settings'))
                                <li class="{{active_menu('admin-home/appearance-settings/topbar-settings')}}">
                                    <a href="{{route('admin.topbar.settings')}}"
                                       aria-expanded="true">
                                        {{__('Topbar Settings')}}
                                    </a>
                                </li>
                            @endif
                            <li class="{{active_menu('admin-home/appearance-settings/navbar-manage')}}">
                                <a href="{{route('admin.general.site.identity')}}">{{__('Navbar Manage')}}</a>
                            </li>
                            @if(check_page_permission_by_string('Home Variant'))
                                <li class="main_dropdown {{active_menu('admin-home/appearance-settings/home-variant')}}">
                                    <a href="{{route('admin.home.variant')}}"
                                       aria-expanded="true">
                                        {{__('Home Variant')}}
                                    </a>
                                </li>
                            @endif
                            @if(check_page_permission_by_string('Menus Manage'))
                                <li
                                        class="main_dropdown
                                        {{active_menu('admin-home/menu')}}
                                        @if(request()->is('admin-home/menu-edit/*')) active @endif
                                        ">
                                    <a href="javascript:void(0)" aria-expanded="true">
                                        {{__('Menus Manage')}}</a>
                                    <ul class="collapse">
                                        <li class="{{active_menu('admin-home/menu')}}"><a
                                                    href="{{route('admin.menu')}}">{{__('All Menus')}}</a></li>
                                    </ul>
                                </li>
                            @endif
                                @if(check_page_permission_by_string('Widgets Manage'))
                                    <li
                                            class="main_dropdown
                                            {{active_menu('admin-home/widgets')}}
                                            @if(request()->is('admin-home/widgets/*')) active @endif
                                                    ">
                                        <a href="javascript:void(0)" aria-expanded="true">
                                            {{__('Widgets Manage')}}</a>
                                        <ul class="collapse">
                                            <li class="{{active_menu('admin-home/widgets')}}"><a
                                                        href="{{route('admin.widgets')}}">{{__('All Widgets')}}</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(check_page_permission_by_string('Popup Builder'))
                                    <li class="main_dropdown @if(request()->is('admin-home/popup-builder/*')) active @endif">
                                        <a href="javascript:void(0)"
                                           aria-expanded="true">
                                            {{__('Popup Builder')}}
                                        </a>
                                        <ul class="collapse">
                                            <li class="{{active_menu('admin-home/popup-builder/all')}}"><a
                                                        href="{{route('admin.popup.builder.all')}}">{{__('All Popup')}}</a></li>
                                            <li class="{{active_menu('admin-home/popup-builder/new')}}"><a
                                                        href="{{route('admin.popup.builder.new')}}">{{__('New Popup')}}</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(check_page_permission_by_string('Form Builder'))
                                    <li class="main_dropdown @if(request()->is('admin-home/form-builder/*')) active @endif">
                                        <a href="javascript:void(0)"
                                           aria-expanded="true">
                                            {{__('Form Builder')}}
                                        </a>
                                        <ul class="collapse">
                                            <li class="{{active_menu('admin-home/form-builder/get-in-touch')}}"><a
                                                        href="{{route('admin.form.builder.get.in.touch')}}">{{__('Get In Touch Form')}}</a></li>
                                            <li class="{{active_menu('admin-home/form-builder/service-query')}}"><a
                                                        href="{{route('admin.form.builder.service.query')}}">{{__('Service Query Form')}}</a></li>
                                            <li class="{{active_menu('admin-home/form-builder/case-study-query')}}"><a
                                                        href="{{route('admin.form.builder.case.study.query')}}">{{__('Case Study Query Form')}}</a></li>
                                            <li class="{{active_menu('admin-home/form-builder/quote-form')}}"><a
                                                        href="{{route('admin.form.builder.quote')}}">{{__('Quote Form')}}</a></li>
                                            <li class="{{active_menu('admin-home/form-builder/order-form')}}"><a
                                                        href="{{route('admin.form.builder.order')}}">{{__('Order Form')}}</a></li>
                                            <li class="{{active_menu('admin-home/form-builder/contact-form')}}"><a
                                                        href="{{route('admin.form.builder.contact')}}">{{__('Contact Form')}}</a></li>
                                            <li class="{{active_menu('admin-home/form-builder/apply-job-form')}}"><a
                                                        href="{{route('admin.form.builder.apply.job.form')}}">{{__('Apply Job Form')}}</a>
                                            </li>
                                            <li class="{{active_menu('admin-home/form-builder/event-attendance')}}"><a
                                                        href="{{route('admin.form.builder.event.attendance.form')}}">{{__('Event Attendance Form')}}</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                        </ul>
                    </li>
                    @if(check_page_permission_by_string('General Settings'))
                    <li class="main_dropdown @if(request()->is('admin-home/general-settings/*')) active @endif">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-settings"></i>
                            <span>{{__('General Settings')}}</span></a>
                        <ul class="collapse ">
                            <li class="{{active_menu('admin-home/general-settings/site-identity')}}"><a
                                        href="{{route('admin.general.site.identity')}}">{{__('Site Identity')}}</a></li>
                            <li class="{{active_menu('admin-home/general-settings/basic-settings')}}"><a
                                        href="{{route('admin.general.basic.settings')}}">{{__('Basic Settings')}}</a>
                            </li>
                            <li class="{{active_menu('admin-home/general-settings/typography-settings')}}"><a
                                        href="{{route('admin.general.typography.settings')}}">{{__('Typography Settings')}}</a>
                            </li>
                            <li class="{{active_menu('admin-home/general-settings/seo-settings')}}"><a
                                        href="{{route('admin.general.seo.settings')}}">{{__('SEO Settings')}}</a></li>
                            <li class="{{active_menu('admin-home/general-settings/scripts')}}"><a
                                        href="{{route('admin.general.scripts.settings')}}">{{__('Third Party Scripts')}}</a>
                            </li>
                            <li class="{{active_menu('admin-home/general-settings/email-template')}}"><a
                                        href="{{route('admin.general.email.template')}}">{{__('Email Template')}}</a>
                            </li>
                            <li class="{{active_menu('admin-home/general-settings/email-settings')}}"><a
                                        href="{{route('admin.general.email.settings')}}">{{__('Email Settings')}}</a>
                            </li>
                            <li class="{{active_menu('admin-home/general-settings/smtp-settings')}}"><a
                                        href="{{route('admin.general.smtp.settings')}}">{{__('SMTP Settings')}}</a>
                            </li>
                            <li class="{{active_menu('admin-home/general-settings/regenerate-image')}}"><a
                                        href="{{route('admin.general.regenerate.thumbnail')}}">{{__('Regenerate Media Image')}}</a>
                            </li>
                            <li class="{{active_menu('admin-home/general-settings/page-settings')}}"><a
                                        href="{{route('admin.general.page.settings')}}">{{__('Page Settings')}}</a></li>
                            @if(!empty(get_static_option('site_payment_gateway')))
                            <li class="{{active_menu('admin-home/general-settings/payment-settings')}}"><a
                                        href="{{route('admin.general.payment.settings')}}">{{__('Payment Gateway Settings')}}</a></li>
                            @endif
                            <li class="{{active_menu('admin-home/general-settings/custom-css')}}"><a
                                        href="{{route('admin.general.custom.css')}}">{{__('Custom CSS')}}</a></li>
                            <li class="{{active_menu('admin-home/general-settings/custom-js')}}"><a
                                        href="{{route('admin.general.custom.js')}}">{{__('Custom JS')}}</a></li>

                            <li class="{{active_menu('admin-home/general-settings/cache-settings')}}"><a
                                        href="{{route('admin.general.cache.settings')}}">{{__('Cache Settings')}}</a>
                            </li>
                            <li class="{{active_menu('admin-home/general-settings/gdpr-settings')}}"><a
                                        href="{{route('admin.general.gdpr.settings')}}">{{__('GDPR Compliant Cookies Settings')}}</a>
                            </li>
                            <li class="{{active_menu('admin-home/general-settings/preloader-settings')}}"><a
                                    href="{{route('admin.general.preloader.settings')}}">{{__('Preloader Settings')}}</a>
                            </li>
                            <li class="{{active_menu('admin-home/general-settings/popup-settings')}}"><a
                                    href="{{route('admin.general.popup.settings')}}">{{__('Popup Settings')}}</a>
                            </li>
                            <li class="{{active_menu('admin-home/general-settings/sitemap-settings')}}"><a
                                    href="{{route('admin.general.sitemap.settings')}}">{{__('Sitemap Settings')}}</a>
                            </li>
                            <li class="{{active_menu('admin-home/general-settings/rss-settings')}}"><a
                                    href="{{route('admin.general.rss.feed.settings')}}">{{__('RSS Feed Settings')}}</a>
                            </li>
                            
                            <li class="{{active_menu('admin-home/general-settings/module-settings')}}"><a
                                    href="{{route('admin.general.module.settings')}}">{{__('Module Settings')}}</a>
                            </li>
                            <li class="{{active_menu('admin-home/general-settings/license-setting')}}"><a
                                        href="{{route('admin.general.license.settings')}}">{{__('Licence Settings')}}</a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    @if(check_page_permission('languages'))
                    <li class="main_dropdown @if(request()->is('admin-home/languages/*') || request()->is('admin-home/languages') ) active @endif">
                        <a href="{{route('admin.languages')}}" aria-expanded="true"><i class="ti-signal"></i>
                            <span>{{__('Languages')}}</span></a>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>
