<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//rss feed route
Route::feeds();


//knowledgebase module
Route::group(['middleware' => ['setlang','globalVariable','knowledgebase_module_check']],function (){
    //
    $knowledgebase_page_slug = !empty(get_static_option('knowledgebase_page_slug')) ? get_static_option('knowledgebase_page_slug') : 'knowledgebase';

    //knowledgebase
    Route::get('/'.$knowledgebase_page_slug,'FrontendController@knowledgebase')->name('frontend.knowledgebase');
    Route::get('/'.$knowledgebase_page_slug.'/{slug}','FrontendController@knowledgebase_single')->name('frontend.knowledgebase.single');
    Route::get('/'.$knowledgebase_page_slug.'-category/{id}/{any}','FrontendController@knowledgebase_category')->name('frontend.knowledgebase.category');
    Route::get('/'.$knowledgebase_page_slug.'-search','FrontendController@knowledgebase_search')->name('frontend.knowledgebase.search');
});

//donation
Route::group(['middleware' => ['setlang','globalVariable','donation_module_check']],function (){

    $donation_page_slug = !empty(get_static_option('donation_page_slug')) ? get_static_option('donation_page_slug') : 'donations';

    //donation page
    Route::get('/'.$donation_page_slug,'FrontendController@donations')->name('frontend.donations');
    Route::get('/'.$donation_page_slug.'/{slug}','FrontendController@donations_single')->name('frontend.donations.single');
    Route::post('/'.$donation_page_slug.'/donation','DonationLogController@store_donation_logs')->name('frontend.donations.log.store');


    //donation
    Route::get('/donation-success/{id}','FrontendController@donation_payment_success')->name('frontend.donation.payment.success');
    Route::get('/donation-cancel/{id}','FrontendController@donation_payment_cancel')->name('frontend.donation.payment.cancel');
    //donation payment ipn
    Route::post('/donation-paypal-ipn','DonationLogController@paypal_ipn')->name('frontend.donation.paypal.ipn');
    Route::post('/donation-paytm-ipn','DonationLogController@paytm_ipn')->name('frontend.donation.paytm.ipn');
    Route::post('/donation-stripe','DonationLogController@stripe_ipn')->name('frontend.donation.stripe.ipn');
    Route::post('/donation-razorpay','DonationLogController@razorpay_ipn')->name('frontend.donation.razorpay.ipn');
    Route::post('/donation-paystack/pay','DonationLogController@paystack_pay')->name('frontend.donation.paystack.pay');
    Route::get('/donation-mollie/webhook','DonationLogController@mollie_webhook')->name('frontend.donation.mollie.webhook');
    Route::post('/donation-flutterwave/pay','DonationLogController@flutterwave_pay')->name('frontend.donation.flutterwave.pay');
    Route::get('/donation-flutterwave/callback','DonationLogController@flutterwave_callback')->name('frontend.donation.flutterwave.callback');

});

//event
Route::group(['middleware' => ['setlang','globalVariable','product_module_check']],function (){

    $product_page_slug = !empty(get_static_option('product_page_slug')) ? get_static_option('product_page_slug') : 'product';

    //product
    Route::get('/'.$product_page_slug,'FrontendController@products')->name('frontend.products');
    Route::get('/'.$product_page_slug.'/{slug}','FrontendController@product_single')->name('frontend.products.single');
    Route::get('/'.$product_page_slug.'-category/{id}/{any}','FrontendController@products_category')->name('frontend.products.category');
    Route::get('/'.$product_page_slug.'-cart','FrontendController@products_cart')->name('frontend.products.cart');
    Route::post('/'.$product_page_slug.'-cart/remove','ProductCartController@remove_cart_item')->name('frontend.products.cart.ajax.remove');
    Route::post('/'.$product_page_slug.'-item/add-to-cart','ProductCartController@add_to_cart')->name('frontend.products.add.to.cart');
    Route::post('/'.$product_page_slug.'-item/ajax/add-to-cart','ProductCartController@ajax_add_to_cart')->name('frontend.products.add.to.cart.ajax');
    Route::post('/'.$product_page_slug.'-item/ajax/coupon','ProductCartController@ajax_coupon_code')->name('frontend.products.coupon.code');
    Route::post('/'.$product_page_slug.'-item/ajax/shipping','ProductCartController@ajax_shipping_apply')->name('frontend.products.shipping.apply');
    Route::post('/'.$product_page_slug.'-item/ajax/cart-update','ProductCartController@ajax_cart_update')->name('frontend.products.ajax.cart.update');
    Route::get('/'.$product_page_slug.'-checkout','FrontendController@product_checkout')->name('frontend.products.checkout');
    Route::post('/'.$product_page_slug.'-checkout','ProductOrderController@product_checkout');
    Route::post('/'.$product_page_slug.'-ratings','FrontendController@product_ratings')->name('product.ratings.store');

    //product order
    Route::get('/'.$product_page_slug.'-success/{id}','FrontendController@product_payment_success')->name('frontend.product.payment.success');
    Route::get('/'.$product_page_slug.'-cancel/{id}','FrontendController@product_payment_cancel')->name('frontend.product.payment.cancel');


    //product payment ipn
    Route::post('/product-paypal-ipn','ProductOrderController@paypal_ipn')->name('frontend.product.paypal.ipn');
    Route::post('/product-paytm-ipn','ProductOrderController@paytm_ipn')->name('frontend.product.paytm.ipn');
    Route::post('/product-stripe','ProductOrderController@stripe_ipn')->name('frontend.product.stripe.ipn');
    Route::post('/product-razorpay','ProductOrderController@razorpay_ipn')->name('frontend.product.razorpay.ipn');
    Route::post('/product-paystack/pay','ProductOrderController@paystack_pay')->name('frontend.product.paystack.pay');
    Route::post('/product-flullterwave/pay','ProductOrderController@flutterwave_pay')->name('frontend.product.flutterwave.pay');
    Route::get('/product-flullterwave/callback','ProductOrderController@flutterwave_callback')->name('frontend.product.flutterwave.callback');
    Route::get('/product-mollie/webhook','ProductOrderController@mollie_webhook')->name('frontend.product.mollie.webhook');

});

Route::group(['middleware' => ['setlang','globalVariable','event_module_check']],function (){

    $events_page_slug = !empty(get_static_option('events_page_slug')) ? get_static_option('events_page_slug') : 'events';

    //events
    Route::get('/'.$events_page_slug,'FrontendController@events')->name('frontend.events');
    Route::get('/'.$events_page_slug.'/{slug}','FrontendController@events_single')->name('frontend.events.single');
    Route::get('/'.$events_page_slug.'-category/{id}/{any}','FrontendController@events_category')->name('frontend.events.category');
    Route::get('/'.$events_page_slug.'-search','FrontendController@events_search')->name('frontend.events.search');
    Route::get('/'.$events_page_slug.'-booking/{id}','FrontendController@event_booking')->name('frontend.event.booking');
    Route::post('/'.$events_page_slug.'-booking','FrontendFormController@store_event_booking_data')->name('frontend.event.booking.store');

    //event payment ipn
    Route::post('/event-paypal-ipn','EventPaymentLogsController@paypal_ipn')->name('frontend.event.paypal.ipn');
    Route::post('/event-paytm-ipn','EventPaymentLogsController@paytm_ipn')->name('frontend.event.paytm.ipn');
    Route::post('/event-stripe','EventPaymentLogsController@stripe_ipn')->name('frontend.event.stripe.ipn');
    Route::post('/event-razorpay','EventPaymentLogsController@razorpay_ipn')->name('frontend.event.razorpay.ipn');
    Route::post('/event-paystack/pay','EventPaymentLogsController@paystack_pay')->name('frontend.event.paystack.pay');
    Route::post('/event-flullterwave/pay','EventPaymentLogsController@flutterwave_pay')->name('frontend.event.flutterwave.pay');
    Route::get('/event-flullterwave/callback','EventPaymentLogsController@flutterwave_callback')->name('frontend.event.flutterwave.callback');
    Route::get('/event-mollie/webhook','EventPaymentLogsController@mollie_webhook')->name('frontend.event.mollie.webhook');

    //event booking
    Route::get('/booking-confirm/{id}','FrontendController@booking_confirm')->name('frontend.event.booking.confirm');
    Route::post('/booking-confirm','EventPaymentLogsController@booking_payment_form')->name('frontend.event.payment.confirm');
    Route::get('/attendance-success/{id}','FrontendController@event_payment_success')->name('frontend.event.payment.success');
    Route::get('/attendance-cancel/{id}','FrontendController@event_payment_cancel')->name('frontend.event.payment.cancel');
});

Route::group(['middleware' => ['setlang','globalVariable','job_module_check']],function (){
    //job post
    $career_with_us_page_slug = !empty(get_static_option('career_with_us_page_slug')) ? get_static_option('career_with_us_page_slug') : 'jobs';
    Route::get('/'.$career_with_us_page_slug,'FrontendController@jobs')->name('frontend.jobs');
    Route::get('/'.$career_with_us_page_slug.'/{slug}','FrontendController@jobs_single')->name('frontend.jobs.single');
    Route::get('/'.$career_with_us_page_slug.'-category/{id}/{any}','FrontendController@jobs_category')->name('frontend.jobs.category');
    Route::get('/'.$career_with_us_page_slug.'-search','FrontendController@jobs_search')->name('frontend.jobs.search');
    Route::get('/apply/{id}','FrontendController@jobs_apply')->name('frontend.jobs.apply');
    Route::post('/apply','FrontendFormController@store_jobs_applicant_data')->name('frontend.jobs.apply.store');
});

