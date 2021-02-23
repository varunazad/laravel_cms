<!DOCTYPE html>
<html lang="{{$user_select_lang_slug}}"  dir="{{get_user_lang_direction()}}">
<head>
    @if(!empty(get_static_option('site_google_analytics')))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{get_static_option('site_google_analytics')}}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', "{{get_static_option('site_google_analytics')}}");
    </script>
    @endif
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="{{get_static_option('site_meta_'.$user_select_lang_slug.'_description')}}">
    <meta name="tags" content="{{get_static_option('site_meta_'.$user_select_lang_slug.'_tags')}}">
    {!! render_favicon_by_id(get_static_option('site_favicon')) !!}
    <!-- load fonts dynamically -->
    {!! load_google_fonts() !!}


    <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/flaticon.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/responsive.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/jquery.ihavecookies.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/dynamic-style.css')}}">

    @if(request()->path() == '/')
        <meta property="og:title"  content="{{get_static_option('site_'.$user_select_lang_slug.'_title')}}" />
        {!! render_og_meta_image_by_attachment_id(get_static_option('og_meta_image_for_site')) !!}
    @endif

    <style>
        :root {
            --main-color-one: {{get_static_option('site_color')}};
            --main-color-two: {{get_static_option('site_main_color_two')}};
            --portfolio-color: {{get_static_option('portfolio_home_color')}};
            --logistic-color: {{get_static_option('logistics_home_color')}};
            --secondary-color: {{get_static_option('site_secondary_color')}};
            --heading-color: {{get_static_option('site_heading_color')}};
            --paragraph-color: {{get_static_option('site_paragraph_color')}};
            @php $heading_font_family = !empty(get_static_option('heading_font')) ? get_static_option('heading_font_family') :  get_static_option('body_font_family') @endphp
            --heading-font: "{{$heading_font_family}}",sans-serif;
            --body-font:"{{get_static_option('body_font_family')}}",sans-serif;
        }
    </style>
    @yield('style')
    @if(!empty(get_static_option('site_rtl_enabled')) || get_user_lang_direction() == 'rtl')
        <link rel="stylesheet" href="{{asset('assets/frontend/css/rtl.css')}}">
    @endif
    @if(request()->is(get_static_option('blog_page_slug').'/*') || request()->is(get_static_option('work_page_slug').'/*') || request()->is(get_static_option('service_page_slug').'/*'))
    @yield('og-meta')
    <title>@yield('site-title')</title>
    @elseif(request()->is(get_static_option('about_page_slug')) || request()->is(get_static_option('service_page_slug')) || request()->is(get_static_option('work_page_slug')) || request()->is(get_static_option('team_page_slug')) || request()->is(get_static_option('faq_page_slug')) || request()->is(get_static_option('blog_page_slug')) || request()->is(get_static_option('contact_page_slug')) || request()->is('p/*') || request()->is(get_static_option('blog_page_slug').'/*') || request()->is(get_static_option('service_page_slug').'/*') || request()->is(get_static_option('career_with_us_page_slug').'/*') || request()->is(get_static_option('events_page_slug').'/*') || request()->is(get_static_option('knowledgebase_page_slug').'/*'))
    <title>@yield('site-title') - {{get_static_option('site_'.$user_select_lang_slug.'_title')}} </title>
    @else
    <title>{{get_static_option('site_'.$user_select_lang_slug.'_title')}} - {{get_static_option('site_'.$user_select_lang_slug.'_tag_line')}}</title>
    @endif
    <!-- jquery -->
    <script src="{{asset('assets/frontend/js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('assets/frontend/js/jquery-migrate-3.1.0.min.js')}}"></script>
    <script>var siteurl = "{{url('/')}}"</script>
    {!! get_static_option('site_third_party_tracking_code') !!}
</head>
<body class="nexelit_version_{{getenv('XGENIOUS_NEXELIT_VERSION')}} {{get_static_option('item_license_status')}} apps_key_{{get_static_option('site_script_unique_key')}} ">
@include('frontend.partials.preloader')
@include('frontend.partials.search-popup')
@include('frontend.partials.supportbar')
