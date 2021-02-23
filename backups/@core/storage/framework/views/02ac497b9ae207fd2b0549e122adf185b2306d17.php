<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo e(get_static_option('site_'.get_user_lang().'_title')); ?> - <?php echo e(get_static_option('site_'.get_user_lang().'_tag_line')); ?></title>
</head>

<body>
<?php echo e(__('Redirecting Please Wait..')); ?>

<form action="<?php echo e(get_paypal_form_url()); ?>" method="post" id="payment_form">
    <input type="hidden" name="cmd" value="_xclick"/>
    <input type="hidden" name="business" value="<?php echo e($paypal_details['business']); ?>"/>
    <input type="hidden" name="cbt" value="<?php echo e($paypal_details['cbt']); ?>"/>
    <input type="hidden" name="currency_code" value="<?php echo e($paypal_details['currency_code']); ?>"/>
    <input type="hidden" name="quantity" value="1"/>
    <input type="hidden" name="item_name" value="<?php echo e($paypal_details['item_name']); ?>"/>
    <input type="hidden" name="custom" value="<?php echo e($paypal_details['custom']); ?>"/>
    <input type="hidden" name="amount" value="<?php echo e($paypal_details['amount']); ?>"/>
    <input type="hidden" name="return" value="<?php echo e($paypal_details['return']); ?>"/>
    <input type="hidden" name="cancel_return" value="<?php echo e($paypal_details['cancel_return']); ?>"/>
    <input type="hidden" name="notify_url" value="<?php echo e($paypal_details['notify_url']); ?>"/>
</form>

<script>
    document.getElementById("payment_form").submit();
</script>
</body>

</html>
    
    
    <?php /**PATH /Users/sharifur/Desktop/sharifur-backup/localhost/nexelit/@core/resources/views/frontend/payment/paypal.blade.php ENDPATH**/ ?>