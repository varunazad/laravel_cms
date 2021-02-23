@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/colorpicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/dropzone.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/media-uploader.css')}}">
@endsection
@section('site-title')
    {{__('Basic Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                @include('backend.partials.message')
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("Basic Settings")}}</h4>
                        <form action="{{route('admin.general.basic.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf
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
                                        <div class="form-group">
                                            <label for="site_{{$lang->slug}}_title">{{__('Site Title')}}</label>
                                            <input type="text" name="site_{{$lang->slug}}_title"  class="form-control" value="{{get_static_option('site_'.$lang->slug.'_title')}}" id="site_{{$lang->slug}}_title">
                                        </div>
                                        <div class="form-group">
                                            <label for="site_{{$lang->slug}}_tag_line">{{__('Site Tag Line')}}</label>
                                            <input type="text" name="site_{{$lang->slug}}_tag_line"  class="form-control" value="{{get_static_option('site_'.$lang->slug.'_tag_line')}}" id="site_{{$lang->slug}}_tag_line">
                                        </div>
                                        <div class="form-group">
                                            <label for="site_{{$lang->slug}}_footer_copyright">{{__('Footer Copyright')}}</label>
                                            <input type="text" name="site_{{$lang->slug}}_footer_copyright"  class="form-control" value="{{get_static_option('site_'.$lang->slug.'_footer_copyright')}}" id="site_{{$lang->slug}}_footer_copyright">
                                            <small class="form-text text-muted">{copy} {{__('Will replace by &copy; and {year} will be replaced by current year.')}}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="og_meta_image_for_site"><strong>{{__('Og Meta Image For Site')}}</strong></label>
                                <div class="media-upload-btn-wrapper">
                                    <div class="img-wrap">
                                        @php
                                            $blog_img = get_attachment_image_by_id(get_static_option('og_meta_image_for_site'),null,true);
                                            $blog_image_btn_label = 'Upload Image';
                                        @endphp
                                        @if (!empty($blog_img))
                                            <div class="attachment-preview">
                                                <div class="thumbnail">
                                                    <div class="centered">
                                                        <img class="avatar user-thumb" src="{{$blog_img['img_url']}}" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                            @php  $blog_image_btn_label = 'Change Image'; @endphp
                                        @endif
                                    </div>
                                    <input type="hidden" id="og_meta_image_for_site" name="og_meta_image_for_site" value="{{get_static_option('og_meta_image_for_site')}}">
                                    <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="Select Image" data-modaltitle="Upload Image" data-toggle="modal" data-target="#media_upload_modal">
                                        {{__($blog_image_btn_label)}}
                                    </button>
                                </div>
                                <small class="form-text text-muted">{{__('allowed image format: jpg,jpeg,png. Recommended image size 1200x900')}}</small>
                            </div>
                            <div class="form-group">
                                <label for="site_sticky_navbar_enabled"><strong>{{__('Sticky Navbar Enable/Disable')}}</strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="site_sticky_navbar_enabled"  @if(!empty(get_static_option('site_sticky_navbar_enabled'))) checked @endif id="site_sticky_navbar_enabled">
                                    <span class="slider onff"></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="site_admin_dark_mode"><strong>{{__('Dark Mode For Admin Dashboard')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="site_admin_dark_mode"  @if(!empty(get_static_option('site_admin_dark_mode'))) checked @endif id="site_admin_dark_mode">
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="site_maintenance_mode"><strong>{{__('Maintenance Mode')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="site_maintenance_mode"  @if(!empty(get_static_option('site_maintenance_mode'))) checked @endif id="site_maintenance_mode">
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="language_select_option"><strong>{{__('Language Select Show/Hide')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="language_select_option"  @if(!empty(get_static_option('language_select_option'))) checked @endif id="language_select_option">
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="site_admin_panel_nav_sticky"><strong>{{__('Enable/Disable Admin Panel Nav Sticky')}}</strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="site_admin_panel_nav_sticky"  @if(!empty(get_static_option('site_admin_panel_nav_sticky'))) checked @endif >
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="site_payment_gateway"><strong>{{__('Enable/Disable Payment Gateway')}}</strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="site_payment_gateway"  @if(!empty(get_static_option('site_payment_gateway'))) checked @endif id="site_payment_gateway">
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="disable_backend_preloader"><strong>{{__('Enable/Disable Backend Preloader')}}</strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="disable_backend_preloader"  @if(!empty(get_static_option('disable_backend_preloader'))) checked @endif id="disable_backend_preloader">
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="site_force_ssl_redirection"><strong>{{__('Enable/Disable Force SSL Redirection')}}</strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="site_force_ssl_redirection"  @if(!empty(get_static_option('site_force_ssl_redirection'))) checked @endif>
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="disable_user_email_verify"><strong>{{__('Disable User Email Verify')}}</strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="disable_user_email_verify"  @if(!empty(get_static_option('disable_user_email_verify'))) checked @endif id="disable_user_email_verify">
                                    <span class="slider onff"></span>
                                </label>
                                <small class="info-text">{{__('No, means user must have to verify their email account in order access his/her dashboard.')}}</small>
                            </div>
                            <div class="form-group">
                                <label for="site_color">{{__('Site Main Color Settings')}}</label>
                                <input type="text" name="site_color" style="background-color: {{get_static_option('site_color')}};color: #fff;" class="form-control" value="{{get_static_option('site_color')}}" id="site_color">
                                <small>{{__('you change site main color from here, it will replace website main color')}}</small>
                            </div>
                            <div class="form-group">
                                <label for="site_color">{{__('Site Base Color Two Settings')}}</label>
                                <input type="text" name="site_main_color_two" style="background-color: {{get_static_option('site_main_color_two')}};color: #fff;" class="form-control" value="{{get_static_option('site_main_color_two')}}" id="site_main_color_two">
                                <small>{{__('you change site base color two color from here, it will replace website site base color two color')}}</small>
                            </div>
                            <div class="form-group">
                                <label for="site_secondary_color">{{__('Site Secondary Color Settings')}}</label>
                                <input type="text" name="site_secondary_color" style="background-color: {{get_static_option('site_secondary_color')}};color: #fff;" class="form-control" value="{{get_static_option('site_secondary_color')}}" id="site_secondary_color">
                                <small>{{__('you change site secondary color from here, it will replace website secondary color')}}</small>
                            </div>
                            <div class="form-group">
                                <label for="site_heading_color">{{__('Site Heading Color')}}</label>
                                <input type="text" name="site_heading_color" style="background-color: {{get_static_option('site_heading_color')}};color: #fff;" class="form-control" value="{{get_static_option('site_heading_color')}}" id="site_heading_color">
                                <small>{{__('you can change site heading color from there , when you chnage this color it will reflect the color in all the heading like (h1,h2,h3,h4.h5.h6)')}}</small>
                            </div>
                            <div class="form-group">
                                <label for="site_paragraph_color">{{__('Site Paragraph Color')}}</label>
                                <input type="text" name="site_paragraph_color" style="background-color: {{get_static_option('site_paragraph_color')}};color: #fff;" class="form-control" value="{{get_static_option('site_paragraph_color')}}" id="site_paragraph_color">
                                <small>{{__('you can change site paragraph color from there')}}</small>
                            </div>
                            <div class="form-group">
                                <label for="portfolio_home_color">{{__('Portfolio Home Color')}}</label>
                                <input type="text" name="portfolio_home_color" style="background-color: {{get_static_option('portfolio_home_color')}};color: #fff;" class="form-control" value="{{get_static_option('portfolio_home_color')}}" >
                            </div>
                            <div class="form-group">
                                <label for="logistics_home_color">{{__('Logistics Home Color')}}</label>
                                <input type="text" name="logistics_home_color" style="background-color: {{get_static_option('logistics_home_color')}};color: #fff;" class="form-control" value="{{get_static_option('logistics_home_color')}}" >
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.partials.media-upload.media-upload-markup')
@endsection
@section('script')
    <script src="{{asset('assets/backend/js/colorpicker.js')}}"></script>
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>
    @include('backend.partials.media-upload.media-js')
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
@endsection