Route::group(['middleware' => ['setlang','globalVariable']],function (){

    Route::get('/', 'FrontendController@index')->name('homepage');
    Route::get('/p/{any}','FrontendController@dynamic_single_page')->name('frontend.dynamic.page');
    Route::post('/get-touch','FrontendFormController@get_touch')->name('frontend.get.touch');
    Route::post('/service-quote','FrontendFormController@service_quote')->name('frontend.service.quote');
    Route::post('/case-study-quote','FrontendFormController@case_study_quote')->name('frontend.case.study.quote');



    //payment status route
    Route::get('/order-success/{id}','FrontendController@order_payment_success')->name('frontend.order.payment.success');
    Route::get('/order-cancel/{id}','FrontendController@order_payment_cancel')->name('frontend.order.payment.cancel');
    Route::get('/order-confirm/{id}','FrontendController@order_confirm')->name('frontend.order.confirm');
    Route::post('/order-confirm','PaymentLogController@order_payment_form')->name('frontend.order.payment.form');

    //ipn route
    Route::post('/paypal-ipn','PaymentLogController@paypal_ipn')->name('frontend.paypal.ipn');
    Route::post('/paytm-ipn','PaymentLogController@paytm_ipn')->name('frontend.paytm.ipn');
    Route::post('/stripe','PaymentLogController@stripe_ipn')->name('frontend.stripe.ipn');
    Route::post('/razorpay','PaymentLogController@razorpay_ipn')->name('frontend.razorpay.ipn');
    Route::post('/paystack/pay','PaymentLogController@paystack_pay')->name('frontend.paystack.pay');
    Route::get('/paystack/callback','PaymentLogController@paystack_callback')->name('frontend.paystack.callback');
    Route::post('/flutterwave/pay','PaymentLogController@flutterwave_pay')->name('frontend.flutterwave.pay');
    Route::get('/flutterwave/callback','PaymentLogController@flutterwave_callback')->name('frontend.flutterwave.callback');
    Route::get('/mollie/callback','PaymentLogController@mollie_webhook')->name('frontend.mollie.webhook');


    //product invoice for user
    Route::post('/products-user/generate-invoice','FrontendController@generate_invoice')->name('frontend.product.invoice.generate');
    Route::post('/donation-user/generate-invoice','FrontendController@generate_donation_invoice')->name('frontend.donation.invoice.generate');
    Route::post('/event-user/generate-invoice','FrontendController@generate_event_invoice')->name('frontend.event.invoice.generate');
    Route::post('/package-user/generate-invoice','FrontendController@generate_package_invoice')->name('frontend.package.invoice.generate');


    //static page
    $about_page_slug = !empty(get_static_option('about_page_slug')) ? get_static_option('about_page_slug') : 'about';
    $service_page_slug = !empty(get_static_option('service_page_slug')) ? get_static_option('service_page_slug') : 'service';
    $work_page_slug = !empty(get_static_option('work_page_slug')) ? get_static_option('work_page_slug') : 'work';
    $faq_page_slug = !empty(get_static_option('faq_page_slug')) ? get_static_option('faq_page_slug') : 'faq';
    $team_page_slug = !empty(get_static_option('team_page_slug')) ? get_static_option('team_page_slug') : 'team';
    $price_plan_page_slug = !empty(get_static_option('price_plan_page_slug')) ? get_static_option('price_plan_page_slug') : 'price-plan';
    $contact_page_slug = !empty(get_static_option('contact_page_slug')) ? get_static_option('contact_page_slug') : 'contact';
    $blog_page_slug = !empty(get_static_option('blog_page_slug')) ? get_static_option('blog_page_slug') : 'blog';

    $quote_page_slug = !empty(get_static_option('quote_page_slug')) ? get_static_option('quote_page_slug') : 'request-quote';


    $testimonial_page_slug = !empty(get_static_option('testimonial_page_slug')) ? get_static_option('testimonial_page_slug') : 'testimonials';
    $feedback_page_slug = !empty(get_static_option('feedback_page_slug')) ? get_static_option('feedback_page_slug') : 'feedback';
    $clients_feedback_page_slug = !empty(get_static_option('clients_feedback_page_slug')) ? get_static_option('clients_feedback_page_slug') : 'clients-feedback';
    $image_gallery_page_slug = !empty(get_static_option('image_gallery_page_slug')) ? get_static_option('image_gallery_page_slug') : 'image-gallery';
    $donor_page_slug = !empty(get_static_option('donor_page_slug')) ? get_static_option('donor_page_slug') : 'donor-list';


    Route::get('/'.$donor_page_slug,'FrontendController@donor_list')->name('frontend.donor.list');
    Route::get('/'.$about_page_slug,'FrontendController@about_page')->name('frontend.about');
    Route::get('/'.$service_page_slug,'FrontendController@service_page')->name('frontend.service');
    Route::get('/'.$work_page_slug,'FrontendController@work_page')->name('frontend.work');
    Route::get('/'.$faq_page_slug,'FrontendController@faq_page')->name('frontend.faq');
    Route::get('/'.$service_page_slug.'/category/{id}/{any}','FrontendController@category_wise_services_page')->name('frontend.services.category');
    Route::get('/'.$service_page_slug.'/{slug}','FrontendController@services_single_page')->name('frontend.services.single');
    Route::get('/'.$work_page_slug.'/{slug}','FrontendController@work_single_page')->name('frontend.work.single');
    Route::get('/'.$work_page_slug.'/category/{id}/{any}','FrontendController@category_wise_works_page')->name('frontend.works.category');
    Route::get('/'.$team_page_slug,'FrontendController@team_page')->name('frontend.team');
    Route::get('/'.$price_plan_page_slug,'FrontendController@price_plan_page')->name('frontend.price.plan');
    Route::get('/'.$contact_page_slug,'FrontendController@contact_page')->name('frontend.contact');

    //blog
    Route::get('/'.$blog_page_slug.'/{slug}','FrontendController@blog_single_page')->name('frontend.blog.single');
    Route::get('/'.$blog_page_slug.'-search','FrontendController@blog_search_page')->name('frontend.blog.search');
    Route::get('/'.$blog_page_slug.'-category/{id}/{any}','FrontendController@category_wise_blog_page')->name('frontend.blog.category');
    Route::get('/'.$blog_page_slug.'-tags/{name}','FrontendController@tags_wise_blog_page')->name('frontend.blog.tags.page');
    Route::get('/'.$blog_page_slug,'FrontendController@blog_page')->name('frontend.blog');

    //quote page
    Route::get('/'.$quote_page_slug,'FrontendController@request_quote')->name('frontend.request.quote');


    //testimonials
    Route::get('/'.$testimonial_page_slug,'FrontendController@testimonials')->name('frontend.testimonials');
    Route::get('/'.$feedback_page_slug,'FrontendController@feedback_page')->name('frontend.feedback');
    Route::get('/'.$clients_feedback_page_slug,'FrontendController@clients_feedback_page')->name('frontend.clients.feedback');
    Route::post('/'.$clients_feedback_page_slug.'/submit','FrontendFormController@clients_feedback_store')->name('frontend.clients.feedback.store');
    //image gallery
    Route::get('/'.$image_gallery_page_slug.'','FrontendController@image_gallery_page')->name('frontend.image.gallery');

    Route::get('/'.$price_plan_page_slug.'/{id}','FrontendController@plan_order')->name('frontend.plan.order');

    //user login
    Route::get('/login','Auth\LoginController@showLoginForm')->name('user.login');
    Route::post('/ajax-login','FrontendController@ajax_login')->name('user.ajax.login');
    Route::post('/login','Auth\LoginController@login');
    Route::get('/register','Auth\RegisterController@showRegistrationForm')->name('user.register');
    Route::post('/register','Auth\RegisterController@register');
    Route::get('/login/forget-password','FrontendController@showUserForgetPasswordForm')->name('user.forget.password');
    Route::get('/login/reset-password/{user}/{token}','FrontendController@showUserResetPasswordForm')->name('user.reset.password');
    Route::post('/login/reset-password','FrontendController@UserResetPassword')->name('user.reset.password.change');
    Route::post('/login/forget-password','FrontendController@sendUserForgetPasswordMail');
    Route::post('/logout','Auth\LoginController@logout')->name('user.logout');
    //user email verify
    Route::get('/user/email-verify','UserDashboardController@user_email_verify_index')->name('user.email.verify');
    Route::get('/user/resend-verify-code','UserDashboardController@reset_user_email_verify_code')->name('user.resend.verify.mail');
    Route::post('/user/email-verify','UserDashboardController@user_email_verify');

    Route::post('/request-quote','FrontendFormController@send_quote_message')->name('frontend.quote.message');
    Route::get('/home/{id}','FrontendController@home_page_change');
});

