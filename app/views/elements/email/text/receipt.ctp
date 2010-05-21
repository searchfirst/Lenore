CONTACT FORM
============

CART
----

<?php foreach ($cart as $hash => $item):?>
<?php echo $item['quantity'].'x '.$textAssistant->plainSnippet($item['name']) ?><?php if(!empty($item['options'])):?>, <?php echo implode(', ',array_values($item['options']));?>
<?php endif;?><?php endforeach;?>
----
Subtotal £<?php echo $subtotal; ?>
Delivery £<?php echo $delivery; ?>
Total £<?php echo $total;?>

DETAILS
-------

<?php if(!empty($email_data['Checkout']['name'])):?>
NAME: <?php echo $email_data['Checkout']['name']?> 
<?php endif;?>
<?php if(!empty($email_data['Checkout']['email'])):?>
EMAIL: <?php echo $email_data['Checkout']['email']?> 
<?php endif;?>
<?php if(!empty($email_data['Checkout']['telephone'])):?>
PHONE: <?php echo $email_data['Checkout']['telephone']?> 
<?php endif;?>

<?php foreach($email_data['Checkout'] as $key=>$value):?>
<?php if(!preg_match('/name|email|telephone|card_number|valid_from|valid_to|security_code|issue_no/',$key)):?>
<?php echo Inflector::humanize($key);?> 
<?php echo str_repeat('-',strlen($key));?> 

<?php echo $value;?> 

<?php endif;?>
<?php endforeach;?>