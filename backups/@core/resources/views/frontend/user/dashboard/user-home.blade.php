@extends('frontend.frontend-master')
@section('page-title')
    {{__('User Dashboard')}}
@endsection
@section('content')
    @include('frontend.partials.breadcrumb')
    <section class="login-page-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="message-show margin-top-10">
                        @include('backend.partials.message')
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
                    <div class="user-dashboard-wrapper">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="mobile_nav">
                                <i class="fas fa-cogs"></i>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">{{__('Dashboard')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="packages-orders-tab" data-toggle="pill" href="#packages-orders" role="tab" aria-selected="false">{{__('Packages Orders')}}</a>
                            </li>
                            @if(!empty(get_static_option('product_module_status')))
                            <li class="nav-item">
                                <a class="nav-link" id="pills-product-tab" data-toggle="pill" href="#pills-product" role="tab" aria-controls="pills-product" aria-selected="false">{{__('Product Orders')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-downloads-tab" data-toggle="pill" href="#pills-downloads" role="tab" aria-controls="pills-downloads" aria-selected="false">{{__('Downloads')}}</a>
                            </li>
                            @endif
                            @if(!empty(get_static_option('events_module_status')))
                            <li class="nav-item">
                                <a class="nav-link" id="pills-event-tab" data-toggle="pill" href="#pills-event" role="tab" aria-controls="pills-event" aria-selected="false">{{__('Events')}}</a>
                            </li>
                            @endif
                            @if(!empty(get_static_option('donations_module_status')))
                            <li class="nav-item">
                                <a class="nav-link" id="pills-donation-tab" data-toggle="pill" href="#pills-donation" role="tab" aria-controls="pills-donation" aria-selected="false">{{__('Donations')}}</a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" id="edit-profile-tab" data-toggle="pill" href="#edit-profile" role="tab"  aria-selected="false">{{__('Edit Profile')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-edit-password-tab" data-toggle="pill" href="#edit-password" role="tab"aria-selected="false">{{__('Change Password')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"  href="{{ route('user.logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <div class="row">
                                    @if(!empty(get_static_option('events_module_status')))
                                    <div class="col-lg-6">
                                        <div class="user-dashboard-card margin-bottom-30">
                                            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                                              <div class="content">
                                                  <h4 class="title">{{__('Events Attend')}}</h4>
                                                  <span class="number">{{count($event_attendances)}}</span>
                                              </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-lg-6">
                                        <div class="user-dashboard-card style-01">
                                            <div class="icon"><i class="fas fa-money-bill"></i></div>
                                           <div class="content">
                                               <h4 class="title">{{__('Package Order')}}</h4>
                                               <span class="number">{{count($package_orders)}}</span>
                                           </div>
                                        </div>
                                    </div>
                                    @if(!empty(get_static_option('product_module_status')))
                                    <div class="col-lg-6">
                                        <div class="user-dashboard-card">
                                            <div class="icon"><i class="fas fa-shopping-bag"></i></div>
                                            <div class="content">
                                                <h4 class="title">{{__('Product Order')}}</h4>
                                                <span class="number">{{count($product_orders)}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if(get_static_option('donations_module_status'))
                                    <div class="col-lg-6">
                                        <div class="user-dashboard-card style-01">
                                            <div class="icon"><i class="fas fa-donate"></i></div>
                                            <div class="content">
                                                <h4 class="title">{{__('Donations')}}</h4>
                                                <span class="number">{{count($donation)}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane fade " id="packages-orders" role="tabpanel" aria-labelledby="packages-orders-tab">
                                @if(count($package_orders) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{__('Package Order Info')}}</th>
                                            <th scope="col">{{__('Payment Status')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($package_orders as $data)
                                            <tr>
                                                <td>
                                                   <div class="user-dahsboard-order-info-wrap">
                                                       <h5 class="title">{{$data->package_name}}</h5>
                                                       <div class="div">
                                                           <small class="d-block"><strong>{{__('Order ID:')}}</strong> #{{$data->id}}</small>
                                                           <small class="d-block"><strong>{{__('Package Price:')}}</strong> {{amount_with_currency_symbol($data->package_price)}}</small>
                                                           <small class="d-block"><strong>{{__('Order Status:')}}</strong>
                                                               @if($data->status == 'pending')
                                                                   <span class="alert alert-warning text-capitalize alert-sm alert-small">{{__($data->status)}}</span>
                                                               @elseif($data->status == 'cancel')
                                                                   <span class="alert alert-danger text-capitalize alert-sm alert-small">{{__($data->status)}}</span>
                                                               @elseif($data->status == 'in_progress')
                                                                   <span class="alert alert-info text-capitalize alert-sm alert-small">{{str_replace('_',' ',__($data->status))}}</span>
                                                               @else
                                                                   <span class="alert alert-success text-capitalize alert-sm alert-small">{{__($data->status)}}</span>
                                                               @endif
                                                           </small>

                                                           <small class="d-block"><strong>{{__('Date:')}}</strong> {{date_format($data->created_at,'D m Y')}}</small>
                                                           @if($data->payment_status == 'complete')
                                                               <form action="{{route('frontend.package.invoice.generate')}}"  method="post">
                                                                   @csrf
                                                                   <input type="hidden" name="id" id="invoice_generate_order_field" value="{{$data->id}}">
                                                                   <button class="btn btn-secondary btn-xs btn-small margin-top-10" type="submit">{{__('Invoice')}}</button>
                                                               </form>
                                                           @endif
                                                       </div>
                                                   </div>
                                                </td>
                                                <td>
                                                    @if($data->payment_status == 'pending' && $data->status != 'cancel')
                                                        <span class="alert alert-warning text-capitalize alert-sm">{{$data->payment_status}}</span>
                                                        <a href="{{route('frontend.order.confirm',$data->id)}}" class="small-btn btn-boxed">{{__('Pay Now')}}</a>
                                                        <form action="{{route('user.dashboard.package.order.cancel')}}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="order_id" value="{{$data->id}}">
                                                            <button type="submit" class="small-btn btn-danger margin-top-10">{{__('Cancel')}}</button>
                                                        </form>
                                                    @else
                                                        <span class="alert alert-success text-capitalize alert-sm" style="display: inline-block">{{$data->payment_status}}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    <div class="alert alert-warning">{{__('No Order Found')}}</div>
                                @endif
                                <div class="blog-pagination">
                                    {{ $package_orders->links() }}
                                </div>
                            </div>
                            @if(!empty(get_static_option('product_module_status')))
                            <div class="tab-pane fade" id="pills-product" role="tabpanel" aria-labelledby="pills-product-tab">
                                @if(count($product_orders) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{__('Order Info')}}</th>
                                            <th scope="col">{{__('Payment Status')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($product_orders as $data)
                                            <tr>
                                                <th scope="row">
                                                    <div class="user-dahsboard-order-info-wrap">
                                                        <small class="d-block"><strong>{{__('Order ID:')}}</strong> #{{$data->id}}</small>
                                                        <small class="d-block"><strong>{{__('Total Amount:')}}</strong>{{amount_with_currency_symbol($data->total)}}</small>
                                                        <small class="d-block"><strong>{{__('Payment Gateway:')}}</strong>{{ucwords(str_replace('_',' ',$data->payment_gateway))}}</small>
                                                        <small class="d-block"><strong>{{__('Order Status:')}}</strong>
                                                            @if($data->status == 'pending')
                                                                <span class="alert alert-warning text-capitalize alert-sm alert-small">{{__($data->status)}}</span>
                                                            @elseif($data->status == 'cancel')
                                                                <span class="alert alert-danger text-capitalize alert-sm alert-small">{{__($data->status)}}</span>
                                                            @elseif($data->status == 'in_progress')
                                                                <span class="alert alert-info text-capitalize alert-sm alert-small">{{str_replace('_',' ',__($data->status))}}</span>
                                                            @else
                                                                <span class="alert alert-success text-capitalize alert-sm alert-small">{{__($data->status)}}</span>
                                                            @endif
                                                        </small>
                                                        <small class="d-block"><strong>{{__('Order Date:')}}</strong> {{date_format($data->created_at,'d M Y')}}</small>
                                                        @if($data->payment_status == 'complete')
                                                        <form action="{{route('frontend.product.invoice.generate')}}"  method="post">
                                                            @csrf
                                                            <input type="hidden" name="order_id" id="invoice_generate_order_field" value="{{$data->id}}">
                                                            <button class="btn btn-secondary btn-small" type="submit">{{__('Invoice')}}</button>
                                                        </form>
                                                        @endif
                                                    </div>
                                                </th>
                                                <td>
                                                    @if($data->payment_status == 'pending' && $data->status != 'cancel')
                                                        <span class="alert alert-warning text-capitalize alert-sm margin-bottom-20">{{$data->payment_status}}</span>
                                                        @if( $data->payment_gateway != 'cash_on_delivery' &&  $data->payment_gateway != 'manual_payment')
                                                            <form action="{{route('frontend.products.checkout')}}" method="post" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="order_id" value="{{$data->id}}">
                                                                <input type="hidden" name="selected_payment_gateway" value="{{$data->payment_gateway}}">
                                                                <input type="hidden" name="subtotal" value="{{$data->subtotal}}">
                                                                <input type="hidden" name="total" value="{{$data->total}}">
                                                                <input type="hidden" name="billing_name" value="{{$data->billing_name}}">
                                                                <input type="hidden" name="billing_email" value="{{$data->billing_email}}">
                                                                <input type="hidden" name="billing_phone" value="{{$data->billing_phone}}">
                                                                <input type="hidden" name="billing_country" value="{{$data->billing_country}}">
                                                                <input type="hidden" name="billing_street_address" value="{{$data->billing_street_address}}">
                                                                <input type="hidden" name="billing_town" value="{{$data->billing_town}}">
                                                                <input type="hidden" name="billing_district" value="{{$data->billing_district}}">
                                                                <input type="hidden" name="billing_district" value="{{$data->billing_district}}">
                                                                <button type="submit" class="small-btn btn-boxed margin-top-20">{{__('Pay Now')}}</button>
                                                            </form>
                                                        @endif
                                                        <form action="{{route('user.dashboard.product.order.cancel')}}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="order_id" value="{{$data->id}}">
                                                            <button type="submit" class="small-btn btn-danger margin-top-10">{{__('Cancel')}}</button>
                                                        </form>
                                                    @else
                                                        <span class="alert alert-success text-capitalize alert-sm" style="display: inline-block">{{$data->payment_status}}</span>
                                                    @endif
                                                        <a href="{{route('user.dashboard.product.order.view',$data->id)}}" target="_blank" class="small-btn btn-boxed margin-top-20">{{__('View Order')}}</a>
                                                </td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    <div class="alert alert-warning">{{__('No Product Order Found')}}</div>
                                @endif
                                <div class="blog-pagination">
                                    {{ $product_orders->links() }}
                                </div>
                            </div>
                            @endif
                            @if(!empty(get_static_option('donations_module_status')))
                            <div class="tab-pane fade" id="pills-donation" role="tabpanel" aria-labelledby="pills-donation-tab">
                                @if(count($donation) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{__('Donation Info')}}</th>
                                            <th scope="col">{{__('Payment Status')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($donation as $data)
                                            <tr>
                                                <td scope="row">
                                                    <div class="user-dahsboard-order-info-wrap">
                                                        <h5 class="title">
                                                            @if(!empty($data->donation))
                                                                <a href="{{route('frontend.donations.single',$data->donation->slug)}}">{{$data->donation->title}}</a>
                                                            @else
                                                                <div class="alert alert-warning">{{__('This donation is not available or removed')}}</div>
                                                            @endif
                                                        </h5>
                                                        <small class="d-block"><strong>{{__('Donation ID:')}}</strong> #{{$data->id}}</small>
                                                        <small class="d-block"><strong>{{__('Amount:')}}</strong> {{amount_with_currency_symbol($data->amount)}}</small>
                                                        <small class="d-block"><strong>{{__('Payment Gateway:')}}</strong> {{str_replace('_',' ',__($data->payment_gateway))}}</small>
                                                        <small class="d-block"><strong>{{__('Date:')}}</strong> {{date_format($data->created_at,'d M Y')}}</small>
                                                        @if(!empty($data->donation) && $data->status == 'complete')
                                                            <form action="{{route('frontend.donation.invoice.generate')}}"  method="post">
                                                                @csrf
                                                                <input type="hidden" name="id" id="invoice_generate_order_field" value="{{$data->id}}">
                                                                <button class="btn btn-secondary btn-small" type="submit">{{__('Invoice')}}</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($data->status == 'pending')
                                                        <span class="alert alert-warning text-capitalize alert-sm alert-small">{{__($data->status)}}</span>
                                                        @if( $data->payment_gateway != 'manual_payment')
                                                            <form action="{{route('frontend.donations.log.store')}}" method="post" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="order_id" value="{{$data->id}}" >
                                                                <input type="hidden" name="donation_id" value="{{$data->donation_id}}" >
                                                                <input type="hidden" name="amount" value="{{$data->amount}}">
                                                                <input type="hidden" name="name" value="{{$data->name}}" >
                                                                <input type="hidden" name="email" value="{{$data->email}}" >
                                                                <input type="hidden" name="selected_payment_gateway" value="{{$data->payment_gateway}}">
                                                                <button type="submit" class="small-btn btn-boxed margin-top-20">{{__('Pay Now')}}</button>
                                                            </form>
                                                        @endif
                                                        <form action="{{route('user.dashboard.donation.order.cancel')}}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="order_id" value="{{$data->id}}">
                                                            <button type="submit" class="small-btn btn-danger margin-top-10">{{__('Cancel')}}</button>
                                                        </form>
                                                    @elseif($data->status == 'cancel')
                                                        <span class="alert alert-danger text-capitalize alert-sm alert-small" style="display: inline-block">{{__($data->status)}}</span>
                                                    @else
                                                        <span class="alert alert-success text-capitalize alert-sm alert-small" style="display: inline-block">{{__($data->status)}}</span>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    <div class="alert alert-warning">{{__('No Donation Found')}}</div>
                                @endif
                                <div class="blog-pagination">
                                    {{ $donation->links() }}
                                </div>
                            </div>
                            @endif

                            @if(!empty(get_static_option('events_module_status')))
                            <div class="tab-pane fade" id="pills-event" role="tabpanel" aria-labelledby="pills-event-tab">
                                @if(count($event_attendances) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{__('Event Booking Info')}}</th>
                                            <th scope="col">{{__('Payment Status')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($event_attendances as $data)
                                            <tr>
                                                <td scope="row">
                                                    <div class="user-dahsboard-order-info-wrap">
                                                        <h5 class="title">
                                                            @if(!empty($data->event))
                                                                <a href="{{route('frontend.events.single',$data->event->slug)}}">{{$data->event_name}}</a>
                                                            @else
                                                                <div class="alert alert-warning">{{__('This event is not available or removed')}}</div>
                                                            @endif
                                                        </h5>
                                                        <small class="d-block"><strong>{{__('Attendance ID:')}}</strong> #{{$data->id}}</small>
                                                        <small class="d-block"><strong>{{__('Ticket Price:')}}</strong> {{amount_with_currency_symbol($data->event_cost)}}</small>
                                                        <small class="d-block"><strong>{{__('Quantity:')}}</strong> {{$data->quantity}}</small>
                                                        <small class="d-block"><strong>{{__('Payment Gateway:')}}</strong>
                                                        @php
                                                        $custom_fields = unserialize($data->custom_fields);
                                                        $selected_payment_gateway = isset($custom_fields['selected_payment_gateway']) ? str_replace('_',' ',__($custom_fields['selected_payment_gateway'])) : '';
                                                        @endphp
                                                            {{$selected_payment_gateway}}
                                                        </small>
                                                        <small class="d-block"><strong>{{__('Booking Status:')}}</strong>
                                                            @if($data->status == 'pending')
                                                                <span class="alert alert-warning text-capitalize alert-sm alert-small" style="display: inline-block">{{__($data->status)}}</span>
                                                            @elseif($data->status == 'cancel')
                                                                <span class="alert alert-danger text-capitalize alert-sm alert-small" style="display: inline-block">{{__($data->status)}}</span>
                                                            @else
                                                                <span class="alert alert-success text-capitalize alert-sm alert-small" style="display: inline-block">{{__($data->status)}}</span>
                                                            @endif
                                                        </small>
                                                        <small class="d-block"><strong>{{__('Date:')}}</strong> {{date_format($data->created_at,'d M Y')}}</small>
                                                        @if(!empty($data->event) && $data->payment_status == 'complete')
                                                            <form action="{{route('frontend.event.invoice.generate')}}"  method="post">
                                                                @csrf
                                                                <input type="hidden" name="id" id="invoice_generate_order_field" value="{{$data->id}}">
                                                                <button class="btn btn-secondary" type="submit">{{__('Invoice')}}</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($data->payment_status == 'pending' && $data->status != 'cancel')
                                                        <span class="alert alert-warning text-capitalize alert-sm" style="display: inline-block">{{$data->payment_status}}</span>
                                                        <a href="{{route('frontend.event.booking.confirm',$data->id)}}" class="btn-boxed btn-small">{{__('Pay Now')}}</a>
                                                        <form action="{{route('user.dashboard.event.order.cancel')}}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="order_id" value="{{$data->id}}">
                                                            <button type="submit" class="small-btn btn-danger margin-top-10">{{__('Cancel')}}</button>
                                                        </form>
                                                    @else
                                                        <span class="alert alert-success text-capitalize alert-sm" style="display: inline-block">{{$data->payment_status}}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    <div class="alert alert-warning">{{__('No Event Found')}}</div>
                                @endif
                                <div class="blog-pagination">
                                    {{ $event_attendances->links() }}
                                </div>
                            </div>
                            @endif
                            @if(!empty(get_static_option('product_module_status')))
                            <div class="tab-pane fade" id="pills-downloads" role="tabpanel" aria-labelledby="pills-downloads-tab">
                                @if(!empty($downloads))
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{__('Thumbnail')}}</th>
                                            <th scope="col">{{__('Product Info')}}</th>
                                            <th scope="col">{{__('Download')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($downloads as $data)
                                            <tr>
                                                <th>
                                                    <div class="thumb-wrap" style="max-width: 60px">
                                                        {!! render_image_markup_by_attachment_id($data['image']) !!}
                                                    </div>
                                                </th>
                                                <td>
                                                    <a href="{{route('frontend.products.single',$data['slug'])}}"><h4 style="font-weight: 600;">{{$data['title']}}</h4></a>
                                                   <div>
                                                       <small class="d-block"><strong>{{{__('Order ID:')}}}</strong> {{$data['order_id']}}</small>
                                                       <small class="d-block"><strong>{{{__('Quantity:')}}}</strong> {{$data['quantity']}}</small>
                                                       <small class="d-block"><strong>{{{__('Purchased:')}}}</strong> {{date_format($data['order_date'],'d M Y')}}</small>
                                                   </div>
                                                </td>
                                                <td>
                                                    <a class="btn-boxed style-01 margin-bottom-10" href="{{route('user.dashboard.download.file',$data['id'])}}">{{__('Download File')}}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    <div class="alert alert-warning">{{__('No Downloads Found')}}</div>
                                @endif
                            </div>
                            @endif
                            <div class="tab-pane fade" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
                                <div class="dashboard-form-wrapper">
                                    <h2 class="title">{{__('Edit Profile')}}</h2>
                                    <form action="{{route('user.profile.update')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="name">{{__('Name')}}</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{$user_details->name}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">{{__('Email')}}</label>
                                            <input type="text" class="form-control" id="email" name="email" value="{{$user_details->email}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">{{__('Phone')}}</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" value="{{$user_details->phone}}">
                                        </div>
                                        <div class="form-group">
                                           <label for="country">{{__('Country')}}</label>
                                            <select id="country" class="form-control" name="country">
                                                <option value="Afganistan">Afghanistan</option>
                                                <option value="Albania">Albania</option>
                                                <option value="Algeria">Algeria</option>
                                                <option value="American Samoa">American Samoa</option>
                                                <option value="Andorra">Andorra</option>
                                                <option value="Angola">Angola</option>
                                                <option value="Anguilla">Anguilla</option>
                                                <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                                <option value="Argentina">Argentina</option>
                                                <option value="Armenia">Armenia</option>
                                                <option value="Aruba">Aruba</option>
                                                <option value="Australia">Australia</option>
                                                <option value="Austria">Austria</option>
                                                <option value="Azerbaijan">Azerbaijan</option>
                                                <option value="Bahamas">Bahamas</option>
                                                <option value="Bahrain">Bahrain</option>
                                                <option value="Bangladesh">Bangladesh</option>
                                                <option value="Barbados">Barbados</option>
                                                <option value="Belarus">Belarus</option>
                                                <option value="Belgium">Belgium</option>
                                                <option value="Belize">Belize</option>
                                                <option value="Benin">Benin</option>
                                                <option value="Bermuda">Bermuda</option>
                                                <option value="Bhutan">Bhutan</option>
                                                <option value="Bolivia">Bolivia</option>
                                                <option value="Bonaire">Bonaire</option>
                                                <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                                <option value="Botswana">Botswana</option>
                                                <option value="Brazil">Brazil</option>
                                                <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                                <option value="Brunei">Brunei</option>
                                                <option value="Bulgaria">Bulgaria</option>
                                                <option value="Burkina Faso">Burkina Faso</option>
                                                <option value="Burundi">Burundi</option>
                                                <option value="Cambodia">Cambodia</option>
                                                <option value="Cameroon">Cameroon</option>
                                                <option value="Canada">Canada</option>
                                                <option value="Canary Islands">Canary Islands</option>
                                                <option value="Cape Verde">Cape Verde</option>
                                                <option value="Cayman Islands">Cayman Islands</option>
                                                <option value="Central African Republic">Central African Republic</option>
                                                <option value="Chad">Chad</option>
                                                <option value="Channel Islands">Channel Islands</option>
                                                <option value="Chile">Chile</option>
                                                <option value="China">China</option>
                                                <option value="Christmas Island">Christmas Island</option>
                                                <option value="Cocos Island">Cocos Island</option>
                                                <option value="Colombia">Colombia</option>
                                                <option value="Comoros">Comoros</option>
                                                <option value="Congo">Congo</option>
                                                <option value="Cook Islands">Cook Islands</option>
                                                <option value="Costa Rica">Costa Rica</option>
                                                <option value="Cote DIvoire">Cote DIvoire</option>
                                                <option value="Croatia">Croatia</option>
                                                <option value="Cuba">Cuba</option>
                                                <option value="Curaco">Curacao</option>
                                                <option value="Cyprus">Cyprus</option>
                                                <option value="Czech Republic">Czech Republic</option>
                                                <option value="Denmark">Denmark</option>
                                                <option value="Djibouti">Djibouti</option>
                                                <option value="Dominica">Dominica</option>
                                                <option value="Dominican Republic">Dominican Republic</option>
                                                <option value="East Timor">East Timor</option>
                                                <option value="Ecuador">Ecuador</option>
                                                <option value="Egypt">Egypt</option>
                                                <option value="El Salvador">El Salvador</option>
                                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                <option value="Eritrea">Eritrea</option>
                                                <option value="Estonia">Estonia</option>
                                                <option value="Ethiopia">Ethiopia</option>
                                                <option value="Falkland Islands">Falkland Islands</option>
                                                <option value="Faroe Islands">Faroe Islands</option>
                                                <option value="Fiji">Fiji</option>
                                                <option value="Finland">Finland</option>
                                                <option value="France">France</option>
                                                <option value="French Guiana">French Guiana</option>
                                                <option value="French Polynesia">French Polynesia</option>
                                                <option value="French Southern Ter">French Southern Ter</option>
                                                <option value="Gabon">Gabon</option>
                                                <option value="Gambia">Gambia</option>
                                                <option value="Georgia">Georgia</option>
                                                <option value="Germany">Germany</option>
                                                <option value="Ghana">Ghana</option>
                                                <option value="Gibraltar">Gibraltar</option>
                                                <option value="Great Britain">Great Britain</option>
                                                <option value="Greece">Greece</option>
                                                <option value="Greenland">Greenland</option>
                                                <option value="Grenada">Grenada</option>
                                                <option value="Guadeloupe">Guadeloupe</option>
                                                <option value="Guam">Guam</option>
                                                <option value="Guatemala">Guatemala</option>
                                                <option value="Guinea">Guinea</option>
                                                <option value="Guyana">Guyana</option>
                                                <option value="Haiti">Haiti</option>
                                                <option value="Hawaii">Hawaii</option>
                                                <option value="Honduras">Honduras</option>
                                                <option value="Hong Kong">Hong Kong</option>
                                                <option value="Hungary">Hungary</option>
                                                <option value="Iceland">Iceland</option>
                                                <option value="Indonesia">Indonesia</option>
                                                <option value="India">India</option>
                                                <option value="Iran">Iran</option>
                                                <option value="Iraq">Iraq</option>
                                                <option value="Ireland">Ireland</option>
                                                <option value="Isle of Man">Isle of Man</option>
                                                <option value="Israel">Israel</option>
                                                <option value="Italy">Italy</option>
                                                <option value="Jamaica">Jamaica</option>
                                                <option value="Japan">Japan</option>
                                                <option value="Jordan">Jordan</option>
                                                <option value="Kazakhstan">Kazakhstan</option>
                                                <option value="Kenya">Kenya</option>
                                                <option value="Kiribati">Kiribati</option>
                                                <option value="Korea North">Korea North</option>
                                                <option value="Korea Sout">Korea South</option>
                                                <option value="Kuwait">Kuwait</option>
                                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                <option value="Laos">Laos</option>
                                                <option value="Latvia">Latvia</option>
                                                <option value="Lebanon">Lebanon</option>
                                                <option value="Lesotho">Lesotho</option>
                                                <option value="Liberia">Liberia</option>
                                                <option value="Libya">Libya</option>
                                                <option value="Liechtenstein">Liechtenstein</option>
                                                <option value="Lithuania">Lithuania</option>
                                                <option value="Luxembourg">Luxembourg</option>
                                                <option value="Macau">Macau</option>
                                                <option value="Macedonia">Macedonia</option>
                                                <option value="Madagascar">Madagascar</option>
                                                <option value="Malaysia">Malaysia</option>
                                                <option value="Malawi">Malawi</option>
                                                <option value="Maldives">Maldives</option>
                                                <option value="Mali">Mali</option>
                                                <option value="Malta">Malta</option>
                                                <option value="Marshall Islands">Marshall Islands</option>
                                                <option value="Martinique">Martinique</option>
                                                <option value="Mauritania">Mauritania</option>
                                                <option value="Mauritius">Mauritius</option>
                                                <option value="Mayotte">Mayotte</option>
                                                <option value="Mexico">Mexico</option>
                                                <option value="Midway Islands">Midway Islands</option>
                                                <option value="Moldova">Moldova</option>
                                                <option value="Monaco">Monaco</option>
                                                <option value="Mongolia">Mongolia</option>
                                                <option value="Montserrat">Montserrat</option>
                                                <option value="Morocco">Morocco</option>
                                                <option value="Mozambique">Mozambique</option>
                                                <option value="Myanmar">Myanmar</option>
                                                <option value="Nambia">Nambia</option>
                                                <option value="Nauru">Nauru</option>
                                                <option value="Nepal">Nepal</option>
                                                <option value="Netherland Antilles">Netherland Antilles</option>
                                                <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                                <option value="Nevis">Nevis</option>
                                                <option value="New Caledonia">New Caledonia</option>
                                                <option value="New Zealand">New Zealand</option>
                                                <option value="Nicaragua">Nicaragua</option>
                                                <option value="Niger">Niger</option>
                                                <option value="Nigeria">Nigeria</option>
                                                <option value="Niue">Niue</option>
                                                <option value="Norfolk Island">Norfolk Island</option>
                                                <option value="Norway">Norway</option>
                                                <option value="Oman">Oman</option>
                                                <option value="Pakistan">Pakistan</option>
                                                <option value="Palau Island">Palau Island</option>
                                                <option value="Palestine">Palestine</option>
                                                <option value="Panama">Panama</option>
                                                <option value="Papua New Guinea">Papua New Guinea</option>
                                                <option value="Paraguay">Paraguay</option>
                                                <option value="Peru">Peru</option>
                                                <option value="Phillipines">Philippines</option>
                                                <option value="Pitcairn Island">Pitcairn Island</option>
                                                <option value="Poland">Poland</option>
                                                <option value="Portugal">Portugal</option>
                                                <option value="Puerto Rico">Puerto Rico</option>
                                                <option value="Qatar">Qatar</option>
                                                <option value="Republic of Montenegro">Republic of Montenegro</option>
                                                <option value="Republic of Serbia">Republic of Serbia</option>
                                                <option value="Reunion">Reunion</option>
                                                <option value="Romania">Romania</option>
                                                <option value="Russia">Russia</option>
                                                <option value="Rwanda">Rwanda</option>
                                                <option value="St Barthelemy">St Barthelemy</option>
                                                <option value="St Eustatius">St Eustatius</option>
                                                <option value="St Helena">St Helena</option>
                                                <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                                <option value="St Lucia">St Lucia</option>
                                                <option value="St Maarten">St Maarten</option>
                                                <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                                <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                                <option value="Saipan">Saipan</option>
                                                <option value="Samoa">Samoa</option>
                                                <option value="Samoa American">Samoa American</option>
                                                <option value="San Marino">San Marino</option>
                                                <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                                <option value="Saudi Arabia">Saudi Arabia</option>
                                                <option value="Senegal">Senegal</option>
                                                <option value="Seychelles">Seychelles</option>
                                                <option value="Sierra Leone">Sierra Leone</option>
                                                <option value="Singapore">Singapore</option>
                                                <option value="Slovakia">Slovakia</option>
                                                <option value="Slovenia">Slovenia</option>
                                                <option value="Solomon Islands">Solomon Islands</option>
                                                <option value="Somalia">Somalia</option>
                                                <option value="South Africa">South Africa</option>
                                                <option value="Spain">Spain</option>
                                                <option value="Sri Lanka">Sri Lanka</option>
                                                <option value="Sudan">Sudan</option>
                                                <option value="Suriname">Suriname</option>
                                                <option value="Swaziland">Swaziland</option>
                                                <option value="Sweden">Sweden</option>
                                                <option value="Switzerland">Switzerland</option>
                                                <option value="Syria">Syria</option>
                                                <option value="Tahiti">Tahiti</option>
                                                <option value="Taiwan">Taiwan</option>
                                                <option value="Tajikistan">Tajikistan</option>
                                                <option value="Tanzania">Tanzania</option>
                                                <option value="Thailand">Thailand</option>
                                                <option value="Togo">Togo</option>
                                                <option value="Tokelau">Tokelau</option>
                                                <option value="Tonga">Tonga</option>
                                                <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                                <option value="Tunisia">Tunisia</option>
                                                <option value="Turkey">Turkey</option>
                                                <option value="Turkmenistan">Turkmenistan</option>
                                                <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                                <option value="Tuvalu">Tuvalu</option>
                                                <option value="Uganda">Uganda</option>
                                                <option value="United Kingdom">United Kingdom</option>
                                                <option value="Ukraine">Ukraine</option>
                                                <option value="United Arab Erimates">United Arab Emirates</option>
                                                <option value="United States of America">United States of America</option>
                                                <option value="Uraguay">Uruguay</option>
                                                <option value="Uzbekistan">Uzbekistan</option>
                                                <option value="Vanuatu">Vanuatu</option>
                                                <option value="Vatican City State">Vatican City State</option>
                                                <option value="Venezuela">Venezuela</option>
                                                <option value="Vietnam">Vietnam</option>
                                                <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                                <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                                <option value="Wake Island">Wake Island</option>
                                                <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                                <option value="Yemen">Yemen</option>
                                                <option value="Zaire">Zaire</option>
                                                <option value="Zambia">Zambia</option>
                                                <option value="Zimbabwe">Zimbabwe</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="state">{{__('State')}}</label>
                                            <input type="text" class="form-control" id="state" name="state" value="{{$user_details->state}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="city">{{__('City')}}</label>
                                            <input type="text" class="form-control" id="city" name="city" value="{{$user_details->city}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="zipcode">{{__('Zipcode')}}</label>
                                            <input type="text" class="form-control" id="zipcode" name="zipcode" value="{{$user_details->zipcode}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="address">{{__('Address')}}</label>
                                            <input type="text" class="form-control" id="address" name="address" value="{{$user_details->address}}">
                                        </div>

                                        <button type="submit" class="submit-btn dash-btn width-200">{{__('Save changes')}}</button>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="edit-password" role="tabpanel" aria-labelledby="pills-edit-password-tab">
                                <div class="dashboard-form-wrapper">
                                    <h2 class="title">{{__('Change Password')}}</h2>

                                    <form action="{{route('user.password.change')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="old_password">{{__('Old Password')}}</label>
                                            <input type="password" class="form-control" id="old_password" name="old_password"
                                                   placeholder="{{__('Old Password')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="password">{{__('New Password')}}</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                   placeholder="{{__('New Password')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation">{{__('Confirm Password')}}</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                   name="password_confirmation" placeholder="{{__('Confirm Password')}}">
                                        </div>
                                        <button type="submit" class="submit-btn dash-btn width-200">{{__('Save changes')}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){

            var selectdCountry = "{{$user_details->country}}";
            $('#country option[value="'+selectdCountry+'"]').attr('selected',true);

            $(document).on('click','.user-dashboard-wrapper > ul .mobile_nav',function (e){
               e.preventDefault();
                var el = $(this);

                el.parent().toggleClass('show');

            });

        });
    </script>
@endsection