//admin login
Route::get('/login/admin','Auth\LoginController@showAdminLoginForm')->name('admin.login');
Route::get('/login/admin/forget-password','FrontendController@showAdminForgetPasswordForm')->name('admin.forget.password');
Route::get('/login/admin/reset-password/{user}/{token}','FrontendController@showAdminResetPasswordForm')->name('admin.reset.password');
Route::post('/login/admin/reset-password','FrontendController@AdminResetPassword')->name('admin.reset.password.change');
Route::post('/login/admin/forget-password','FrontendController@sendAdminForgetPasswordMail');
Route::post('/logout/admin','AdminDashboardController@adminLogout')->name('admin.logout');
Route::post('/login/admin','Auth\LoginController@adminLogin');

//language change
Route::get('/lang','FrontendController@lang_change')->name('frontend.langchange');

Route::post('/subscribe-newsletter','FrontendController@subscribe_newsletter')->name('frontend.subscribe.newsletter');
Route::post('/contact-message','FrontendFormController@send_contact_message')->name('frontend.contact.message');
Route::post('/place-order','FrontendFormController@send_order_message')->name('frontend.order.message');

//user dashboard
Route::prefix('user-home')->middleware(['userEmailVerify','setlang','globalVariable'])->group(function (){
    Route::get('/', 'UserDashboardController@user_index')->name('user.home');
    Route::get('/download/file/{id}', 'UserDashboardController@download_file')->name('user.dashboard.download.file');

    Route::post('/profile-update','UserDashboardController@user_profile_update')->name('user.profile.update');
    Route::post('/password-change','UserDashboardController@user_password_change')->name('user.password.change');

});

Route::prefix('admin-home')->group(function (){

    Route::get('/', 'AdminDashboardController@adminIndex')->name('admin.home');

    // maintains page
    Route::get('/maintains-page/settings','MaintainsPageController@maintains_page_settings')->name('admin.maintains.page.settings');
    Route::post('/maintains-page/settings','MaintainsPageController@update_maintains_page_settings');

    //admin settings
    Route::get('/settings','AdminDashboardController@admin_settings')->name('admin.profile.settings');
    Route::get('/profile-update','AdminDashboardController@admin_profile')->name('admin.profile.update');
    Route::post('/profile-update','AdminDashboardController@admin_profile_update');
    Route::get('/password-change','AdminDashboardController@admin_password')->name('admin.password.change');
    Route::post('/password-change','AdminDashboardController@admin_password_chagne');
    Route::post('/set-static-option','AdminDashboardController@admin_set_static_option');
    Route::post('/get-static-option','AdminDashboardController@admin_get_static_option');
    Route::post('/update-static-option','AdminDashboardController@admin_update_static_option');
});

//products routes
Route::prefix('admin-home')->middleware(['products_manage_check','product_module_check'])->group(function (){
    //products
    Route::get('/products','ProductsController@all_product')->name('admin.products.all');
    Route::get('/products/new','ProductsController@new_product')->name('admin.products.new');
    Route::post('/products/new','ProductsController@store_product');
    Route::get('/products/edit/{id}','ProductsController@edit_product')->name('admin.products.edit');
    Route::post('/products/update','ProductsController@update_product')->name('admin.products.update');
    Route::post('/products/delete/{id}','ProductsController@delete_product')->name('admin.products.delete');
    Route::post('/products/clone','ProductsController@clone_product')->name('admin.products.clone');
    Route::post('/products/bulk-action','ProductsController@bulk_action')->name('admin.products.bulk.action');
    Route::get('/products/file/download/{id}','ProductsController@download_file')->name('admin.products.file.download');
    //product ratings
    Route::get('/products/product-ratings','ProductsController@product_ratings')->name('admin.products.ratings');
    Route::post('/products/product-ratings/delete/{id}','ProductsController@product_ratings_delete')->name('admin.products.ratings.delete');
    Route::post('/products/product-ratings/bulk-action','ProductsController@product_ratings_bulk_action')->name('admin.products.ratings.bulk.action');

    //orders
    Route::get('/products/product-order-logs','ProductsController@product_order_logs')->name('admin.products.order.logs');
    Route::post('/products/product-order-logs/approve/{id}','ProductsController@product_order_payment_approve')->name('admin.products.order.payment.approve');
    Route::post('/products/product-order-logs/delete/{id}','ProductsController@product_order_delete')->name('admin.product.payment.delete');
    Route::post('/products/product-order-logs/status-change','ProductsController@product_order_status_change')->name('admin.product.order.status.change');
    Route::post('/products/product-order-logs/bulk-actoin','ProductsController@product_order_bulk_action')->name('admin.product.order.bulk.action');
    Route::post('/products/generate-invoice','ProductsController@generate_invoice')->name('admin.product.invoice.generate');

    //products  page settings
    Route::get('/products/page-settings','ProductsController@page_settings')->name('admin.products.page.settings');
    Route::post('/products/page-settings','ProductsController@update_page_settings');
    Route::get('/products/single-page-settings','ProductsController@single_page_settings')->name('admin.products.single.page.settings');
    Route::post('/products/single-page-settings','ProductsController@update_single_page_settings');

    Route::get('/products/success-page-settings','ProductsController@success_page_settings')->name('admin.products.success.page.settings');
    Route::post('/products/success-page-settings','ProductsController@update_success_page_settings');
    Route::get('/products/cancel-page-settings','ProductsController@cancel_page_settings')->name('admin.products.cancel.page.settings');
    Route::post('/products/cancel-page-settings','ProductsController@update_cancel_page_settings');

    Route::get('/products/order-report','ProductsController@order_report')->name('admin.products.order.report');

    //products category
    Route::get('/products/category','ProductCategoryController@all_product_category')->name('admin.products.category.all');
    Route::post('/products/category/new','ProductCategoryController@store_product_category')->name('admin.products.category.new');
    Route::post('/products/category/update','ProductCategoryController@update_product_category')->name('admin.products.category.update');
    Route::post('/products/category/delete/{id}','ProductCategoryController@delete_product_category')->name('admin.products.category.delete');
    Route::post('/products/category/lang','ProductCategoryController@category_by_language_slug')->name('admin.products.category.by.lang');
    Route::post('/products/category/bulk-action','ProductCategoryController@bulk_action')->name('admin.products.category.bulk.action');
    //coupon
    Route::get('/products/coupon','ProductCouponController@all_coupon')->name('admin.products.coupon.all');
    Route::post('/products/coupon/new','ProductCouponController@store_coupon')->name('admin.products.coupon.new');
    Route::post('/products/coupon/update','ProductCouponController@update_coupon')->name('admin.products.coupon.update');
    Route::post('/products/coupon/delete/{id}','ProductCouponController@delete_coupon')->name('admin.products.coupon.delete');
    Route::post('/products/coupon/bulk-action','ProductCouponController@bulk_action')->name('admin.products.coupon.bulk.action');
    //shipping
    Route::get('/products/shipping','ProductShippingController@all_shipping')->name('admin.products.shipping.all');
    Route::post('/products/shipping/new','ProductShippingController@store_all_shipping')->name('admin.products.shipping.new');
    Route::post('/products/shipping/update','ProductShippingController@update_shipping')->name('admin.products.shipping.update');
    Route::post('/products/shipping/delete/{id}','ProductShippingController@delete_shipping')->name('admin.products.shipping.delete');
    Route::post('/products/shipping/default/{id}','ProductShippingController@default_shipping')->name('admin.products.shipping.default');
    Route::post('/products/shipping/bulk-action','ProductShippingController@bulk_action')->name('admin.products.shipping.bulk.action');

});


//knowledgebase routes
Route::prefix('admin-home')->middleware(['knowledgebase_manage_check','knowledgebase_module_check'])->group(function (){

    Route::get('/knowledge','KnowledgebaseController@all_knowledgebases')->name('admin.knowledge.all');
    Route::get('/knowledge/new','KnowledgebaseController@new_knowledgebase')->name('admin.knowledge.new');
    Route::post('/knowledge/new','KnowledgebaseController@store_knowledgebases');
    Route::get('/knowledge/edit/{id}','KnowledgebaseController@edit_knowledgebases')->name('admin.knowledge.edit');
    Route::post('/knowledge/update','KnowledgebaseController@update_knowledgebases')->name('admin.knowledge.update');
    Route::post('/knowledge/delete/{id}','KnowledgebaseController@delete_knowledgebases')->name('admin.knowledge.delete');
    Route::post('/knowledge/clone','KnowledgebaseController@clone_knowledgebases')->name('admin.knowledge.clone');
    Route::post('/knowledge/bulk-action','KnowledgebaseController@bulk_action')->name('admin.knowledge.bulk.action');

    //knowledge base page settings
    Route::get('/knowledge/page-settings','KnowledgebaseController@page_settings')->name('admin.knowledge.page.settings');
    Route::post('/knowledge/page-settings','KnowledgebaseController@update_page_settings');

    //knowledge base category
    Route::get('/knowledge/category','KnowledgebaseTopicsController@all_knowledgebase_category')->name('admin.knowledge.category.all');
    Route::post('/knowledge/category/new','KnowledgebaseTopicsController@store_knowledgebase_category')->name('admin.knowledge.category.new');
    Route::post('/knowledge/category/update','KnowledgebaseTopicsController@update_knowledgebase_category')->name('admin.knowledge.category.update');
    Route::post('/knowledge/category/delete/{id}','KnowledgebaseTopicsController@delete_knowledgebase_category')->name('admin.knowledge.category.delete');
    Route::post('/knowledge/category/lang','KnowledgebaseTopicsController@category_by_language_slug')->name('admin.knowledge.category.by.lang');
    Route::post('/knowledge/category/bulk-action','KnowledgebaseTopicsController@bulk_action')->name('admin.knowledge.category.bulk.action');

});

