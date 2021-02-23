@php
    $home_page_variant = isset($home_page) ? $home_page : get_static_option('home_page_variant');
@endphp
<div class="header-style-02  header-variant-{{$home_page_variant}}">
    <nav class="navbar navbar-area nav-absolute navbar-expand-lg nav-style-01">
        <div class="container nav-container">
            <div class="responsive-mobile-menu">
                <div class="logo-wrapper">
                    <a href="{{url('/')}}" class="logo">
                        @if(!empty(get_static_option('site_white_logo')))
                            {!! render_image_markup_by_attachment_id(get_static_option('site_white_logo')) !!}
                        @else
                            <h2 class="site-title">{{get_static_option('site_'.$user_select_lang_slug.'_title')}}</h2>
                        @endif
                    </a>
                </div>
                @if(!empty(get_static_option('product_module_status')))
                    <div class="mobile-cart"><a href="{{route('frontend.products.cart')}}"><i
                                    class="flaticon-shopping-cart"></i> <span
                                    class="pcount">{{cart_total_items()}}</span></a></div>
                @endif
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bizcoxx_main_menu"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bizcoxx_main_menu">
                <ul class="navbar-nav">
                    {!! render_menu_by_id($primary_menu) !!}
                </ul>
            </div>
            <div class="nav-right-content">
                <div class="icon-part">
                    <ul>
                        <li id="search"><a href="#"><i class="flaticon-search-1"></i></a></li>
                        @if(!empty(get_static_option('product_module_status')))
                            <li class="cart"><a href="{{route('frontend.products.cart')}}"><i
                                            class="flaticon-shopping-cart"></i> <span
                                            class="pcount">{{cart_total_items()}}</span></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>

