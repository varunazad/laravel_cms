@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/nestable.css')}}">
@endsection
@section('site-title')
    {{__('Edit Menu')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                @include('backend/partials/message')
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Edit Menu')}}</h4>
                        <form action="{{route('admin.menu.update',$page_post->id)}}" id="menu_update_form" method="post"
                              enctype="multipart/form-data">
                            <input type="hidden" name="menu_id" id="menu_id" value="{{$page_post->id}}">
                            @csrf
                            @php
                                $menu_content = '';
if (!empty($page_post->content)){
    $menu_content = $page_post->content;
}else{
    $menu_content = '[{"ptype":"custom","pname":"Home","purl":"@url","id":1}]';
}
                            @endphp
                            <textarea  id="menu_content" name="menu_content" style="display: none;" class="form-control" >{{$menu_content}}</textarea>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>{{__('Language')}}</label>
                                        <select name="lang" id="language" class="form-control">
                                            @foreach($all_languages as $lang)
                                                <option @if($lang->slug == $page_post->lang) selected @endif value="{{$lang->slug}}">{{$lang->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="title">{{__('Title')}}</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                               value="{{$page_post->title}}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="menu-left-side-content">
                                        <h3 class="title">{{__('Add menu items')}}</h3>
                                        <div class="accordion accordion-wrapper" id="add_menu_item_accordion">
                                            <div class="card">
                                                <div class="card-header" id="page-list-items">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#page-list-items-content"
                                                                aria-expanded="true"
                                                                aria-controls="page-list-items-content">
                                                            {{__('Pages')}}
                                                        </button>
                                                    </h2>
                                                </div>
                                                @php
                                                    $default_lang = get_default_language();
                                                    $static_page_list = ['about','service','work','team','faq','price_plan','blog','contact','career_with_us','events','knowledgebase','quote','donation','product','testimonial','feedback','clients_feedback','image_gallery','donor' ];
                                                @endphp
                                                <div id="page-list-items-content" class="collapse show"
                                                     aria-labelledby="page-list-items"
                                                     data-parent="#add_menu_item_accordion">
                                                    <div class="card-body">
                                                        <ul class="page-list-ul">
                                                            <li data-ptype="custom" data-purl="@url" data-pname="{{__('Home')}}">
                                                                <label class="menu-item-title">
                                                                    <input type="checkbox" class="menu-item-checkbox">
                                                                    {{__('Home')}}
                                                                </label>
                                                            </li>
                                                            @foreach($static_page_list as $key => $static_page)
                                                                <li data-ptype="static" data-pslug="{{$static_page.'_page_slug'}}" data-pname="{{$static_page.'_page_'.$page_post->lang.'_name'}}">
                                                                    <label class="menu-item-title">
                                                                        <input type="checkbox" class="menu-item-checkbox">
                                                                        {{get_static_option($static_page.'_page_'.$page_post->lang.'_name')}}
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="form-group">
                                                            <button type="button" id="add_page_to_menu"
                                                                    class="btn btn-primary btn-xs mt-4 pr-4 pl-4 add_page_to_menu">{{__('Add To Menu')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="dynamic-page-list-items">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#dynamic-page-list-items-content"
                                                                aria-expanded="true"
                                                                aria-controls="page-list-items-content">
                                                            {{__('Dynamic Pages')}}
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="dynamic-page-list-items-content" class="collapse"
                                                     aria-labelledby="page-list-items"
                                                     data-parent="#add_menu_item_accordion">
                                                    <div class="card-body">
                                                        <ul class="page-list-ul">
                                                            @foreach($all_page as $static_page)
                                                                <li data-ptype="dynamic" data-pid="{{$static_page->id}}">
                                                                    <label class="menu-item-title">
                                                                        <input type="checkbox" class="menu-item-checkbox">
                                                                        {{$static_page->title}}
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="form-group">
                                                            <button type="button" id="add_dynamic_page_to_menu"
                                                                    class="btn btn-primary btn-xs mt-4 pr-4 pl-4 add_page_to_menu">{{__('Add To Menu')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="service-page-list-items">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#service-page-list-items-content"
                                                                aria-expanded="true"
                                                                aria-controls="page-list-items-content">
                                                            {{__('All Services')}}
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="service-page-list-items-content" class="collapse"
                                                     aria-labelledby="page-list-items"
                                                     data-parent="#add_menu_item_accordion">
                                                    <div class="card-body">
                                                        <ul class="page-list-ul">
                                                            @foreach($all_services as $static_page)
                                                                <li data-ptype="service" data-pid="{{$static_page->id}}">
                                                                    <label class="menu-item-title">
                                                                        <input type="checkbox" class="menu-item-checkbox">
                                                                        {{$static_page->title}}
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="form-group">
                                                            <button type="button" id="add_dynamic_page_to_menu"
                                                                    class="btn btn-primary btn-xs mt-4 pr-4 pl-4 add_page_to_menu">{{__('Add To Menu')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(!empty(get_static_option('events_module_status')))
                                            <div class="card">
                                                <div class="card-header" id="event-page-list-items">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#event-page-list-items-content"
                                                                aria-expanded="true"
                                                                aria-controls="page-list-items-content">
                                                            {{__('All Event')}}
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="event-page-list-items-content" class="collapse"
                                                     aria-labelledby="page-list-items"
                                                     data-parent="#add_menu_item_accordion">
                                                    <div class="card-body">
                                                        <ul class="page-list-ul">
                                                            @foreach($all_events as $static_page)
                                                                <li data-ptype="event" data-pid="{{$static_page->id}}">
                                                                    <label class="menu-item-title">
                                                                        <input type="checkbox" class="menu-item-checkbox">
                                                                        {{$static_page->title}}
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="form-group">
                                                            <button type="button" id="add_dynamic_page_to_menu"
                                                                    class="btn btn-primary btn-xs mt-4 pr-4 pl-4 add_page_to_menu">{{__('Add To Menu')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="card">
                                                <div class="card-header" id="case-study-page-list-items">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#case-study-page-list-items-content"
                                                                aria-expanded="true"
                                                                aria-controls="page-list-items-content">
                                                            {{__('All Case Study')}}
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="case-study-page-list-items-content" class="collapse"
                                                     aria-labelledby="page-list-items"
                                                     data-parent="#add_menu_item_accordion">
                                                    <div class="card-body">
                                                        <ul class="page-list-ul">
                                                            @foreach($all_works as $static_page)
                                                                <li data-ptype="work" data-pid="{{$static_page->id}}">
                                                                    <label class="menu-item-title">
                                                                        <input type="checkbox" class="menu-item-checkbox">
                                                                        {{$static_page->title}}
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="form-group">
                                                            <button type="button" id="add_dynamic_page_to_menu"
                                                                    class="btn btn-primary btn-xs mt-4 pr-4 pl-4 add_page_to_menu">{{__('Add To Menu')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="blog-page-list-items">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#blog-page-list-items-content"
                                                                aria-expanded="true"
                                                                aria-controls="page-list-items-content">
                                                            {{__('All Blog')}}
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="blog-page-list-items-content" class="collapse"
                                                     aria-labelledby="page-list-items"
                                                     data-parent="#add_menu_item_accordion">
                                                    <div class="card-body">
                                                        <ul class="page-list-ul">
                                                            @foreach($all_blogs as $static_page)
                                                                <li data-ptype="blog" data-pid="{{$static_page->id}}">
                                                                    <label class="menu-item-title">
                                                                        <input type="checkbox" class="menu-item-checkbox">
                                                                        {{$static_page->title}}
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="form-group">
                                                            <button type="button" id="add_dynamic_page_to_menu"
                                                                    class="btn btn-primary btn-xs mt-4 pr-4 pl-4 add_page_to_menu">{{__('Add To Menu')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(!empty(get_static_option('job_module_status')))
                                            <div class="card">
                                                <div class="card-header" id="job-page-list-items">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#jobs-page-list-items-content"
                                                                aria-expanded="true"
                                                                aria-controls="page-list-items-content">
                                                            {{__('All Jobs')}}
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="jobs-page-list-items-content" class="collapse"
                                                     aria-labelledby="page-list-items"
                                                     data-parent="#add_menu_item_accordion">
                                                    <div class="card-body">
                                                        <ul class="page-list-ul">
                                                            @foreach($all_jobs as $static_page)
                                                                <li data-ptype="job" data-pid="{{$static_page->id}}">
                                                                    <label class="menu-item-title">
                                                                        <input type="checkbox" class="menu-item-checkbox">
                                                                        {{$static_page->title}}
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="form-group">
                                                            <button type="button" id="add_dynamic_page_to_menu"
                                                                    class="btn btn-primary btn-xs mt-4 pr-4 pl-4 add_page_to_menu">{{__('Add To Menu')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if(!empty(get_static_option('knowledgebase_module_status')))
                                            <div class="card">
                                                <div class="card-header" id="knowledgebase-page-list-items">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#knowledgebase-page-list-items-content"
                                                                aria-expanded="true"
                                                                aria-controls="page-list-items-content">
                                                            {{__('All Knowledgebase')}}
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="knowledgebase-page-list-items-content" class="collapse"
                                                     aria-labelledby="page-list-items"
                                                     data-parent="#add_menu_item_accordion">
                                                    <div class="card-body">
                                                        <ul class="page-list-ul">
                                                            @foreach($all_knowledgebase as $static_page)
                                                                <li data-ptype="knowledgebase" data-pid="{{$static_page->id}}">
                                                                    <label class="menu-item-title">
                                                                        <input type="checkbox" class="menu-item-checkbox">
                                                                        {{$static_page->title}}
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="form-group">
                                                            <button type="button" id="add_post_type_page_to_menu"
                                                                    class="btn btn-primary btn-xs mt-4 pr-4 pl-4 add_page_to_menu">{{__('Add To Menu')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if(!empty(get_static_option('product_module_status')))
                                            <div class="card">
                                                <div class="card-header" id="product-page-list-items">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#product-page-list-items-content"
                                                                aria-expanded="true"
                                                                aria-controls="page-list-items-content">
                                                            {{__('All Products')}}
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="product-page-list-items-content" class="collapse"
                                                     aria-labelledby="page-list-items"
                                                     data-parent="#add_menu_item_accordion">
                                                    <div class="card-body">
                                                        <ul class="page-list-ul">
                                                            @foreach($all_products as $static_page)
                                                                <li data-ptype="product" data-pid="{{$static_page->id}}">
                                                                    <label class="menu-item-title">
                                                                        <input type="checkbox" class="menu-item-checkbox">
                                                                        {{$static_page->title}}
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="form-group">
                                                            <button type="button" id="add_dynamic_page_to_menu"
                                                                    class="btn btn-primary btn-xs mt-4 pr-4 pl-4 add_page_to_menu">{{__('Add To Menu')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if(!empty(get_static_option('donation_module_check')))
                                            <div class="card">
                                                <div class="card-header" id="donation-page-list-items">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#donation-page-list-items-content"
                                                                aria-expanded="true"
                                                                aria-controls="page-list-items-content">
                                                            {{__('All Donations')}}
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="donation-page-list-items-content" class="collapse"
                                                     aria-labelledby="page-list-items"
                                                     data-parent="#add_menu_item_accordion">
                                                    <div class="card-body">
                                                        <ul class="page-list-ul">
                                                            @foreach($all_donations as $static_page)
                                                                <li data-ptype="donation" data-pid="{{$static_page->id}}">
                                                                    <label class="menu-item-title">
                                                                        <input type="checkbox" class="menu-item-checkbox">
                                                                        {{$static_page->title}}
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="form-group">
                                                            <button type="button" id="add_dynamic_page_to_menu"
                                                                    class="btn btn-primary btn-xs mt-4 pr-4 pl-4 add_page_to_menu">{{__('Add To Menu')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="card">
                                                <div class="card-header" id="megamenu-page-list-items">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#megamenu-page-list-items-content"
                                                                aria-expanded="true"
                                                                aria-controls="page-list-items-content">
                                                            {{__('Mega Menus')}}
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="megamenu-page-list-items-content" class="collapse"
                                                     aria-labelledby="page-list-items"
                                                     data-parent="#add_menu_item_accordion">
                                                    <div class="card-body">
@php $all_mega_menus = [
    'service_mega_menu' => get_static_option('service_page_'.$default_lang.'_name'),
    'work_mega_menu' => get_static_option('work_page_'.$default_lang.'_name'),
    'event_mega_menu' => get_static_option('events_page_'.$default_lang.'_name'),
    'product_mega_menu' => get_static_option('product_page_'.$default_lang.'_name'),
    'donation_mega_menu' => get_static_option('donation_page_'.$default_lang.'_name'),
    'blog_mega_menu' => get_static_option('blog_page_'.$default_lang.'_name'),
    'job_mega_menu' => get_static_option('career_with_us_page_'.$default_lang.'_name'),
    ];
@endphp
                                                        <ul class="page-list-ul">
                                                            @foreach($all_mega_menus as $key => $mega_m)
                                                                <li data-ptype="{{$key}}">
                                                                    <label class="menu-item-title">
                                                                        <input type="checkbox" class="menu-item-checkbox">
                                                                        {{$mega_m.__(' Mega Menu')}}
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="form-group">
                                                            <button type="button" id="add_dynamic_page_to_menu"
                                                                    class="btn btn-primary btn-xs mt-4 pr-4 pl-4 add_mega_menu_to_menu">{{__('Add MegaMenu')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="custom-links">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#custom-links-content"
                                                                aria-expanded="false"
                                                                aria-controls="custom-links-content">
                                                            {{__('Custom Links')}}
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="custom-links-content" class="collapse"
                                                     aria-labelledby="custom-links"
                                                     data-parent="#add_menu_item_accordion">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="custom_url"><strong>{{__("URL")}}</strong></label>
                                                            <input type="text" name="custom_url" id="custom_url"
                                                                   class="form-control"
                                                                   placeholder="{{__('https://')}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="custom_label_text"><strong>{{__("Link Text")}}</strong></label>
                                                            <input type="text" name="custom_label_text"
                                                                   id="custom_label_text" class="form-control"
                                                                   placeholder="{{__('label text')}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <button type="button" id="add_custom_links"
                                                                    class="btn btn-primary btn-xs mt-4 pr-4 pl-4">{{__('Add To Menu')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                   <div class="menu-structure-wrapper">
                                       <div class="card">
                                           <div class="card-header">
                                               <h3 class="title">{{__('Menu Structure')}}</h3>
                                           </div>
                                           <div class="card-body">
                                               <section id="drop_down_menu_builder_wrapper">
                                                   <div class="dd" id="nestable">
                                                       <ol class="dd-list">
                                                           @if(!empty($page_post->content))
                                                               {!! render_draggable_menu_by_id($page_post->id) !!}
                                                           @else
                                                               <li class="dd-item" data-id="1" data-purl="@url" data-pname="{{__('Home')}}" data-ptype="custom">
                                                                   <div class="dd-handle">
                                                                       {{__('Home')}}
                                                                   </div>
                                                                   <span class="remove_item">x</span>
                                                               </li>
                                                           @endif
                                                       </ol>
                                                   </div>
                                               </section><!-- END #demo -->
                                           </div>
                                       </div>
                                   </div>
                                    <div class="form-group">
                                        <button type="submit" id="menu_structure_submit_btn" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/backend/js/jquery.nestable.js')}}"></script>
    <script>
        $(document).ready(function () {


            $('#nestable').nestable({
                group: 1,
                maxDepth:3
            }).on('change', function (e) {
                // saveChangedValue();
            });

            $(document).on('click','.add_mega_menu_to_menu',function (e) {
                e.preventDefault();

                var allList = $(this).parent().prev().find('input[type="checkbox"]:checked');
                var draggAbleMenuWrap = $('#nestable > ol');

                $.each(allList,function (index,value) {
                    $(this).attr('checked',false);
                    var draggAbleMenuLength = $('#nestable ol li').length + 1;
                    var allDataAttr = '';
                    var menuType = $(this).parent().parent().data('ptype');
                    var itemSelectMarkup = '';
                    allDataAttr += ' data-ptype="'+menuType+'"';
                    var randomID = Math.floor((Math.random() * 99999999) + 1);
                    var oldRandomId  = randomID;
                    var AjaxRandomId  = randomID;
                    draggAbleMenuWrap.append('<li class="dd-item" data-uniqueid="'+oldRandomId+'" data-id="'+draggAbleMenuLength+'" '+ allDataAttr +'>\n' +
                        ' <div class="dd-handle">'+$(this).parent().text()+'</div>\n' +
                        '<span class="remove_item">x</span>'+
                        '<span class="expand"><i class="ti-angle-down"></i></span>'+
                        '<div class="dd-body hide">' +
                        '<p>loading items...</p>'+
                        '</div>'+
                        '</li>');

                        $.ajax({
                            type: 'POST',
                            url: "{{route('admin.mega.menu.item.select.markup')}}",
                            data:{
                                _token: "{{csrf_token()}}",
                                type : menuType,
                                menu_id : $('#menu_id').val(),
                            },
                            success:function (data) {
                                var html = data;
                                setTimeout(function () {
                                    $('#nestable > ol').find('li[data-uniqueid="'+AjaxRandomId+'"] .dd-body').html(html);
                                },1000);
                            }
                        });

                });

            });
            $(document).on('click','.add_page_to_menu',function (e) {
                e.preventDefault();
                //nestable
                var allList = $(this).parent().prev().find('input[type="checkbox"]:checked');
                var draggAbleMenuWrap = $('#nestable > ol');
                $.each(allList,function (index,value) {
                    $(this).attr('checked',false);
                    var draggAbleMenuLength = $('#nestable ol li').length + 1;
                    var allDataAttr = '';
                    var menuType = $(this).parent().parent().data('ptype');

                    if(menuType == 'static'){

                        var menuPslug = $(this).parent().parent().data('pslug');
                        var menuPname = $(this).parent().parent().data('pname');

                        allDataAttr += 'data-pname="'+menuPname+'"';
                        allDataAttr += ' data-pslug="'+menuPslug+'"';
                        allDataAttr += ' data-ptype="'+menuType+'"';

                    }else if(menuType == 'dynamic'){

                        var menuPid = $(this).parent().parent().data('pid');

                        allDataAttr += 'data-pid="'+menuPid+'"';
                        allDataAttr += ' data-ptype="'+menuType+'"';

                    }else if(menuType == 'custom'){

                        var menuPurl = $(this).parent().parent().data('purl');
                        var menuPName = $(this).parent().parent().data('pname');

                        allDataAttr += 'data-purl="'+menuPurl+'"';
                        allDataAttr += 'data-pname="'+menuPName+'"';
                        allDataAttr += ' data-ptype="'+menuType+'"';
                    }else{
                        var menuPid = $(this).parent().parent().data('pid');

                        allDataAttr += 'data-pid="'+menuPid+'"';
                        allDataAttr += ' data-ptype="'+menuType+'"';
                    }
                    draggAbleMenuWrap.append('<li class="dd-item" data-id="'+draggAbleMenuLength+'" '+ allDataAttr +'>\n' +
                        ' <div class="dd-handle">'+$(this).parent().text()+'</div>\n' +
                        '<span class="remove_item">x</span>'+
                        '<span class="expand"><i class="ti-angle-down"></i></span>'+
                        '<div class="dd-body hide">' +
                        '<input type="text" class="icon_picker" placeholder="eg: fas-fa-facebook"/>'+
                        '</div>'+
                        '</li>');
                });
            });

            $(document).on('click','#add_custom_links',function (e) {
                e.preventDefault();

                var draggAbleMenuWrap = $('#nestable > ol');

                    var draggAbleMenuLength = $('#nestable ol li').length + 1;

                    var menuType = $(this).parent().parent().data('ptype');
                    var menuName = $('#custom_label_text').val();//custom_label_text
                    var menuSlug = $('#custom_url').val();//custom_url

                    draggAbleMenuWrap.append('<li class="dd-item" data-id="'+draggAbleMenuLength+'" data-ptype="custom" data-purl="'+menuSlug+'" data-pname="'+menuName+'">\n' +
                        ' <div class="dd-handle">'+menuName+'</div>\n' +
                        '<span class="remove_item">x</span>'+
                        '<span class="expand"><i class="ti-angle-down"></i></span>'+
                        '<div class="dd-body hide"><input type="text" class="anchor_target" placeholder="eg: _blank"/><input type="text" class="icon_picker" placeholder="eg: fas-fa-facebook"/></div>'+
                        '</li>');
                $('#custom_label_text').val('');
                $('#custom_url').val('');
            });
            $(document).on('input','.icon_picker',function (e) {
                var el = $(this);
                var value = el.val();

                 if(value != '' ){
                    el.parent().parent().attr('data-icon',value);
                 }else{
                     el.parent().parent().removeAttr('data-icon');
                 }
            });
            $(document).on('input','.anchor_target',function (e) {
                var el = $(this);
                var value = el.val();

                if(value != '' ){
                    el.parent().parent().attr('data-antarget',value);
                }else{
                    el.parent().parent().removeAttr('data-antarget');
                }
            });

            $(document).on('click', '.remove_item', function(e) {
                $(this).parent().remove();
            });

            $('body').on('change','select[name="items_id"]',function (e) {
                e.preventDefault();
                var el = $(this);
                var item_id = $(this).val();
                if(item_id != '' ){
                    el.parent().parent().attr('data-items_id',item_id);
                }else{
                    el.parent().parent().removeAttr('data-items_id');
                }
            });

            $(document).on('click','#menu_structure_submit_btn',function (e) {
                e.preventDefault();
                var alldata = $('#nestable').nestable('serialize');
                $('#menu_content').val(JSON.stringify(alldata));
                $('#menu_update_form').submit();
            })

        });
    </script>
@endsection