//job post routes
Route::prefix('admin-home')->middleware(['job_post_manage_check','job_module_check'])->group(function (){

    Route::get('/jobs','JobsController@all_jobs')->name('admin.jobs.all');
    Route::get('/jobs/new','JobsController@new_job')->name('admin.jobs.new');
    Route::post('/jobs/new','JobsController@store_job');
    Route::get('/jobs/edit/{id}','JobsController@edit_job')->name('admin.jobs.edit');
    Route::post('/jobs/update','JobsController@update_job')->name('admin.jobs.update');
    Route::post('/jobs/delete/{id}','JobsController@delete_job')->name('admin.jobs.delete');
    Route::post('/jobs/clone','JobsController@clone_job')->name('admin.jobs.clone');
    Route::post('/jobs/bulk-action','JobsController@bulk_action')->name('admin.jobs.bulk.action');

    //job page settings
    Route::get('/jobs/page-settings','JobsController@page_settings')->name('admin.jobs.page.settings');
    Route::post('/jobs/page-settings','JobsController@update_page_settings');
    Route::get('/jobs/single-page-settings','JobsController@single_page_settings')->name('admin.jobs.single.page.settings');
    Route::post('/jobs/single-page-settings','JobsController@update_single_page_settings');

    //job category
    Route::get('/jobs/category','JobsCategoryController@all_jobs_category')->name('admin.jobs.category.all');
    Route::post('/jobs/category/new','JobsCategoryController@store_jobs_category')->name('admin.jobs.category.new');
    Route::post('/jobs/category/update','JobsCategoryController@update_jobs_category')->name('admin.jobs.category.update');
    Route::post('/jobs/category/delete/{id}','JobsCategoryController@delete_jobs_category')->name('admin.jobs.category.delete');
    Route::post('/jobs/category/bulk-action','JobsCategoryController@bulk_action')->name('admin.jobs.category.bulk.action');
    Route::post('/jobs/category/lang','JobsCategoryController@Language_by_slug')->name('admin.jobs.category.by.lang');

    //job applicant
    Route::get('/jobs/applicant','JobsController@all_jobs_applicant')->name('admin.jobs.applicant');
    Route::post('/jobs/applicant/delete/{id}','JobsController@delete_job_applicant')->name('admin.jobs.applicant.delete');
    Route::post('/jobs/applicant/bulk-delete','JobsController@job_applicant_bulk_delete')->name('admin.jobs.applicant.bulk.delete');
    Route::get('/jobs/applicant/report','JobsController@job_applicant_report')->name('admin.jobs.applicant.report');
});

//services
Route::prefix('admin-home')->middleware(['services_manage_check'])->group(function (){
    //services
    Route::get('/services','ServiceController@index')->name('admin.services');
    Route::get('/services/new','ServiceController@new_service')->name('admin.services.new');
    Route::get('/services/edit/{id}','ServiceController@edit_service')->name('admin.services.edit');
    Route::post('/services','ServiceController@store');
    Route::post('/services-cat-by-slug','ServiceController@category_by_slug')->name('admin.service.category.by.slug');
    Route::post('/update-services','ServiceController@update')->name('admin.services.update');
    Route::post('/clone-services','ServiceController@clone_service_as_draft')->name('admin.services.clone');
    Route::post('/services/bulk-action','ServiceController@bulk_action')->name('admin.services.bulk.action');

    Route::post('/delete-services/{id}','ServiceController@delete')->name('admin.services.delete');
    Route::get('/services/category','ServiceController@category_index')->name('admin.service.category');
    Route::post('/services/category','ServiceController@category_store');
    Route::post('/update-services-category','ServiceController@category_update')->name('admin.service.category.update');
    Route::post('/delete-services-category/{id}','ServiceController@category_delete')->name('admin.service.category.delete');
    Route::post('/services-category/bulk-action','ServiceController@category_bulk_action')->name('admin.service.category.bulk.action');

    //service page
    Route::get('/services/page-settings','ServicePageController@service_page_settings')->name('admin.services.page.settings');
    Route::post('/services/page-settings','ServicePageController@update_service_page_settings');
    //service single page
    Route::get('/services/single-page-settings','ServicePageController@service_single_page_settings')->name('admin.services.single.page.settings');
    Route::post('/services/single-page-settings','ServicePageController@update_service_single_page_settings');
});

//top bar settings
Route::prefix('admin-home')->middleware(['topbar_settings_check'])->group(function (){
    //topbar
    Route::get('/topbar-settings',"TopBarController@topbar_settings")->name('admin.topbar.settings');
    Route::post('/topbar-settings',"TopBarController@update_topbar_settings");
    Route::post('/topbar/new-social-item','TopBarController@new_social_item')->name('admin.new.social.item');
    Route::post('/topbar/update-social-item','TopBarController@update_social_item')->name('admin.update.social.item');
    Route::post('/topbar/delete-social-item/{id}','TopBarController@delete_social_item')->name('admin.delete.social.item');
});

//home variant
Route::prefix('admin-home')->middleware(['home_variant_manage_check'])->group(function (){
    //home page variant select
    Route::get('/home-variant',"AdminDashboardController@home_variant")->name('admin.home.variant');
    Route::post('/home-variant',"AdminDashboardController@update_home_variant");
});

//home page manage
Route::prefix('admin-home')->middleware(['home_page_manage_check'])->group(function (){
    //home page one
    Route::get('/home-page-01/brand-logos','HomePageController@home_01_brand_logos_area')->name('admin.homeone.brand.logos');
    Route::post('/home-page-01/brand-logos','HomePageController@home_01_update_brand_logos_area');
    Route::get('/home-page-01/latest-news','HomePageController@home_01_latest_news')->name('admin.homeone.latest.news');
    Route::post('/home-page-01/latest-news','HomePageController@home_01_update_latest_news');
    Route::get('/home-page-01/testimonial','HomePageController@home_01_testimonial')->name('admin.homeone.testimonial');
    Route::post('/home-page-01/testimonial','HomePageController@home_01_update_testimonial');
    Route::get('/home-page-01/service-area','HomePageController@home_01_service_area')->name('admin.homeone.service.area');
    Route::post('/home-page-01/service-area','HomePageController@home_01_update_service_area');
    Route::get('/home-page-01/case-study-area','HomePageController@home_01_case_study_area')->name('admin.homeone.case.study.area');
    Route::post('/home-page-01/case-study-area','HomePageController@home_01_update_case_study_area');
    Route::get('/home-page-01/about-us','HomePageController@home_01_about_us')->name('admin.homeone.about.us');
    Route::post('/home-page-01/about-us','HomePageController@home_01_update_about_us');

    Route::get('/home-page-01/cta-area','HomePageController@home_01_cta_area')->name('admin.homeone.cta.area');
    Route::post('/home-page-01/cta-area','HomePageController@home_01_update_cta_area');
    Route::get('/home-page-01/section-manage','HomePageController@home_01_section_manage')->name('admin.homeone.section.manage');
    Route::post('/home-page-01/section-manage','HomePageController@home_01_update_section_manage');
    Route::get('/home-page-01/price-plan','HomePageController@home_01_price_plan')->name('admin.homeone.price.plan');
    Route::post('/home-page-01/price-plan','HomePageController@home_01_update_price_plan');
    Route::get('/home-page-01/team-member','HomePageController@home_01_team_member')->name('admin.homeone.team.member');
    Route::post('/home-page-01/team-member','HomePageController@home_01_update_team_member');
    Route::get('/home-page-01/contact-area','HomePageController@home_01_contact_area')->name('admin.homeone.contact.area');
    Route::post('/home-page-01/contact-area','HomePageController@home_01_update_contact_area');

    Route::get('/home-page-01/quality-area','HomePageController@home_01_quality_area')->name('admin.homeone.quality.area');
    Route::post('/home-page-01/quality-area','HomePageController@home_01_update_quality_area');
    //key features
    Route::get('/keyfeatures','KeyFeaturesController@index')->name('admin.keyfeatures');
    Route::post('/keyfeatures','KeyFeaturesController@store');
    Route::post('/home-page-01/keyfeatures','KeyFeaturesController@update_section_settings')->name('admin.keyfeature.section');
    Route::post('/update-keyfeatures','KeyFeaturesController@update')->name('admin.keyfeatures.update');
    Route::post('/delete-keyfeatures/{id}','KeyFeaturesController@delete')->name('admin.keyfeatures.delete');
    Route::post('/keyfeatures/bulk-action','KeyFeaturesController@bulk_action')->name('admin.keyfeatures.bulk.action');

    //header slider
    Route::get('/header','HeaderSliderController@index')->name('admin.header');
    Route::post('/header','HeaderSliderController@store');
    Route::post('/update-header','HeaderSliderController@update')->name('admin.header.update');
    Route::post('/delete-header/{id}','HeaderSliderController@delete')->name('admin.header.delete');
    Route::post('/header/bulk-action/','HeaderSliderController@bulk_action')->name('admin.header.bulk.action');


});