<div class="portfolio-home-header-area">
    <div class="shape-01 shape">
        <img src="{{asset('assets/frontend/img/shape/01.png')}}" alt="">
    </div>
    <div class="shape-02 shape">
        <img src="{{asset('assets/frontend/img/shape/02.png')}}" alt="">
    </div>
    <div class="shape-03 shape">
        <img src="{{asset('assets/frontend/img/shape/03.png')}}" alt="">
    </div>
    <div class="shape-04 shape">
        <img src="{{asset('assets/frontend/img/shape/04.png')}}" alt="">
    </div>
    <div class="shape-05 shape">
        <img src="{{asset('assets/frontend/img/shape/05.png')}}" alt="">
    </div>
    <div class="right-image">
        {!! render_image_markup_by_attachment_id(get_static_option('portfolio_home_page_right_image')) !!}
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="header-inner">
                    <span class="subtitle">{{get_static_option('portfolio_home_page_'.$user_select_lang_slug.'_subtitle')}}</span>
                    <h1 class="title">{{get_static_option('portfolio_home_page_'.$user_select_lang_slug.'_title')}}</h1>
                    <h6 class="profession">{{get_static_option('portfolio_home_page_'.$user_select_lang_slug.'_profession')}}</h6>
                    <div class="description">{!! get_static_option('portfolio_home_page_'.$user_select_lang_slug.'_description') !!}</div>
                    <div class="btn-wrapper margin-top-40">
                        <a href="{{get_static_option('portfolio_home_page_button_url')}}"
                           class="portfolio-btn">{{get_static_option('portfolio_home_page_'.$user_select_lang_slug.'_button_text')}}
                            <i class="fas fa-download"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="about-and-coutnerup-area dark-section-bg-two">
    @if(!empty(get_static_option('home_page_counterup_section_status')))
    <div class="counterup-area padding-bottom-120 padding-top-120">
        <div class="container">
            <div class="row">
                @foreach($all_counterup as $data)
                    <div class="col-lg-3 col-md-6">
                        <div class="single-counterup-03">
                            <div class="number">{{$data->number}}{{$data->extra_text}}</div>
                            <h4 class="title">{{$data->title}}</h4>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    @if(!empty(get_static_option('home_page_about_us_section_status')))
    <div class="portfolio-about-us-section padding-bottom-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="left-content-area">
                        <div class="img-wrapper">
                            <div class="shape-06">
                                <img src="{{asset('assets/frontend/img/shape/06.png')}}" alt="">
                            </div>
                            {!! render_image_markup_by_attachment_id(get_static_option('portfolio_about_section_left_image')) !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="right-content-area">
                        <span class="subtitle">{{get_static_option('portfolio_about_section_'.$user_select_lang_slug.'_subtitle')}}</span>
                        <h3 class="title">{{get_static_option('portfolio_about_section_'.$user_select_lang_slug.'_title')}}</h3>
                        <div class="description">
                            {!! get_static_option('portfolio_about_section_'.$user_select_lang_slug.'_description') !!}
                        </div>
                        <ul class="about-info-list">
                            @php
                                $all_icon_fields =  get_static_option('home_page_05_about_section_icon_box_icon');
                                $all_icon_fields = !empty($all_icon_fields) ? unserialize($all_icon_fields) : [''];
                                $all_title_fields = get_static_option('home_page_05_'.$user_select_lang_slug.'_about_section_icon_box_title');
                                $all_title_fields = !empty($all_title_fields) ? unserialize($all_title_fields) : [''];
                            @endphp
                            @if(!empty($all_icon_fields))
                                @foreach($all_icon_fields as $index => $icon)
                                    <li>
                                        <i class="{{$icon}}"></i> {{isset($all_title_fields[$index]) ? $all_title_fields[$index] : ''}}
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        <div class="button-wrap margin-top-40">
                            <a href="{{get_static_option('portfolio_about_section_button_one_url')}}"
                               class="portfolio-btn">{{get_static_option('portfolio_about_section_'.$user_select_lang_slug.'_button_one_text')}}
                                <i class="{{get_static_option('portfolio_about_section_button_one_icon')}}"></i></a>
                            <a href="{{get_static_option('portfolio_about_section_button_two_url')}}"
                               class="portfolio-btn blank">{{get_static_option('portfolio_about_section_'.$user_select_lang_slug.'_button_two_text')}}
                                <i class="{{get_static_option('portfolio_about_section_button_two_icon')}}"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@if(!empty(get_static_option('home_page_expertice_section_status')))
<div class="expertice-area dark-section-bg-three padding-top-115 padding-bottom-90">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title white text-center margin-bottom-60">
                    <span class="subtitle">{{get_static_option('portfolio_expertice_section_'.$user_select_lang_slug.'_subtitle')}}</span>
                    <h2 class="title">{{get_static_option('portfolio_expertice_section_'.$user_select_lang_slug.'_title')}}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @php
                $all_icon_fields =  get_static_option('home_page_05_experties_section_skill_box_number');
                $all_icon_fields = !empty($all_icon_fields) ? unserialize($all_icon_fields) : [''];
                $all_title_fields = get_static_option('home_page_05_'.$user_select_lang_slug.'_experties_section_skill_box_title');
                $all_title_fields = !empty($all_title_fields) ? unserialize($all_title_fields) : [''];
                $all_subtitle_fields = get_static_option('home_page_05_'.$user_select_lang_slug.'_experties_section_skill_box_subtitle');
                $all_subtitle_fields = !empty($all_subtitle_fields) ? unserialize($all_subtitle_fields) : [''];
            @endphp
            @if(!empty($all_icon_fields))
                @foreach($all_icon_fields as $index => $icon)
                    <div class="col-lg-4 col-md-6">
                        <div class="single-expertice-area">
                            <span class="number">{{$icon}}%</span>
                            <h4 class="title">{{isset($all_title_fields[$index]) ? $all_title_fields[$index] : ''}}</h4>
                            <span class="category">{{isset($all_subtitle_fields[$index]) ? $all_subtitle_fields[$index] : ''}}</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endif
@if(!empty(get_static_option('home_page_service_section_status')))
<div class="what-we-offer-area dark-section-bg-two padding-top-115 padding-bottom-90">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title white text-center margin-bottom-60">
                    <span class="subtitle">{{get_static_option('portfolio_what_we_offer_section_'.$user_select_lang_slug.'_subtitle')}}</span>
                    <h2 class="title">{{get_static_option('portfolio_what_we_offer_section_'.$user_select_lang_slug.'_title')}}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($all_service as $data)
            <div class="col-lg-6">
                <div class="single-we-offer-item">
                    @if($data->icon_type == 'icon' || $data->icon_type == '')
                        <div class="icon">
                            <i class="{{$data->icon}}"></i>
                        </div>
                    @else
                        <div class="img-icon">
                            {!! render_image_markup_by_attachment_id($data->img_icon) !!}
                        </div>
                    @endif
                    <div class="content-wrap">
                        <h4 class="title"><a href="{{route('frontend.services.single', $data->slug)}}">{{$data->title}}</a></h4>
                        <p>{{$data->excerpt}}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@if(!empty(get_static_option('home_page_case_study_section_status')))
<div class="what-we-offer-area dark-section-bg-three padding-top-115 padding-bottom-90">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title white text-center margin-bottom-60">
                    <span class="subtitle">{{get_static_option('portfolio_recent_work_section_'.$user_select_lang_slug.'_subtitle')}}</span>
                    <h2 class="title">{{get_static_option('portfolio_recent_work_section_'.$user_select_lang_slug.'_title')}}</h2>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="case-studies-masonry-wrapper">
                    <ul class="case-studies-menu white">
                        <li class="active" data-filter="*">{{__('All')}}</li>
                        @foreach($all_work_category as $data)
                            <li data-filter=".{{Str::slug($data->name)}}">{{$data->name}}</li>
                        @endforeach
                    </ul>
                    <div class="case-studies-masonry">
                        @foreach($all_work as $data)
                            <div class="col-lg-4 col-md-4 col-sm-6 masonry-item {{get_work_category_by_id($data->id,'slug')}}">
                                <div class="single-case-studies-item portfolio-home margin-bottom-30">
                                    <div class="thumb">
                                        {!! render_image_markup_by_attachment_id($data->image) !!}
                                    </div>
                                    <div class="cart-icon">
                                        <h4 class="title"><a href="{{route('frontend.work.single',$data->slug)}}"> {{$data->title}}</a></h4>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="btn-wrapper text-center margin-top-40">
                    <a href="{{route('frontend.work')}}" class="portfolio-btn">{{get_static_option('portfolio_recent_work_section_'.$user_select_lang_slug.'_button_text')}} <i class="fas fa-long-arrow-alt-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@if(!empty(get_static_option('home_page_call_to_action_section_status')))
<div class="portfolio-cta-area dark-section-bg-two">
    <div class="shape-01 shape">
        <img src="{{asset('assets/frontend/img/shape/01.png')}}" alt="">
    </div>
    <div class="shape-02 shape">
        <img src="{{asset('assets/frontend/img/shape/02.png')}}" alt="">
    </div>
    <div class="shape-03 shape">
        <img src="{{asset('assets/frontend/img/shape/03.png')}}" alt="">
    </div>
    <div class="shape-04 shape">
        <img src="{{asset('assets/frontend/img/shape/04.png')}}" alt="">
    </div>
    <div class="shape-05 shape">
        <img src="{{asset('assets/frontend/img/shape/05.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-8">
                <div class="left-content-wrap">
                    <h4 class="title">{{{get_static_option('portfolio_cta_section_'.$user_select_lang_slug.'_title')}}}</h4>
                    <p class="description">{{{get_static_option('portfolio_cta_section_'.$user_select_lang_slug.'_description')}}}</p>
                    <div class="btn-wrapper">
                        <a href="{{get_static_option('portfolio_cta_section_button_url')}}" class="portfolio-btn">{{get_static_option('portfolio_cta_section_'.$user_select_lang_slug.'_button_text')}} <i class="{{get_static_option('portfolio_cta_section_button_icon')}}"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="right-content-wrap">
                    <div class="img-wrap">
                        {!! render_image_markup_by_attachment_id(get_static_option('portfolio_cta_section_right_image')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@if(!empty(get_static_option('home_page_testimonial_section_status')))
<div class="portfolio-testimonial-area dark-section-bg-three padding-top-115 padding-bottom-115">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title white text-center margin-bottom-60">
                    <span class="subtitle">{{get_static_option('portfolio_testimonial_section_'.$user_select_lang_slug.'_subtitle')}}</span>
                    <h2 class="title">{{get_static_option('portfolio_testimonial_section_'.$user_select_lang_slug.'_title')}}</h2>
                </div>
            </div>
        </div>
        <div class="row ">
            <div class="col-lg-12 ">
                <div class="testimonial-carousel-area margin-top-10 ">
                    <div class="portfolio-testimonial-carousel pcarousel-dots">
                        @foreach($all_testimonial as $data)
                            <div class="single-portfolio-testimonial-item ">
                                <div class="content">
                                    <i class="fas fa-quote-left"></i>
                                    <p class="description ">{{$data->description}}</p>
                                </div>
                                <div class="author-details ">
                                    <div class="thumb ">
                                        {!! render_image_markup_by_attachment_id($data->image,'','thumb') !!}
                                    </div>
                                    <div class="author-meta ">
                                        <h4 class="title ">{{$data->name}}</h4>
                                        <span class="designation ">{{$data->designation}}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@if(!empty(get_static_option('home_page_latest_news_section_status')))
<div class="portfolio-news-area dark-section-bg-two padding-top-115 padding-bottom-115">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title white text-center margin-bottom-60">
                    <span class="subtitle">{{get_static_option('portfolio_news_section_'.$user_select_lang_slug.'_subtitle')}}</span>
                    <h2 class="title">{{get_static_option('portfolio_news_section_'.$user_select_lang_slug.'_title')}}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="blog-grid-carosel-wrapper">
                    <div class="portfolio-news-carousel pcarousel-dots">
                        @foreach($all_blog as $data )
                            <div class="single-portfolio-blog-grid">
                                <div class="thumb">
                                    {!! render_image_markup_by_attachment_id($data->image,'grid') !!}
                                    <div class="time-wrap">
                                        <span class="date">{{date_format($data->created_at,'d')}}</span>
                                        <span class="month">{{date_format($data->created_at,'M')}}</span>
                                    </div>
                                </div>
                                <div class="content">
                                    <h4 class="title">
                                        <a href="{{route('frontend.blog.single',$data->slug)}}">{{$data->title}}</a>
                                    </h4>
                                    <p class="excerpt">{{strip_tags($data->excerpt)}}</p>
                                    <a class="readmore" href="{{route('frontend.blog.single',$data->slug)}}">{{get_static_option('portfolio_news_section_'.$user_select_lang_slug.'_button_text')}}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif