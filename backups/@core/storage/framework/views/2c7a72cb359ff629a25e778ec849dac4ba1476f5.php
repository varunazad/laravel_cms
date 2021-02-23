<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('User Dashboard')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('frontend.partials.breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="login-page-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="message-show margin-top-10">
                        <?php echo $__env->make('backend.partials.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="user-dashboard-wrapper">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="mobile_nav">
                                <i class="fas fa-cogs"></i>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><?php echo e(__('Dashboard')); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="packages-orders-tab" data-toggle="pill" href="#packages-orders" role="tab" aria-selected="false"><?php echo e(__('Packages Orders')); ?></a>
                            </li>
                            <?php if(!empty(get_static_option('product_module_status'))): ?>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-product-tab" data-toggle="pill" href="#pills-product" role="tab" aria-controls="pills-product" aria-selected="false"><?php echo e(__('Product Orders')); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-downloads-tab" data-toggle="pill" href="#pills-downloads" role="tab" aria-controls="pills-downloads" aria-selected="false"><?php echo e(__('Downloads')); ?></a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty(get_static_option('events_module_status'))): ?>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-event-tab" data-toggle="pill" href="#pills-event" role="tab" aria-controls="pills-event" aria-selected="false"><?php echo e(__('Events')); ?></a>
                            </li>
                            <?php endif; ?>
                            <?php if(!empty(get_static_option('donations_module_status'))): ?>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-donation-tab" data-toggle="pill" href="#pills-donation" role="tab" aria-controls="pills-donation" aria-selected="false"><?php echo e(__('Donations')); ?></a>
                            </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link" id="edit-profile-tab" data-toggle="pill" href="#edit-profile" role="tab"  aria-selected="false"><?php echo e(__('Edit Profile')); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-edit-password-tab" data-toggle="pill" href="#edit-password" role="tab"aria-selected="false"><?php echo e(__('Change Password')); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"  href="<?php echo e(route('user.logout')); ?>"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <?php echo e(__('Logout')); ?>

                                </a>
                                <form id="logout-form" action="<?php echo e(route('user.logout')); ?>" method="POST" style="display: none;">
                                    <?php echo csrf_field(); ?>
                                </form>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <div class="row">
                                    <?php if(!empty(get_static_option('events_module_status'))): ?>
                                    <div class="col-lg-6">
                                        <div class="user-dashboard-card margin-bottom-30">
                                            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                                              <div class="content">
                                                  <h4 class="title"><?php echo e(__('Events Attend')); ?></h4>
                                                  <span class="number"><?php echo e(count($event_attendances)); ?></span>
                                              </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="col-lg-6">
                                        <div class="user-dashboard-card style-01">
                                            <div class="icon"><i class="fas fa-money-bill"></i></div>
                                           <div class="content">
                                               <h4 class="title"><?php echo e(__('Package Order')); ?></h4>
                                               <span class="number"><?php echo e(count($package_orders)); ?></span>
                                           </div>
                                        </div>
                                    </div>
                                    <?php if(!empty(get_static_option('product_module_status'))): ?>
                                    <div class="col-lg-6">
                                        <div class="user-dashboard-card">
                                            <div class="icon"><i class="fas fa-shopping-bag"></i></div>
                                            <div class="content">
                                                <h4 class="title"><?php echo e(__('Product Order')); ?></h4>
                                                <span class="number"><?php echo e(count($product_orders)); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(get_static_option('donations_module_status')): ?>
                                    <div class="col-lg-6">
                                        <div class="user-dashboard-card style-01">
                                            <div class="icon"><i class="fas fa-donate"></i></div>
                                            <div class="content">
                                                <h4 class="title"><?php echo e(__('Donations')); ?></h4>
                                                <span class="number"><?php echo e(count($donation)); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="tab-pane fade " id="packages-orders" role="tabpanel" aria-labelledby="packages-orders-tab">
                                <?php if(count($package_orders) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col"><?php echo e(__('Package Order Info')); ?></th>
                                            <th scope="col"><?php echo e(__('Payment Status')); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $package_orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                   <div class="user-dahsboard-order-info-wrap">
                                                       <h5 class="title"><?php echo e($data->package_name); ?></h5>
                                                       <div class="div">
                                                           <small class="d-block"><strong><?php echo e(__('Order ID:')); ?></strong> #<?php echo e($data->id); ?></small>
                                                           <small class="d-block"><strong><?php echo e(__('Package Price:')); ?></strong> <?php echo e(amount_with_currency_symbol($data->package_price)); ?></small>
                                                           <small class="d-block"><strong><?php echo e(__('Order Status:')); ?></strong>
                                                               <?php if($data->status == 'pending'): ?>
                                                                   <span class="alert alert-warning text-capitalize alert-sm alert-small"><?php echo e(__($data->status)); ?></span>
                                                               <?php elseif($data->status == 'cancel'): ?>
                                                                   <span class="alert alert-danger text-capitalize alert-sm alert-small"><?php echo e(__($data->status)); ?></span>
                                                               <?php elseif($data->status == 'in_progress'): ?>
                                                                   <span class="alert alert-info text-capitalize alert-sm alert-small"><?php echo e(str_replace('_',' ',__($data->status))); ?></span>
                                                               <?php else: ?>
                                                                   <span class="alert alert-success text-capitalize alert-sm alert-small"><?php echo e(__($data->status)); ?></span>
                                                               <?php endif; ?>
                                                           </small>

                                                           <small class="d-block"><strong><?php echo e(__('Date:')); ?></strong> <?php echo e(date_format($data->created_at,'D m Y')); ?></small>
                                                           <?php if($data->payment_status == 'complete'): ?>
                                                               <form action="<?php echo e(route('frontend.package.invoice.generate')); ?>"  method="post">
                                                                   <?php echo csrf_field(); ?>
                                                                   <input type="hidden" name="id" id="invoice_generate_order_field" value="<?php echo e($data->id); ?>">
                                                                   <button class="btn btn-secondary btn-xs btn-small margin-top-10" type="submit"><?php echo e(__('Invoice')); ?></button>
                                                               </form>
                                                           <?php endif; ?>
                                                       </div>
                                                   </div>
                                                </td>
                                                <td>
                                                    <?php if($data->payment_status == 'pending' && $data->status != 'cancel'): ?>
                                                        <span class="alert alert-warning text-capitalize alert-sm"><?php echo e($data->payment_status); ?></span>
                                                        <a href="<?php echo e(route('frontend.order.confirm',$data->id)); ?>" class="small-btn btn-boxed"><?php echo e(__('Pay Now')); ?></a>
                                                        <form action="<?php echo e(route('user.dashboard.package.order.cancel')); ?>" method="post">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="order_id" value="<?php echo e($data->id); ?>">
                                                            <button type="submit" class="small-btn btn-danger margin-top-10"><?php echo e(__('Cancel')); ?></button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="alert alert-success text-capitalize alert-sm" style="display: inline-block"><?php echo e($data->payment_status); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                    <div class="alert alert-warning"><?php echo e(__('No Order Found')); ?></div>
                                <?php endif; ?>
                                <div class="blog-pagination">
                                    <?php echo e($package_orders->links()); ?>

                                </div>
                            </div>
                            <?php if(!empty(get_static_option('product_module_status'))): ?>
                            <div class="tab-pane fade" id="pills-product" role="tabpanel" aria-labelledby="pills-product-tab">
                                <?php if(count($product_orders) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col"><?php echo e(__('Order Info')); ?></th>
                                            <th scope="col"><?php echo e(__('Payment Status')); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $product_orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <th scope="row">
                                                    <div class="user-dahsboard-order-info-wrap">
                                                        <small class="d-block"><strong><?php echo e(__('Order ID:')); ?></strong> #<?php echo e($data->id); ?></small>
                                                        <small class="d-block"><strong><?php echo e(__('Total Amount:')); ?></strong><?php echo e(amount_with_currency_symbol($data->total)); ?></small>
                                                        <small class="d-block"><strong><?php echo e(__('Payment Gateway:')); ?></strong><?php echo e(ucwords(str_replace('_',' ',$data->payment_gateway))); ?></small>
                                                        <small class="d-block"><strong><?php echo e(__('Order Status:')); ?></strong>
                                                            <?php if($data->status == 'pending'): ?>
                                                                <span class="alert alert-warning text-capitalize alert-sm alert-small"><?php echo e(__($data->status)); ?></span>
                                                            <?php elseif($data->status == 'cancel'): ?>
                                                                <span class="alert alert-danger text-capitalize alert-sm alert-small"><?php echo e(__($data->status)); ?></span>
                                                            <?php elseif($data->status == 'in_progress'): ?>
                                                                <span class="alert alert-info text-capitalize alert-sm alert-small"><?php echo e(str_replace('_',' ',__($data->status))); ?></span>
                                                            <?php else: ?>
                                                                <span class="alert alert-success text-capitalize alert-sm alert-small"><?php echo e(__($data->status)); ?></span>
                                                            <?php endif; ?>
                                                        </small>
                                                        <small class="d-block"><strong><?php echo e(__('Order Date:')); ?></strong> <?php echo e(date_format($data->created_at,'d M Y')); ?></small>
                                                        <?php if($data->payment_status == 'complete'): ?>
                                                        <form action="<?php echo e(route('frontend.product.invoice.generate')); ?>"  method="post">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="order_id" id="invoice_generate_order_field" value="<?php echo e($data->id); ?>">
                                                            <button class="btn btn-secondary btn-small" type="submit"><?php echo e(__('Invoice')); ?></button>
                                                        </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </th>
                                                <td>
                                                    <?php if($data->payment_status == 'pending' && $data->status != 'cancel'): ?>
                                                        <span class="alert alert-warning text-capitalize alert-sm margin-bottom-20"><?php echo e($data->payment_status); ?></span>
                                                        <?php if( $data->payment_gateway != 'cash_on_delivery' &&  $data->payment_gateway != 'manual_payment'): ?>
                                                            <form action="<?php echo e(route('frontend.products.checkout')); ?>" method="post" enctype="multipart/form-data">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="order_id" value="<?php echo e($data->id); ?>">
                                                                <input type="hidden" name="selected_payment_gateway" value="<?php echo e($data->payment_gateway); ?>">
                                                                <input type="hidden" name="subtotal" value="<?php echo e($data->subtotal); ?>">
                                                                <input type="hidden" name="total" value="<?php echo e($data->total); ?>">
                                                                <input type="hidden" name="billing_name" value="<?php echo e($data->billing_name); ?>">
                                                                <input type="hidden" name="billing_email" value="<?php echo e($data->billing_email); ?>">
                                                                <input type="hidden" name="billing_phone" value="<?php echo e($data->billing_phone); ?>">
                                                                <input type="hidden" name="billing_country" value="<?php echo e($data->billing_country); ?>">
                                                                <input type="hidden" name="billing_street_address" value="<?php echo e($data->billing_street_address); ?>">
                                                                <input type="hidden" name="billing_town" value="<?php echo e($data->billing_town); ?>">
                                                                <input type="hidden" name="billing_district" value="<?php echo e($data->billing_district); ?>">
                                                                <input type="hidden" name="billing_district" value="<?php echo e($data->billing_district); ?>">
                                                                <button type="submit" class="small-btn btn-boxed margin-top-20"><?php echo e(__('Pay Now')); ?></button>
                                                            </form>
                                                        <?php endif; ?>
                                                        <form action="<?php echo e(route('user.dashboard.product.order.cancel')); ?>" method="post">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="order_id" value="<?php echo e($data->id); ?>">
                                                            <button type="submit" class="small-btn btn-danger margin-top-10"><?php echo e(__('Cancel')); ?></button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="alert alert-success text-capitalize alert-sm" style="display: inline-block"><?php echo e($data->payment_status); ?></span>
                                                    <?php endif; ?>
                                                        <a href="<?php echo e(route('user.dashboard.product.order.view',$data->id)); ?>" target="_blank" class="small-btn btn-boxed margin-top-20"><?php echo e(__('View Order')); ?></a>
                                                </td>

                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                    <div class="alert alert-warning"><?php echo e(__('No Product Order Found')); ?></div>
                                <?php endif; ?>
                                <div class="blog-pagination">
                                    <?php echo e($product_orders->links()); ?>

                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty(get_static_option('donations_module_status'))): ?>
                            <div class="tab-pane fade" id="pills-donation" role="tabpanel" aria-labelledby="pills-donation-tab">
                                <?php if(count($donation) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col"><?php echo e(__('Donation Info')); ?></th>
                                            <th scope="col"><?php echo e(__('Payment Status')); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $donation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td scope="row">
                                                    <div class="user-dahsboard-order-info-wrap">
                                                        <h5 class="title">
                                                            <?php if(!empty($data->donation)): ?>
                                                                <a href="<?php echo e(route('frontend.donations.single',$data->donation->slug)); ?>"><?php echo e($data->donation->title); ?></a>
                                                            <?php else: ?>
                                                                <div class="alert alert-warning"><?php echo e(__('This donation is not available or removed')); ?></div>
                                                            <?php endif; ?>
                                                        </h5>
                                                        <small class="d-block"><strong><?php echo e(__('Donation ID:')); ?></strong> #<?php echo e($data->id); ?></small>
                                                        <small class="d-block"><strong><?php echo e(__('Amount:')); ?></strong> <?php echo e(amount_with_currency_symbol($data->amount)); ?></small>
                                                        <small class="d-block"><strong><?php echo e(__('Payment Gateway:')); ?></strong> <?php echo e(str_replace('_',' ',__($data->payment_gateway))); ?></small>
                                                        <small class="d-block"><strong><?php echo e(__('Date:')); ?></strong> <?php echo e(date_format($data->created_at,'d M Y')); ?></small>
                                                        <?php if(!empty($data->donation) && $data->status == 'complete'): ?>
                                                            <form action="<?php echo e(route('frontend.donation.invoice.generate')); ?>"  method="post">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="id" id="invoice_generate_order_field" value="<?php echo e($data->id); ?>">
                                                                <button class="btn btn-secondary btn-small" type="submit"><?php echo e(__('Invoice')); ?></button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if($data->status == 'pending'): ?>
                                                        <span class="alert alert-warning text-capitalize alert-sm alert-small"><?php echo e(__($data->status)); ?></span>
                                                        <?php if( $data->payment_gateway != 'manual_payment'): ?>
                                                            <form action="<?php echo e(route('frontend.donations.log.store')); ?>" method="post" enctype="multipart/form-data">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="order_id" value="<?php echo e($data->id); ?>" >
                                                                <input type="hidden" name="donation_id" value="<?php echo e($data->donation_id); ?>" >
                                                                <input type="hidden" name="amount" value="<?php echo e($data->amount); ?>">
                                                                <input type="hidden" name="name" value="<?php echo e($data->name); ?>" >
                                                                <input type="hidden" name="email" value="<?php echo e($data->email); ?>" >
                                                                <input type="hidden" name="selected_payment_gateway" value="<?php echo e($data->payment_gateway); ?>">
                                                                <button type="submit" class="small-btn btn-boxed margin-top-20"><?php echo e(__('Pay Now')); ?></button>
                                                            </form>
                                                        <?php endif; ?>
                                                        <form action="<?php echo e(route('user.dashboard.donation.order.cancel')); ?>" method="post">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="order_id" value="<?php echo e($data->id); ?>">
                                                            <button type="submit" class="small-btn btn-danger margin-top-10"><?php echo e(__('Cancel')); ?></button>
                                                        </form>
                                                    <?php elseif($data->status == 'cancel'): ?>
                                                        <span class="alert alert-danger text-capitalize alert-sm alert-small" style="display: inline-block"><?php echo e(__($data->status)); ?></span>
                                                    <?php else: ?>
                                                        <span class="alert alert-success text-capitalize alert-sm alert-small" style="display: inline-block"><?php echo e(__($data->status)); ?></span>
                                                    <?php endif; ?>

                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                    <div class="alert alert-warning"><?php echo e(__('No Donation Found')); ?></div>
                                <?php endif; ?>
                                <div class="blog-pagination">
                                    <?php echo e($donation->links()); ?>

                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if(!empty(get_static_option('events_module_status'))): ?>
                            <div class="tab-pane fade" id="pills-event" role="tabpanel" aria-labelledby="pills-event-tab">
                                <?php if(count($event_attendances) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col"><?php echo e(__('Event Booking Info')); ?></th>
                                            <th scope="col"><?php echo e(__('Payment Status')); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $event_attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td scope="row">
                                                    <div class="user-dahsboard-order-info-wrap">
                                                        <h5 class="title">
                                                            <?php if(!empty($data->event)): ?>
                                                                <a href="<?php echo e(route('frontend.events.single',$data->event->slug)); ?>"><?php echo e($data->event_name); ?></a>
                                                            <?php else: ?>
                                                                <div class="alert alert-warning"><?php echo e(__('This event is not available or removed')); ?></div>
                                                            <?php endif; ?>
                                                        </h5>
                                                        <small class="d-block"><strong><?php echo e(__('Attendance ID:')); ?></strong> #<?php echo e($data->id); ?></small>
                                                        <small class="d-block"><strong><?php echo e(__('Ticket Price:')); ?></strong> <?php echo e(amount_with_currency_symbol($data->event_cost)); ?></small>
                                                        <small class="d-block"><strong><?php echo e(__('Quantity:')); ?></strong> <?php echo e($data->quantity); ?></small>
                                                        <small class="d-block"><strong><?php echo e(__('Payment Gateway:')); ?></strong>
                                                        <?php
                                                        $custom_fields = unserialize($data->custom_fields);
                                                        $selected_payment_gateway = isset($custom_fields['selected_payment_gateway']) ? str_replace('_',' ',__($custom_fields['selected_payment_gateway'])) : '';
                                                        ?>
                                                            <?php echo e($selected_payment_gateway); ?>

                                                        </small>
                                                        <small class="d-block"><strong><?php echo e(__('Booking Status:')); ?></strong>
                                                            <?php if($data->status == 'pending'): ?>
                                                                <span class="alert alert-warning text-capitalize alert-sm alert-small" style="display: inline-block"><?php echo e(__($data->status)); ?></span>
                                                            <?php elseif($data->status == 'cancel'): ?>
                                                                <span class="alert alert-danger text-capitalize alert-sm alert-small" style="display: inline-block"><?php echo e(__($data->status)); ?></span>
                                                            <?php else: ?>
                                                                <span class="alert alert-success text-capitalize alert-sm alert-small" style="display: inline-block"><?php echo e(__($data->status)); ?></span>
                                                            <?php endif; ?>
                                                        </small>
                                                        <small class="d-block"><strong><?php echo e(__('Date:')); ?></strong> <?php echo e(date_format($data->created_at,'d M Y')); ?></small>
                                                        <?php if(!empty($data->event) && $data->payment_status == 'complete'): ?>
                                                            <form action="<?php echo e(route('frontend.event.invoice.generate')); ?>"  method="post">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="id" id="invoice_generate_order_field" value="<?php echo e($data->id); ?>">
                                                                <button class="btn btn-secondary" type="submit"><?php echo e(__('Invoice')); ?></button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if($data->payment_status == 'pending' && $data->status != 'cancel'): ?>
                                                        <span class="alert alert-warning text-capitalize alert-sm" style="display: inline-block"><?php echo e($data->payment_status); ?></span>
                                                        <a href="<?php echo e(route('frontend.event.booking.confirm',$data->id)); ?>" class="btn-boxed btn-small"><?php echo e(__('Pay Now')); ?></a>
                                                        <form action="<?php echo e(route('user.dashboard.event.order.cancel')); ?>" method="post">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="order_id" value="<?php echo e($data->id); ?>">
                                                            <button type="submit" class="small-btn btn-danger margin-top-10"><?php echo e(__('Cancel')); ?></button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="alert alert-success text-capitalize alert-sm" style="display: inline-block"><?php echo e($data->payment_status); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                    <div class="alert alert-warning"><?php echo e(__('No Event Found')); ?></div>
                                <?php endif; ?>
                                <div class="blog-pagination">
                                    <?php echo e($event_attendances->links()); ?>

                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty(get_static_option('product_module_status'))): ?>
                            <div class="tab-pane fade" id="pills-downloads" role="tabpanel" aria-labelledby="pills-downloads-tab">
                                <?php if(!empty($downloads)): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col"><?php echo e(__('Thumbnail')); ?></th>
                                            <th scope="col"><?php echo e(__('Product Info')); ?></th>
                                            <th scope="col"><?php echo e(__('Download')); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $downloads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <th>
                                                    <div class="thumb-wrap" style="max-width: 60px">
                                                        <?php echo render_image_markup_by_attachment_id($data['image']); ?>

                                                    </div>
                                                </th>
                                                <td>
                                                    <a href="<?php echo e(route('frontend.products.single',$data['slug'])); ?>"><h4 style="font-weight: 600;"><?php echo e($data['title']); ?></h4></a>
                                                   <div>
                                                       <small class="d-block"><strong><?php echo e(__('Order ID:')); ?></strong> <?php echo e($data['order_id']); ?></small>
                                                       <small class="d-block"><strong><?php echo e(__('Quantity:')); ?></strong> <?php echo e($data['quantity']); ?></small>
                                                       <small class="d-block"><strong><?php echo e(__('Purchased:')); ?></strong> <?php echo e(date_format($data['order_date'],'d M Y')); ?></small>
                                                   </div>
                                                </td>
                                                <td>
                                                    <a class="btn-boxed style-01 margin-bottom-10" href="<?php echo e(route('user.dashboard.download.file',$data['id'])); ?>"><?php echo e(__('Download File')); ?></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                    <div class="alert alert-warning"><?php echo e(__('No Downloads Found')); ?></div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            <div class="tab-pane fade" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
                                <div class="dashboard-form-wrapper">
                                    <h2 class="title"><?php echo e(__('Edit Profile')); ?></h2>
                                    <form action="<?php echo e(route('user.profile.update')); ?>" method="post" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-group">
                                            <label for="name"><?php echo e(__('Name')); ?></label>
                                            <input type="text" class="form-control" id="name" name="name" value="<?php echo e($user_details->name); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="email"><?php echo e(__('Email')); ?></label>
                                            <input type="text" class="form-control" id="email" name="email" value="<?php echo e($user_details->email); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone"><?php echo e(__('Phone')); ?></label>
                                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo e($user_details->phone); ?>">
                                        </div>
                                        <div class="form-group">
                                           <label for="country"><?php echo e(__('Country')); ?></label>
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
                                            <label for="state"><?php echo e(__('State')); ?></label>
                                            <input type="text" class="form-control" id="state" name="state" value="<?php echo e($user_details->state); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="city"><?php echo e(__('City')); ?></label>
                                            <input type="text" class="form-control" id="city" name="city" value="<?php echo e($user_details->city); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="zipcode"><?php echo e(__('Zipcode')); ?></label>
                                            <input type="text" class="form-control" id="zipcode" name="zipcode" value="<?php echo e($user_details->zipcode); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="address"><?php echo e(__('Address')); ?></label>
                                            <input type="text" class="form-control" id="address" name="address" value="<?php echo e($user_details->address); ?>">
                                        </div>

                                        <button type="submit" class="submit-btn dash-btn width-200"><?php echo e(__('Save changes')); ?></button>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="edit-password" role="tabpanel" aria-labelledby="pills-edit-password-tab">
                                <div class="dashboard-form-wrapper">
                                    <h2 class="title"><?php echo e(__('Change Password')); ?></h2>

                                    <form action="<?php echo e(route('user.password.change')); ?>" method="post" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-group">
                                            <label for="old_password"><?php echo e(__('Old Password')); ?></label>
                                            <input type="password" class="form-control" id="old_password" name="old_password"
                                                   placeholder="<?php echo e(__('Old Password')); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="password"><?php echo e(__('New Password')); ?></label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                   placeholder="<?php echo e(__('New Password')); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation"><?php echo e(__('Confirm Password')); ?></label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                   name="password_confirmation" placeholder="<?php echo e(__('Confirm Password')); ?>">
                                        </div>
                                        <button type="submit" class="submit-btn dash-btn width-200"><?php echo e(__('Save changes')); ?></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script>
        $(document).ready(function(){

            var selectdCountry = "<?php echo e($user_details->country); ?>";
            $('#country option[value="'+selectdCountry+'"]').attr('selected',true);

            $(document).on('click','.user-dashboard-wrapper > ul .mobile_nav',function (e){
               e.preventDefault();
                var el = $(this);

                el.parent().toggleClass('show');

            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.frontend-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/sharifur/Desktop/sharifur-backup/localhost/nexelit/@core/resources/views/frontend/user/dashboard/user-home.blade.php ENDPATH**/ ?>