//order manage route
Route::prefix('admin-home/package')->middleware(['package_order_manage_check'])->group(function (){
    Route::get('/order-manage/all','OrderManageController@all_orders')->name('admin.package.order.manage.all');
    Route::get('/order-manage/pending','OrderManageController@pending_orders')->name('admin.package.order.manage.pending');
    Route::get('/order-manage/completed','OrderManageController@completed_orders')->name('admin.package.order.manage.completed');
    Route::get('/order-manage/in-progress','OrderManageController@in_progress_orders')->name('admin.package.order.manage.in.progress');

    Route::post('/order-manage/change-status','OrderManageController@change_status')->name('admin.package.order.manage.change.status');
    Route::post('/order-manage/send-mail','OrderManageController@send_mail')->name('admin.package.order.manage.send.mail');
    Route::post('/order-manage/delete/{id}','OrderManageController@order_delete')->name('admin.package.order.manage.delete');

    //thank you page
    Route::get('/order-manage/success-page','OrderManageController@order_success_payment')->name('admin.package.order.success.page');
    Route::post('/order-manage/success-page','OrderManageController@update_order_success_payment');
    //cancel page
    Route::get('/order-manage/cancel-page','OrderManageController@order_cancel_payment')->name('admin.package.order.cancel.page');
    Route::post('/order-manage/cancel-page','OrderManageController@update_order_cancel_payment');
    Route::get('/order-page','OrderPageController@index')->name('admin.package.order.page');
    Route::post('/order-page','OrderPageController@udpate');
    Route::post('/order-manage/bulk-action','OrderManageController@bulk_action')->name('admin.package.order.bulk.action');

    Route::get('/order-report','OrderManageController@order_report')->name('admin.package.order.report');

});

//payment log route
Route::prefix('admin-home')->middleware(['payment_logs_check'])->group(function (){
    Route::get('/payment-logs','OrderManageController@all_payment_logs')->name('admin.payment.logs');
    Route::post('/payment-logs/delete/{id}','OrderManageController@payment_logs_delete')->name('admin.payment.delete');
    Route::post('/payment-logs/approve/{id}','OrderManageController@payment_logs_approve')->name('admin.payment.approve');
    Route::post('/payment-logs/bulk-action','OrderManageController@payment_log_bulk_action')->name('admin.payment.bulk.action');
    Route::get('/payment-logs/report','OrderManageController@payment_report')->name('admin.payment.report');

});

//about us page manage
Route::prefix('admin-home')->middleware(['about_page_manage_check'])->group(function (){
    //about page
    Route::get('/about-page/about-us','AboutPageController@about_page_about_section')->name('admin.about.page.about');
    Route::post('/about-page/about-us','AboutPageController@about_page_update_about_section');
    //global network
    Route::get('/about-page/global-network','AboutPageController@about_page_global_network_section')->name('admin.about.global.network');
    Route::post('/about-page/global-network','AboutPageController@about_page_update_global_network_section');
    //experience
    Route::get('/about-page/experience','AboutPageController@about_page_experience_section')->name('admin.about.experience');
    Route::post('/about-page/experience','AboutPageController@about_page_update_experience_section');
    //team member
    Route::get('/about-page/team-member','AboutPageController@about_page_team_member_section')->name('admin.about.team.member');
    Route::post('/about-page/team-member','AboutPageController@about_page_update_team_member_section');
    //testimonial
    Route::get('/about-page/testimonial','AboutPageController@about_page_testimonial_section')->name('admin.about.testimonial');
    Route::post('/about-page/testimonial','AboutPageController@about_page_update_testimonial_section');

    Route::get('/about-page/section-manage','AboutPageController@about_page_section_manage')->name('admin.about.page.section.manage');
    Route::post('/about-page/section-manage','AboutPageController@about_page_update_section_manage');
});
//preloader builder manage
Route::prefix('admin-home')->middleware(['popup_builder_check'])->group(function (){
    //popup page
    Route::get('/popup-builder/all','PopupBuilderController@all_popup')->name('admin.popup.builder.all');
    Route::get('/popup-builder/new','PopupBuilderController@new_popup')->name('admin.popup.builder.new');
    Route::post('/popup-builder/new','PopupBuilderController@store_popup');
    Route::get('/popup-builder/edit/{id}','PopupBuilderController@edit_popup')->name('admin.popup.builder.edit');
    Route::post('/popup-builder/update/{id}','PopupBuilderController@update_popup')->name('admin.popup.builder.update');
    Route::post('/popup-builder/delete/{id}','PopupBuilderController@delete_popup')->name('admin.popup.builder.delete');
    Route::post('/popup-builder/clone/{id}','PopupBuilderController@clone_popup')->name('admin.popup.builder.clone');
    Route::post('/popup-builder/bulk-action','PopupBuilderController@bulk_action')->name('admin.popup.builder.bulk.action');

});


//feedback page manage
Route::prefix('admin-home')->middleware(['feedback_page_manage_check'])->group(function (){
    //feedback page
    Route::get('/feedback-page/page-settings','FeedbackController@page_settings')->name('admin.feedback.page.settings');
    Route::post('/feedback-page/page-settings','FeedbackController@update_page_settings');
    //form builder
    Route::get('/feedback-page/form-builder','FeedbackController@form_builder')->name('admin.feedback.page.form.builder');
    Route::post('/feedback-page/form-builder','FeedbackController@update_form_builder');
    //all feedback
    Route::get('/feedback-page/all-feedback','FeedbackController@all_feedback')->name('admin.feedback.all');
    Route::post('/feedback-page/all-feedback/delete/{id}','FeedbackController@delete_feedback')->name('admin.feedback.delete');
    Route::post('/feedback-page/all-feedback/bulk-action','FeedbackController@bulk_action')->name('admin.feedback.bulk.action');
});

//image gallery page manage
Route::prefix('admin-home')->middleware(['gallery_page_check'])->group(function (){
    //image gallery page
    Route::get('/gallery-page','ImageGalleryPageController@index')->name('admin.gallery.all');
    Route::post('/gallery-page/new','ImageGalleryPageController@store')->name('admin.gallery.new');
    Route::post('gallery-page/update','ImageGalleryPageController@update')->name('admin.gallery.update');
    Route::post('gallery-page/delete/{id}','ImageGalleryPageController@delete')->name('admin.gallery.delete');
    Route::post('gallery-page/bulk-action','ImageGalleryPageController@bulk_action')->name('admin.gallery.bulk.action');
});

//contact page manage
Route::prefix('admin-home')->middleware(['contact_page_manage_check'])->group(function (){
    //contact page
    Route::get('/contact-page/form-area','ContactPageController@contact_page_form_area')->name('admin.contact.page.form.area');
    Route::post('/contact-page/form-area','ContactPageController@contact_page_update_form_area');
    Route::get('/contact-page/map','ContactPageController@contact_page_map_area')->name('admin.contact.page.map');
    Route::post('/contact-page/map','ContactPageController@contact_page_update_map_area');

    Route::get('/contact-page/section-manage','ContactPageController@contact_page_section_manage')->name('admin.contact.page.section.manage');
    Route::post('/contact-page/section-manage','ContactPageController@contact_page_update_section_manage');

    //contact info
    Route::get('/contact-page/contact-info','ContactInfoController@index')->name('admin.contact.info');
    Route::post('/contact-page/contact-info','ContactInfoController@store');
    Route::post('/contact-page/contact-info/title','ContactInfoController@contact_info_title')->name('admin.contact.info.title');
    Route::post('contact-page/contact-info/update','ContactInfoController@update')->name('admin.contact.info.update');
    Route::post('contact-page/contact-info/delete/{id}','ContactInfoController@delete')->name('admin.contact.info.delete');
    Route::post('contact-page/contact-info/bulk-action','ContactInfoController@bulk_action')->name('admin.contact.info.bulk.action');
});

//team member
Route::prefix('admin-home')->middleware(['team_member_manage_check'])->group(function (){
    //team member
    Route::get('/team-member','TeamMemberController@index')->name('admin.team.member');
    Route::post('/team-member','TeamMemberController@store');
    Route::post('/update-team-member','TeamMemberController@update')->name('admin.team.member.update');
    Route::post('/delete-team-member/{id}','TeamMemberController@delete')->name('admin.team.member.delete');
    Route::post('/team-member/bulk-action','TeamMemberController@bulk_action')->name('admin.team.member.bulk.action');
});


//form builder
Route::prefix('admin-home')->middleware(['form_builder_check'])->group(function (){
    //form builder routes
    Route::get('/form-builder/get-in-touch','FormBuilderController@get_in_touch_form_index')->name('admin.form.builder.get.in.touch');
    Route::post('/form-builder/get-in-touch','FormBuilderController@update_get_in_touch_form');
    //service query routes
    Route::get('/form-builder/service-query','FormBuilderController@service_query_index')->name('admin.form.builder.service.query');
    Route::post('/form-builder/service-query','FormBuilderController@update_service_query');
    //case study query routes
    Route::get('/form-builder/case-study-query','FormBuilderController@case_study_query_index')->name('admin.form.builder.case.study.query');
    Route::post('/form-builder/case-study-query','FormBuilderController@update_case_study_query');

    Route::get('/form-builder/quote-form','FormBuilderController@quote_form_index')->name('admin.form.builder.quote');
    Route::post('/form-builder/quote-form','FormBuilderController@update_quote_form');
    Route::get('/form-builder/order-form','FormBuilderController@order_form_index')->name('admin.form.builder.order');
    Route::post('/form-builder/order-form','FormBuilderController@update_order_form');
    Route::get('/form-builder/contact-form','FormBuilderController@contact_form_index')->name('admin.form.builder.contact');
    Route::post('/form-builder/contact-form','FormBuilderController@update_contact_form');

    Route::get('/form-builder/apply-job-form','FormBuilderController@apply_job_form_index')->name('admin.form.builder.apply.job.form');
    Route::post('/form-builder/apply-job-form','FormBuilderController@update_apply_job_form');

    //event attendance
    Route::get('/form-builder/event-attendance','FormBuilderController@event_attendance_form_index')->name('admin.form.builder.event.attendance.form');
    Route::post('/form-builder/event-attendance','FormBuilderController@update_event_attedance_form');
});
// manage route
Route::prefix('admin-home')->middleware(['quote_manage_check'])->group(function (){
    Route::get('/quote-manage/all','QuoteManageController@all_quotes')->name('admin.quote.manage.all');
    Route::get('/quote-manage/pending','QuoteManageController@pending_quotes')->name('admin.quote.manage.pending');
    Route::get('/quote-manage/completed','QuoteManageController@completed_quotes')->name('admin.quote.manage.completed');
    Route::post('/quote-manage/change-status','QuoteManageController@change_status')->name('admin.quote.manage.change.status');
    Route::post('/quote-manage/send-mail','QuoteManageController@send_mail')->name('admin.quote.manage.send.mail');
    Route::post('/quote-manage/delete/{id}','QuoteManageController@quote_delete')->name('admin.quote.manage.delete');
    //quote page
    Route::get('/quote-manage/quote-page','QuoteManageController@quote_page_index')->name('admin.quote.page');
    Route::post('/quote-manage/quote-page','QuoteManageController@quote_page_udpate');
    Route::post('/quote-manage/bulk-action','QuoteManageController@bulk_action')->name('admin.quote.bulk.action');
});


//counterup
Route::prefix('admin-home')->middleware(['counterup_manage_check'])->group(function (){
    Route::get('/counterup','CounterUpController@index')->name('admin.counterup');
    Route::post('/counterup','CounterUpController@store');
    Route::post('/update-counterup','CounterUpController@update')->name('admin.counterup.update');
    Route::post('/delete-counterup/{id}','CounterUpController@delete')->name('admin.counterup.delete');
    Route::post('/counterup/bulk-action','CounterUpController@bulk_action')->name('admin.counterup.bulk.action');
});

//newsletter manage
Route::prefix('admin-home')->middleware(['newsletter_manage_check'])->group(function (){
    //newsletter
    Route::get('/newsletter','NewsletterController@index')->name('admin.newsletter');
    Route::post('/newsletter/delete/{id}','NewsletterController@delete')->name('admin.newsletter.delete');
    Route::post('/newsletter/single','NewsletterController@send_mail')->name('admin.newsletter.single.mail');
    Route::get('/newsletter/all','NewsletterController@send_mail_all_index')->name('admin.newsletter.mail');
    Route::post('/newsletter/all','NewsletterController@send_mail_all');
    Route::post('/newsletter/new','NewsletterController@add_new_sub')->name('admin.newsletter.new.add');
});

//languages
Route::prefix('admin-home')->middleware(['language_check'])->group(function (){
    //language
    Route::get('/languages','LanguageController@index')->name('admin.languages');
    Route::get('/languages/words/edit/{id}','LanguageController@edit_words')->name('admin.languages.words.edit');
    Route::post('/languages/words/new','LanguageController@add_new_words')->name('admin.languages.add.new.word');
    Route::post('/languages/words/update/{id}','LanguageController@update_words')->name('admin.languages.words.update');
    Route::post('/languages/new','LanguageController@store')->name('admin.languages.new');
    Route::post('/languages/update','LanguageController@update')->name('admin.languages.update');
    Route::post('/languages/delete/{id}','LanguageController@delete')->name('admin.languages.delete');
    Route::post('/languages/default/{id}','LanguageController@make_default')->name('admin.languages.default');
});

/* media upload routes */
Route::prefix('admin-home')->group(function (){
    Route::post('/media-upload/all','MediaUploadController@all_upload_media_file')->name('admin.upload.media.file.all');
    Route::post('/media-upload','MediaUploadController@upload_media_file')->name('admin.upload.media.file');
});
Route::prefix('admin-home')->group(function (){
    Route::post('/media-upload/delete','MediaUploadController@delete_upload_media_file')->name('admin.upload.media.file.delete');
});
/* media upload routes end */

//brad logos
Route::prefix('admin-home')->middleware(['brand_logos_manage_check'])->group(function (){
    //brand logos
    Route::get('/brands','BrandController@index')->name('admin.brands');
    Route::post('/brands','BrandController@store');
    Route::post('/update-brands','BrandController@update')->name('admin.brands.update');
    Route::post('/delete-brands/{id}','BrandController@delete')->name('admin.brands.delete');
    Route::post('/brands/bulk-action','BrandController@bulk_action')->name('admin.brands.bulk.action');
});
//blogs
Route::prefix('admin-home')->middleware(['blogs_manage_check'])->group(function (){
    //blog
    Route::get('/blog','BlogController@index')->name('admin.blog');
    Route::get('/blog/new','BlogController@new_blog')->name('admin.blog.new');
    Route::post('/blog/new','BlogController@store_new_blog');
    Route::post('/blog/clone','BlogController@clone_blog')->name('admin.blog.clone');
    Route::get('/blog/edit/{id}','BlogController@edit_blog')->name('admin.blog.edit');
    Route::post('/blog/update/{id}','BlogController@update_blog')->name('admin.blog.update');
    Route::post('/blog/delete/{id}','BlogController@delete_blog')->name('admin.blog.delete');
    Route::get('/blog/category','BlogController@category')->name('admin.blog.category');
    Route::post('/blog/category','BlogController@new_category');
    Route::post('/blog/category/delete/{id}','BlogController@delete_category')->name('admin.blog.category.delete');
    Route::post('/blog/category/update','BlogController@update_category')->name('admin.blog.category.update');
    Route::post('/blog/category/bulk-action','BlogController@category_bulk_action')->name('admin.blog.category.bulk.action');
    Route::post('/blog-lang-by-cat','BlogController@Language_by_slug')->name('admin.blog.lang.cat');
    //blog page

    Route::get('/blog/page-settings','BlogController@blog_page_settings')->name('admin.blog.page.settings');
    Route::post('/blog/page-settings','BlogController@update_blog_page_settings');
    //blog single page
    Route::get('/blog/single-settings','BlogController@blog_single_page_settings')->name('admin.blog.single.settings');
    Route::post('/blog/single-settings','BlogController@update_blog_single_page_settings');
    //bulk action
    Route::post('/blog/bulk-action','BlogController@bulk_action')->name('admin.blog.bulk.action');

});
//pages
Route::prefix('admin-home')->middleware(['pages_manage_check'])->group(function (){
    //pages
    Route::get('/page','PagesController@index')->name('admin.page');
    Route::get('/new-page','PagesController@new_page')->name('admin.page.new');
    Route::post('/new-page','PagesController@store_new_page');
    Route::get('/page-edit/{id}','PagesController@edit_page')->name('admin.page.edit');
    Route::post('/page-update/{id}','PagesController@update_page')->name('admin.page.update');
    Route::post('/page-delete/{id}','PagesController@delete_page')->name('admin.page.delete');
    Route::post('/page/bulk-action','PagesController@bulk_action')->name('admin.page.bulk.action');
});

//404 page manage
Route::prefix('admin-home')->middleware(['error_404_manage_check'])->group(function (){
    // work single page
    Route::get('404-page-manage','Error404PageManage@error_404_page_settings')->name('admin.404.page.settings');
    Route::post('404-page-manage','Error404PageManage@update_error_404_page_settings');
});

//price plan
Route::prefix('admin-home')->middleware(['price_plan_manage_check'])->group(function (){
    //price plan
    Route::get('/price-plan','PricePlanController@index')->name('admin.price.plan');
    Route::get('/price-plan/new','PricePlanController@new')->name('admin.price.plan.new');
    Route::post('/price-plan','PricePlanController@store');
    Route::post('/price-plan/clone','PricePlanController@clone')->name('admin.price.plan.clone');
    Route::post('/update-price-plan','PricePlanController@update')->name('admin.price.plan.update');
    Route::post('/delete-price-plan/{id}','PricePlanController@delete')->name('admin.price.plan.delete');
    Route::post('/price-plan/bulk-action','PricePlanController@bulk_action')->name('admin.price.plan.bulk.action');

    Route::post('/price-plan-lang-by-cat','PricePlanController@Language_by_slug')->name('admin.price.plan.lang.cat');
    //price plan category
    Route::get('/price-plan/category','PricePlanController@category_index')->name('admin.price.plan.category');
    Route::post('/price-plan/category','PricePlanController@category_store');
    Route::post('/update-price-plan-category','PricePlanController@category_update')->name('admin.price.plan.category.update');
    Route::post('/delete-price-plan-category/{id}','PricePlanController@category_delete')->name('admin.price.plan.category.delete');
    Route::post('/price-plan-category/bulk-action','PricePlanController@category_bulk_action')->name('admin.price.plan.category.bulk.action');

});
//faq
Route::prefix('admin-home')->middleware(['faq_manage_check'])->group(function (){
    //faq
    Route::get('/faq','FaqController@index')->name('admin.faq');
    Route::post('/faq','FaqController@store');
    Route::post('/update-faq','FaqController@update')->name('admin.faq.update');
    Route::post('/delete-faq/{id}','FaqController@delete')->name('admin.faq.delete');
    Route::post('/clone-faq','FaqController@clone')->name('admin.faq.clone');
    Route::post('/faq/bulk-action','FaqController@bulk_action')->name('admin.faq.bulk.action');
});

//testimonial
Route::prefix('admin-home')->middleware(['testimonial_manage_check'])->group(function (){
    //testimonial
    Route::get('/testimonial','TestimonialController@index')->name('admin.testimonial');
    Route::post('/testimonial','TestimonialController@store');
    Route::post('/testimonial/clone','TestimonialController@clone')->name('admin.testimonial.clone');
    Route::post('/update-testimonial','TestimonialController@update')->name('admin.testimonial.update');
    Route::post('/delete-testimonial/{id}','TestimonialController@delete')->name('admin.testimonial.delete');
    Route::post('/testimonial/bulk-action','TestimonialController@bulk_action')->name('admin.testimonial.bulk.action');
});

//events routes
Route::prefix('admin-home')->middleware(['events_manage_check','event_module_check'])->group(function (){

    Route::get('/events','EventsController@all_events')->name('admin.events.all');
    Route::get('/events/new','EventsController@new_event')->name('admin.events.new');
    Route::post('/events/new','EventsController@store_event');
    Route::get('/events/edit/{id}','EventsController@edit_event')->name('admin.events.edit');
    Route::post('/events/update','EventsController@update_event')->name('admin.events.update');
    Route::post('/events/delete/{id}','EventsController@delete_event')->name('admin.events.delete');
    Route::post('/events/clone','EventsController@clone_event')->name('admin.events.clone');
    Route::post('/events/bulk-action','EventsController@bulk_action')->name('admin.events.bulk.action');

    //event page settings
    Route::get('/events/page-settings','EventsController@page_settings')->name('admin.events.page.settings');
    Route::post('/events/page-settings','EventsController@update_page_settings');
    //payment success
    Route::get('/events/payment-success-page-settings','EventsController@payment_success_page_settings')->name('admin.events.payment.success.page.settings');
    Route::post('/events/payment-success-page-settings','EventsController@update_payment_success_page_settings');
    //payment cancel
    Route::get('/events/payment-cancel-pag-settings','EventsController@payment_cancel_page_settings')->name('admin.events.payment.cancel.page.settings');
    Route::post('/events/payment-cancel-pag-settings','EventsController@update_payment_cancel_page_settings');

    //event single page settings
    Route::get('/events/single-page-settings','EventsController@single_page_settings')->name('admin.events.single.page.settings');
    Route::post('/events/single-page-settings','EventsController@update_single_page_settings');
    Route::get('/events/attendance','EventsController@event_attendance')->name('admin.events.attendance');
    Route::post('/events/attendance','EventsController@update_event_attendance');
    //event attendance logs
    Route::get('/events/event-attendance-logs','EventsController@event_attendance_logs')->name('admin.event.attendance.logs');
    Route::post('/events/event-attendance-logs','EventsController@update_event_attendance_logs_status');
    Route::post('/events/event-attendance-logs/delete/{id}','EventsController@delete_event_attendance_logs')->name('admin.event.attendance.logs.delete');
    Route::post('/events/event-attendance-logs/send-mail','EventsController@send_mail_event_attendance_logs')->name('admin.event.attendance.send.mail');
    Route::post('/events/event-attendance-logs/bulk-action','EventsController@attendance_logs_bulk_action')->name('admin.event.attendance.bulk.action');
    //event payment logs
    Route::get('/events/event-payment-logs','EventsController@event_payment_logs')->name('admin.event.payment.logs');
    Route::post('/events/event-payment-logs/delete/{id}','EventsController@delete_event_payment_logs')->name('admin.event.payment.delete');
    Route::post('/events/event-payment-logs/approve/{id}','EventsController@approve_event_payment')->name('admin.event.payment.approve');
    Route::post('/events/event-payment-logs/bulk-action','EventsController@payment_logs_bulk_action')->name('admin.event.payment.bulk.action');

    Route::get('/events/payment/report','EventsController@payment_report')->name('admin.event.payment.report');
    Route::get('/events/attendance/report','EventsController@attendance_report')->name('admin.event.attendance.report');

    //event category
    Route::get('/events/category','EventsCategoryController@all_events_category')->name('admin.events.category.all');
    Route::post('/events/category/new','EventsCategoryController@store_events_category')->name('admin.events.category.new');
    Route::post('/events/category/update','EventsCategoryController@update_events_category')->name('admin.events.category.update');
    Route::post('/events/category/delete/{id}','EventsCategoryController@delete_events_category')->name('admin.events.category.delete');
    Route::post('/events/category/lang','EventsCategoryController@Category_by_language_slug')->name('admin.events.category.by.lang');
    Route::post('/events/category/bulk-action','EventsCategoryController@bulk_action')->name('admin.events.category.bulk.action');

});

//donation routes
Route::prefix('admin-home')->middleware(['donations_manage_check','donation_module_check'])->group(function (){

    Route::get('/donations','DonationController@all_donation')->name('admin.donations.all');
    Route::get('/donations/new','DonationController@new_donation')->name('admin.donations.new');
    Route::post('/donations/new','DonationController@store_donation');
    Route::get('/donations/edit/{id}','DonationController@edit_donation')->name('admin.donations.edit');
    Route::post('/donations/update','DonationController@update_donation')->name('admin.donations.update');
    Route::post('/donations/delete/{id}','DonationController@delete_donation')->name('admin.donations.delete');
    Route::post('/donations/clone','DonationController@clone_donation')->name('admin.donations.clone');
    Route::post('/donations/bulk-action','DonationController@bulk_action')->name('admin.donations.bulk.action');

    //donation page settings
    Route::get('/donations/page-settings','DonationController@page_settings')->name('admin.donations.page.settings');
    Route::post('/donations/page-settings','DonationController@update_page_settings');
    //donation single page settings
    Route::get('/donations/single-page-settings','DonationController@single_page_settings')->name('admin.donations.single.page.settings');
    Route::post('/donations/single-page-settings','DonationController@update_single_page_settings');
    //payment success
    Route::get('/donations/payment-success-page-settings','DonationController@payment_success_page_settings')->name('admin.donations.payment.success.page.settings');
    Route::post('/donations/payment-success-page-settings','DonationController@update_payment_success_page_settings');
    //payment cancel
    Route::get('/donations/payment-cancel-pag-settings','DonationController@payment_cancel_page_settings')->name('admin.donations.payment.cancel.page.settings');
    Route::post('/donations/payment-cancel-pag-settings','DonationController@update_payment_cancel_page_settings');
    //report generate
    Route::get('/donations/report','DonationController@donation_report')->name('admin.donations.report');

    //donation payment logs
    Route::get('/donations/donations-payment-logs','DonationController@event_payment_logs')->name('admin.donations.payment.logs');
    Route::post('/donations/donations-payment-logs/delete/{id}','DonationController@delete_event_payment_logs')->name('admin.donations.payment.delete');
    Route::post('/donations/donations-payment-logs/approve/{id}','DonationController@approve_event_payment')->name('admin.donations.payment.approve');
    Route::post('/donations/donations-payment-logs/bulk-action','DonationController@donation_payment_logs_bulk_action')->name('admin.donations.payment.bulk.action');

});

Route::prefix('admin-home')->middleware(['case_study_manage_check'])->group(function (){
    //works
    Route::get('/works','WorksController@index')->name('admin.work');
    Route::get('/works/new','WorksController@new')->name('admin.work.new');
    Route::get('/works/edit/{id}','WorksController@edit')->name('admin.work.edit');
    Route::post('/works','WorksController@store');
    Route::post('/update-works','WorksController@update')->name('admin.work.update');
    Route::post('/clone-works','WorksController@clone_new_draft')->name('admin.work.clone');
    Route::post('/works/bulk-action','WorksController@bulk_action')->name('admin.work.bulk.action');

    Route::post('/delete-works/{id}','WorksController@delete')->name('admin.work.delete');
    Route::post('/works-cat-by-slug','WorksController@category_by_slug')->name('admin.work.category.by.slug');

    Route::get('/works/category','WorksController@category_index')->name('admin.work.category');
    Route::post('/works/category','WorksController@category_store');
    Route::post('/update-works-category','WorksController@category_update')->name('admin.work.category.update');
    Route::post('/delete-works-category/{id}','WorksController@category_delete')->name('admin.work.category.delete');
    Route::post('/works-category/bulk-action','WorksController@category_bulk_action')->name('admin.work.category.bulk.action');
    //work page
    Route::get('/works/single-page/settings','WorkSinglePageController@work_single_page_settings')->name('admin.work.single.page.settings');
    Route::post('works/single-page/settings','WorkSinglePageController@update_work_single_page_settings');
    //
    Route::get('/works/page/settings','WorkSinglePageController@work_page_settings')->name('admin.work.page.settings');
    Route::post('works/page/settings','WorkSinglePageController@update_work_page_settings');
});

