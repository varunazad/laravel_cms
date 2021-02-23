@extends('backend.admin-master')
@section('site-title')
    {{__('Page Settings')}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/bootstrap-tagsinput.css')}}">
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                @include('backend.partials.message')
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("Page Name & Slug Settings")}}</h4>
                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger">{{$error}}</div>
                             @endforeach
                        @endif
                        <form action="{{route('admin.general.page.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="from-group">
                                        <label for="about_page_slug">{{__('About Page Slug')}}</label>
                                        <input type="text" class="form-control" id="about_page_slug" value="{{get_static_option('about_page_slug')}}" name="about_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: about-page')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="service_page_slug">{{__('Service Page Slug')}}</label>
                                        <input type="text" class="form-control" id="service_page_slug" value="{{get_static_option('service_page_slug')}}" name="service_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: service-page')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="work_page_slug">{{__('Case Study Page Slug')}}</label>
                                        <input type="text" class="form-control" id="work_page_slug" value="{{get_static_option('work_page_slug')}}" name="work_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: case-study')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="team_page_slug">{{__('Team Page Slug')}}</label>
                                        <input type="text" class="form-control" id="team_page_slug" value="{{get_static_option('team_page_slug')}}" name="team_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: team')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="faq_page_slug">{{__('Faq Page Slug')}}</label>
                                        <input type="text" class="form-control" id="faq_page_slug" value="{{get_static_option('faq_page_slug')}}" name="faq_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: faq')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="price_plan_page_slug">{{__('Price Plan Page Slug')}}</label>
                                        <input type="text" class="form-control" id="price_plan_page_slug" value="{{get_static_option('price_plan_page_slug')}}" name="price_plan_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: price-plan')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="blog_page_slug">{{__('Blog Page Slug')}}</label>
                                        <input type="text" class="form-control" id="blog_page_slug" value="{{get_static_option('blog_page_slug')}}" name="blog_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: blog')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="contact_page_slug">{{__('Contact Page Slug')}}</label>
                                        <input type="text" class="form-control" id="contact_page_slug" value="{{get_static_option('contact_page_slug')}}" name="contact_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: contact')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="career_with_us_page_slug">{{__('Career With Us Page Slug')}}</label>
                                        <input type="text" class="form-control" id="career_with_us_page_slug" value="{{get_static_option('career_with_us_page_slug')}}" name="career_with_us_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: career')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="events_page_slug">{{__('Events Page Slug')}}</label>
                                        <input type="text" class="form-control" id="events_page_slug" value="{{get_static_option('events_page_slug')}}" name="events_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: events')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="knowledgebase_page_slug">{{__('Knowledgebase Page Slug')}}</label>
                                        <input type="text" class="form-control" id="knowledgebase_page_slug" value="{{get_static_option('knowledgebase_page_slug')}}" name="knowledgebase_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: knowledgebase')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="quote_page_slug">{{__('Quote Page Slug')}}</label>
                                        <input type="text" class="form-control" id="quote_page_slug" value="{{get_static_option('quote_page_slug')}}" name="quote_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: request-quote')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="donation_page_slug">{{__('Donation Page Slug')}}</label>
                                        <input type="text" class="form-control" id="donation_page_slug" value="{{get_static_option('donation_page_slug')}}" name="donation_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: donation')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="product_page_slug">{{__('Product Page Slug')}}</label>
                                        <input type="text" class="form-control" id="product_page_slug" value="{{get_static_option('product_page_slug')}}" name="product_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: products')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="testimonial_page_slug">{{__('Testimonial Page Slug')}}</label>
                                        <input type="text" class="form-control" id="testimonial_page_slug" value="{{get_static_option('testimonial_page_slug')}}" name="testimonial_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: testimonial')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="feedback_page_slug">{{__('Feedback Page Slug')}}</label>
                                        <input type="text" class="form-control" id="feedback_page_slug" value="{{get_static_option('feedback_page_slug')}}" name="feedback_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: feedback')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="clients_feedback_page_slug">{{__('Clients Feedback Page Slug')}}</label>
                                        <input type="text" class="form-control" id="clients_feedback_page_slug" value="{{get_static_option('clients_feedback_page_slug')}}" name="clients_feedback_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: clients-feedback')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="image_gallery_page_slug">{{__('Image Gallery Page Slug')}}</label>
                                        <input type="text" class="form-control" id="image_gallery_page_slug" value="{{get_static_option('image_gallery_page_slug')}}" name="image_gallery_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: image-gallery')}}</small>
                                    </div>
                                    <div class="from-group">
                                        <label for="donor_page_slug">{{__('Donor List Page Slug')}}</label>
                                        <input type="text" class="form-control" id="donor_page_slug" value="{{get_static_option('donor_page_slug')}}" name="donor_page_slug" placeholder="{{__('Slug')}}" >
                                        <small>{{__('slug example: donor-list')}}</small>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            @foreach($all_languages as $key => $lang)
                                                <a class="nav-item nav-link @if($key == 0) active @endif" id="nav-home-tab" data-toggle="tab" href="#nav-home-{{$lang->slug}}" role="tab" aria-controls="nav-home" aria-selected="true">{{$lang->name}}</a>
                                            @endforeach
                                        </div>
                                    </nav>
                                    <div class="tab-content margin-top-30" id="nav-tabContent">
                                        @foreach($all_languages as $key => $lang)
                                            <div class="tab-pane fade @if($key == 0) show active @endif" id="nav-home-{{$lang->slug}}" role="tabpanel" aria-labelledby="nav-home-tab">
                                                <div class="accordion-wrapper">
                                                    <div id="accordion-{{$lang->slug}}">
                                                        <div class="card">
                                                            <div class="card-header" id="about_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#about_page_content_{{$lang->slug}}" aria-expanded="true" >
                                                                        <span class="page-title">@if(!empty(get_static_option('about_page_'.$lang->slug.'_name'))) {{get_static_option('about_page_'.$lang->slug.'_name')}} @else {{__('About')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="about_page_content_{{$lang->slug}}" class="collapse show"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="about_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control" name="about_page_{{$lang->slug}}_name" id="about_page_{{$lang->slug}}_name" value="{{get_static_option('about_page_'.$lang->slug.'_name')}}"  placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="about_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="about_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('about_page_'.$lang->slug.'_meta_tags')}}" id="about_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="about_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="about_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="about_page_{{$lang->slug}}_meta_description">{{get_static_option('about_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="service_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#service_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('service_page_'.$lang->slug.'_name'))) {{get_static_option('service_page_'.$lang->slug.'_name')}} @else {{__('Service')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="service_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="service_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control" name="service_page_{{$lang->slug}}_name" id="service_page_{{$lang->slug}}_name" value="{{get_static_option('service_page_'.$lang->slug.'_name')}}"  placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="service_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="service_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('service_page_'.$lang->slug.'_meta_tags')}}" id="service_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="service_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="service_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="service_page_{{$lang->slug}}_meta_description">{{get_static_option('service_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="work_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#work_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('work_page_'.$lang->slug.'_name'))) {{get_static_option('work_page_'.$lang->slug.'_name')}} @else {{__('Case Study')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="work_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="work_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="work_page_{{$lang->slug}}_name" value="{{get_static_option('work_page_'.$lang->slug.'_name')}}" name="work_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="work_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="work_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('work_page_'.$lang->slug.'_meta_tags')}}" id="work_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="work_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="work_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="work_page_{{$lang->slug}}_meta_description">{{get_static_option('work_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="team_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#team_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('team_page_'.$lang->slug.'_name'))) {{get_static_option('team_page_'.$lang->slug.'_name')}} @else {{__('Team')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="team_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="team_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="team_page_{{$lang->slug}}_name" value="{{get_static_option('team_page_'.$lang->slug.'_name')}}" name="team_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="team_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="team_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('team_page_'.$lang->slug.'_meta_tags')}}" id="team_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="team_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="team_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="team_page_{{$lang->slug}}_meta_description">{{get_static_option('team_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="faq_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#faq_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('faq_page_'.$lang->slug.'_name'))) {{get_static_option('faq_page_'.$lang->slug.'_name')}} @else {{__('Faq')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="faq_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="faq_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="faq_page_{{$lang->slug}}_name" value="{{get_static_option('faq_page_'.$lang->slug.'_name')}}" name="faq_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="faq_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="faq_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('faq_page_'.$lang->slug.'_meta_tags')}}" id="faq_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="faq_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="faq_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="faq_page_{{$lang->slug}}_meta_description">{{get_static_option('faq_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="price_plan_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#price_plan_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('price_plan_page_'.$lang->slug.'_name'))) {{get_static_option('price_plan_page_'.$lang->slug.'_name')}} @else {{__('Price Plan')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="price_plan_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="price_plan_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="price_plan_page_{{$lang->slug}}_name" value="{{get_static_option('price_plan_page_'.$lang->slug.'_name')}}" name="price_plan_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="price_plan_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="price_plan_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('price_plan_page_'.$lang->slug.'_meta_tags')}}" id="price_plan_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="price_plan_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="price_plan_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="price_plan_page_{{$lang->slug}}_meta_description">{{get_static_option('price_plan_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="blog_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#blog_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('blog_page_'.$lang->slug.'_name'))) {{get_static_option('blog_page_'.$lang->slug.'_name')}} @else {{__('Blog')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="blog_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="blog_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="blog_page_{{$lang->slug}}_name" value="{{get_static_option('blog_page_'.$lang->slug.'_name')}}" name="blog_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="blog_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="blog_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('blog_page_'.$lang->slug.'_meta_tags')}}" id="blog_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="blog_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="blog_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="blog_page_{{$lang->slug}}_meta_description">{{get_static_option('blog_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="contact_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#contact_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('contact_page_'.$lang->slug.'_name'))) {{get_static_option('contact_page_'.$lang->slug.'_name')}} @else {{__('Contact')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="contact_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="contact_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="contact_page_{{$lang->slug}}_name" value="{{get_static_option('contact_page_'.$lang->slug.'_name')}}" name="contact_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="contact_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="contact_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('contact_page_'.$lang->slug.'_meta_tags')}}" id="contact_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="contact_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="contact_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="contact_page_{{$lang->slug}}_meta_description">{{get_static_option('contact_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="career_with_us_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#career_with_us_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('career_with_us_page_'.$lang->slug.'_name'))) {{get_static_option('career_with_us_page_'.$lang->slug.'_name')}} @else {{__('Career With Us')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="career_with_us_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="career_with_us_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="career_with_us_page_{{$lang->slug}}_name" value="{{get_static_option('career_with_us_page_'.$lang->slug.'_name')}}" name="career_with_us_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="career_with_us_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="career_with_us_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('career_with_us_page_'.$lang->slug.'_meta_tags')}}" id="career_with_us_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="career_with_us_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="career_with_us_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="career_with_us_page_{{$lang->slug}}_meta_description">{{get_static_option('career_with_us_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="events_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#events_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('events_page_'.$lang->slug.'_name'))) {{get_static_option('events_page_'.$lang->slug.'_name')}} @else {{__('Events')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="events_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="events_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="events_page_{{$lang->slug}}_name" value="{{get_static_option('events_page_'.$lang->slug.'_name')}}" name="events_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="events_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="events_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('events_page_'.$lang->slug.'_meta_tags')}}" id="events_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="events_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="events_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="events_page_{{$lang->slug}}_meta_description">{{get_static_option('events_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="card">
                                                            <div class="card-header" id="knowledgebase_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#knowledgebase_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('knowledgebase_page_'.$lang->slug.'_name'))) {{get_static_option('knowledgebase_page_'.$lang->slug.'_name')}} @else {{__('Knowledgebase')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="knowledgebase_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="knowledgebase_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="knowledgebase_page_{{$lang->slug}}_name" value="{{get_static_option('knowledgebase_page_'.$lang->slug.'_name')}}" name="knowledgebase_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="knowledgebase_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="events_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('knowledgebase_page_'.$lang->slug.'_meta_tags')}}" id="knowledgebase_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="knowledgebase_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="knowledgebase_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="knowledgebase_page_{{$lang->slug}}_meta_description">{{get_static_option('knowledgebase_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="quote_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#quote_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('quote_page_'.$lang->slug.'_name'))) {{get_static_option('quote_page_'.$lang->slug.'_name')}} @else {{__('Quote')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="quote_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="quote_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="quote_page_{{$lang->slug}}_name" value="{{get_static_option('quote_page_'.$lang->slug.'_name')}}" name="quote_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="quote_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="quote_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('quote_page_'.$lang->slug.'_meta_tags')}}" id="quote_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="quote_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="quote_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="quote_page_{{$lang->slug}}_meta_description">{{get_static_option('quote_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="card">
                                                            <div class="card-header" id="donation_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#donation_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('donation_page_'.$lang->slug.'_name'))) {{get_static_option('donation_page_'.$lang->slug.'_name')}} @else {{__('Donation')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="donation_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="donation_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="donation_page_{{$lang->slug}}_name" value="{{get_static_option('donation_page_'.$lang->slug.'_name')}}" name="donation_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="donation_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="donation_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('donation_page_'.$lang->slug.'_meta_tags')}}" id="donation_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="donation_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="donation_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="donation_page_{{$lang->slug}}_meta_description">{{get_static_option('donation_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="card">
                                                            <div class="card-header" id="product_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#product_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('product_page_'.$lang->slug.'_name'))) {{get_static_option('product_page_'.$lang->slug.'_name')}} @else {{__('Products')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="product_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="product_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="product_page_{{$lang->slug}}_name" value="{{get_static_option('product_page_'.$lang->slug.'_name')}}" name="product_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="product_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="product_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('product_page_'.$lang->slug.'_meta_tags')}}" id="product_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="product_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="product_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="product_page_{{$lang->slug}}_meta_description">{{get_static_option('product_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="testimonial_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#testimonial_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('testimonial_page_'.$lang->slug.'_name'))) {{get_static_option('testimonial_page_'.$lang->slug.'_name')}} @else {{__('Testimonials')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="testimonial_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="testimonial_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="testimonial_page_{{$lang->slug}}_name" value="{{get_static_option('testimonial_page_'.$lang->slug.'_name')}}" name="testimonial_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="testimonial_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="testimonial_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('testimonial_page_'.$lang->slug.'_meta_tags')}}" id="testimonial_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="testimonial_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="testimonial_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="testimonial_page_{{$lang->slug}}_meta_description">{{get_static_option('testimonial_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="feedback_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#feedback_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('feedback_page_'.$lang->slug.'_name'))) {{get_static_option('feedback_page_'.$lang->slug.'_name')}} @else {{__('Feedback')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="feedback_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="feedback_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="feedback_page_{{$lang->slug}}_name" value="{{get_static_option('feedback_page_'.$lang->slug.'_name')}}" name="feedback_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="feedback_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="feedback_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('feedback_page_'.$lang->slug.'_meta_tags')}}" id="feedback_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="feedback_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="feedback_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="feedback_page_{{$lang->slug}}_meta_description">{{get_static_option('feedback_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="clients_feedback_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#clients_feedback_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('clients_feedback_page_'.$lang->slug.'_name'))) {{get_static_option('clients_feedback_page_'.$lang->slug.'_name')}} @else {{__('Clients Feedback')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="clients_feedback_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="clients_feedback_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="clients_feedback_page_{{$lang->slug}}_name" value="{{get_static_option('clients_feedback_page_'.$lang->slug.'_name')}}" name="clients_feedback_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="clients_feedback_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="clients_feedback_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('clients_feedback_page_'.$lang->slug.'_meta_tags')}}" id="clients_feedback_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="feedback_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="clients_feedback_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="clients_feedback_page_{{$lang->slug}}_meta_description">{{get_static_option('clients_feedback_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="image_gallery_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#image_gallery_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('image_gallery_page_'.$lang->slug.'_name'))) {{get_static_option('image_gallery_page_'.$lang->slug.'_name')}} @else {{__('Image Gallery')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="image_gallery_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="image_gallery_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="image_gallery_page_{{$lang->slug}}_name" value="{{get_static_option('image_gallery_page_'.$lang->slug.'_name')}}" name="image_gallery_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="image_gallery_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="image_gallery_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('image_gallery_page_'.$lang->slug.'_meta_tags')}}" id="image_gallery_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="image_gallery_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="image_gallery_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="image_gallery_page_{{$lang->slug}}_meta_description">{{get_static_option('image_gallery_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header" id="donor_page_{{$lang->slug}}">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#donor_page_content_{{$lang->slug}}" aria-expanded="false" >
                                                                        <span class="page-title">@if(!empty(get_static_option('donor_page_'.$lang->slug.'_name'))) {{get_static_option('donor_page_'.$lang->slug.'_name')}} @else {{__('Donor List')}}  @endif</span>
                                                                    </button>
                                                                </h5>
                                                            </div>
                                                            <div id="donor_page_content_{{$lang->slug}}" class="collapse"  data-parent="#accordion-{{$lang->slug}}">
                                                                <div class="card-body">
                                                                    <div class="from-group">
                                                                        <label for="donor_page_{{$lang->slug}}_name">{{__('Name')}}</label>
                                                                        <input type="text" class="form-control page-name" id="donor_page_{{$lang->slug}}_name" value="{{get_static_option('donor_page_'.$lang->slug.'_name')}}" name="donor_page_{{$lang->slug}}_name" placeholder="{{__('Name')}}" >
                                                                    </div>
                                                                    <div class="form-group margin-top-20">
                                                                        <label for="donor_page_{{$lang->slug}}_meta_tags">{{__('Meta Tags')}}</label>
                                                                        <input type="text" name="donor_page_{{$lang->slug}}_meta_tags"  class="form-control" data-role="tagsinput" value="{{get_static_option('donor_page_'.$lang->slug.'_meta_tags')}}" id="donor_page_{{$lang->slug}}_meta_tags">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="donor_page_{{$lang->slug}}_meta_description">{{__('Meta Description')}}</label>
                                                                        <textarea name="donor_page_{{$lang->slug}}_meta_description"  class="form-control" rows="5" id="donor_page_{{$lang->slug}}_meta_description">{{get_static_option('donor_page_'.$lang->slug.'_meta_description')}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{asset('assets/backend/js/bootstrap-tagsinput.js')}}"></script>
    <script>
        $(document).ready(function (e) {
            $('.page-name').bind('change paste keyup',function (e) {
                $(this).parent().parent().parent().prev().find('.page-title').text($(this).val());
            })
        })
    </script>
@endsection