//widget manage
Route::prefix('admin-home')->middleware(['widgets_manage_check'])->group(function (){
    //widger manage
    Route::get('/widgets','WidgetsController@index')->name('admin.widgets');
    Route::post('/widgets/create','WidgetsController@new_widget')->name('admin.widgets.new');
    Route::post('/widgets/markup','WidgetsController@widget_markup')->name('admin.widgets.markup');
    Route::post('/widgets/update','WidgetsController@update_widget')->name('admin.widgets.update');
    Route::post('/widgets/update/order','WidgetsController@update_order_widget')->name('admin.widgets.update.order');
    Route::post('/widgets/delete','WidgetsController@delete_widget')->name('admin.widgets.delete');
});

//menu manage
Route::prefix('admin-home')->middleware(['menus_manage_check'])->group(function (){
    //menu manage
    Route::get('/menu','MenuController@index')->name('admin.menu');
    Route::post('/new-menu','MenuController@store_new_menu')->name('admin.menu.new');
    Route::get('/menu-edit/{id}','MenuController@edit_menu')->name('admin.menu.edit');
    Route::post('/menu-update/{id}','MenuController@update_menu')->name('admin.menu.update');
    Route::post('/menu-delete/{id}','MenuController@delete_menu')->name('admin.menu.delete');
    Route::post('/menu-default/{id}','MenuController@set_default_menu')->name('admin.menu.default');
    Route::post('/mega-menu','MenuController@mega_menu_item_select_markup')->name('admin.mega.menu.item.select.markup');
});

//frontend user manage
Route::prefix('admin-home')->middleware(['users_manage_check'])->group(function (){
    //user role management
    Route::get('/frontend/new-user','FrontendUserManageController@new_user')->name('admin.frontend.new.user');
    Route::post('/frontend/new-user','FrontendUserManageController@new_user_add');
    Route::post('/frontend/user-update','FrontendUserManageController@user_update')->name('admin.frontend.user.update');
    Route::post('/frontend/user-password-chnage','FrontendUserManageController@user_password_change')->name('admin.frontend.user.password.change');
    Route::post('/frontend/delete-user/{id}','FrontendUserManageController@new_user_delete')->name('admin.frontend.delete.user');
    Route::get('/frontend/all-user','FrontendUserManageController@all_user')->name('admin.all.frontend.user');

});



//admin role manage
Route::prefix('admin-home')->middleware(['admin_manage_check'])->group(function (){
    //user role management
    Route::get('/new-user','UserRoleManageController@new_user')->name('admin.new.user');
    Route::post('/new-user','UserRoleManageController@new_user_add');
    Route::post('/user-update','UserRoleManageController@user_update')->name('admin.user.update');
    Route::post('/user-password-chnage','UserRoleManageController@user_password_change')->name('admin.user.password.change');
    Route::post('/delete-user/{id}','UserRoleManageController@new_user_delete')->name('admin.delete.user');
    Route::get('/all-user','UserRoleManageController@all_user')->name('admin.all.user');
    Route::get('/all-user/role','UserRoleManageController@all_user_role')->name('admin.all.user.role');
    Route::post('/all-user/role','UserRoleManageController@add_new_user_role');
    Route::post('/all-user/role/update','UserRoleManageController@udpate_user_role')->name('admin.user.role.edit');
    Route::post('/all-user/role/delete/{id}','UserRoleManageController@delete_user_role')->name('admin.user.role.delete');
});

//general settings
Route::prefix('admin-home')->middleware(['general_settings_check'])->group(function (){
    //general settings
    Route::get('/general-settings/site-identity','GeneralSettingsController@site_identity')->name('admin.general.site.identity');
    Route::post('/general-settings/site-identity','GeneralSettingsController@update_site_identity');
    Route::get('/general-settings/basic-settings','GeneralSettingsController@basic_settings')->name('admin.general.basic.settings');
    Route::post('/general-settings/basic-settings','GeneralSettingsController@update_basic_settings');
    Route::get('/general-settings/seo-settings','GeneralSettingsController@seo_settings')->name('admin.general.seo.settings');
    Route::post('/general-settings/seo-settings','GeneralSettingsController@update_seo_settings');
    Route::get('/general-settings/scripts','GeneralSettingsController@scripts_settings')->name('admin.general.scripts.settings');
    Route::post('/general-settings/scripts','GeneralSettingsController@update_scripts_settings');
    Route::get('/general-settings/email-template','GeneralSettingsController@email_template_settings')->name('admin.general.email.template');
    Route::post('/general-settings/email-template','GeneralSettingsController@update_email_template_settings');
    Route::get('/general-settings/email-settings','GeneralSettingsController@email_settings')->name('admin.general.email.settings');
    Route::post('/general-settings/email-settings','GeneralSettingsController@update_email_settings');
    Route::get('/general-settings/typography-settings','GeneralSettingsController@typography_settings')->name('admin.general.typography.settings');
    Route::post('/general-settings/typography-settings','GeneralSettingsController@update_typography_settings');
    Route::post('/general-settings/typography-settings/single','GeneralSettingsController@get_single_font_variant')->name('admin.general.typography.single');
    Route::get('/general-settings/cache-settings','GeneralSettingsController@cache_settings')->name('admin.general.cache.settings');
    Route::post('/general-settings/cache-settings','GeneralSettingsController@update_cache_settings');
    Route::get('/general-settings/page-settings','GeneralSettingsController@page_settings')->name('admin.general.page.settings');
    Route::post('/general-settings/page-settings','GeneralSettingsController@update_page_settings');
    Route::get('/general-settings/backup-settings','GeneralSettingsController@backup_settings')->name('admin.general.backup.settings');
    Route::post('/general-settings/backup-settings','GeneralSettingsController@update_backup_settings');
    Route::post('/general-settings/backup-settings/delete','GeneralSettingsController@delete_backup_settings')->name('admin.general.backup.settings.delete');
    Route::post('/general-settings/backup-settings/restore','GeneralSettingsController@restore_backup_settings')->name('admin.general.backup.settings.restore');
    Route::get('/general-settings/update-system','GeneralSettingsController@update_system')->name('admin.general.update.system');
    Route::post('/general-settings/update-system','GeneralSettingsController@update_system_version');
    Route::get('/general-settings/license-setting','GeneralSettingsController@license_settings')->name('admin.general.license.settings');
    Route::post('/general-settings/license-setting','GeneralSettingsController@update_license_settings');
    Route::get('/general-settings/custom-css','GeneralSettingsController@custom_css_settings')->name('admin.general.custom.css');
    Route::post('/general-settings/custom-css','GeneralSettingsController@update_custom_css_settings');
    Route::get('/general-settings/gdpr-settings','GeneralSettingsController@gdpr_settings')->name('admin.general.gdpr.settings');
    Route::post('/general-settings/gdpr-settings','GeneralSettingsController@update_gdpr_cookie_settings');

    //update script
    Route::get('/general-settings/update-script','ScriptUpdateController@index')->name('admin.general.script.update');
    Route::post('/general-settings/update-script','ScriptUpdateController@update_script');

    //custom js
    Route::get('/general-settings/custom-js','GeneralSettingsController@custom_js_settings')->name('admin.general.custom.js');
    Route::post('/general-settings/custom-js','GeneralSettingsController@update_custom_js_settings');

    //regenerate media image
    Route::get('/general-settings/regenerate-image','GeneralSettingsController@regenerate_image_settings')->name('admin.general.regenerate.thumbnail');
    Route::post('/general-settings/regenerate-image','GeneralSettingsController@update_regenerate_image_settings');

    //smtp settings
    Route::get('/general-settings/smtp-settings','GeneralSettingsController@smtp_settings')->name('admin.general.smtp.settings');
    Route::post('/general-settings/smtp-settings','GeneralSettingsController@update_smtp_settings');

    //payment gateway
    Route::get('/general-settings/payment-settings','GeneralSettingsController@payment_settings')->name('admin.general.payment.settings');
    Route::post('/general-settings/payment-settings','GeneralSettingsController@update_payment_settings');

    //preloader
    Route::get('/general-settings/preloader-settings','GeneralSettingsController@preloader_settings')->name('admin.general.preloader.settings');
    Route::post('/general-settings/preloader-settings','GeneralSettingsController@update_preloader_settings');
    //preloader
    Route::get('/general-settings/popup-settings','GeneralSettingsController@popup_settings')->name('admin.general.popup.settings');
    Route::post('/general-settings/popup-settings','GeneralSettingsController@update_popup_settings');

    //rss feed
    Route::get('/general-settings/rss-settings','GeneralSettingsController@rss_feed_settings')->name('admin.general.rss.feed.settings');
    Route::post('/general-settings/rss-settings','GeneralSettingsController@update_rss_feed_settings');

    //Module Settings
    Route::get('/general-settings/module-settings','GeneralSettingsController@module_settings')->name('admin.general.module.settings');
    Route::post('/general-settings/module-settings','GeneralSettingsController@store_module_settings');

    //update script
    Route::get('/general-settings/update-script','GeneralSettingsController@update_script_settings')->name('admin.general.update.script.settings');
    Route::post('/general-settings/update-script','GeneralSettingsController@sote_update_script_settings');

    //sitemap
    Route::get('/general-settings/sitemap-settings','GeneralSettingsController@sitemap_settings')->name('admin.general.sitemap.settings');
    Route::post('/general-settings/sitemap-settings','GeneralSettingsController@update_sitemap_settings');
    Route::post('/general-settings/sitemap-settings/delete','GeneralSettingsController@delete_sitemap_settings')->name('admin.general.sitemap.settings.delete');

});
