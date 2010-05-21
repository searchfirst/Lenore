<h1>Contact Form</h1>

<h2>Cart</h2>

<ul>
<?php foreach ($cart as $hash => $item):?>
<li>
<?php echo $item['quantity'].'x '.$textAssistant->plainSnippet($item['name']) ?><?php if(!empty($item['options'])):?>, <?php echo implode(', ',array_values($item['options']));?><?php endif;?>
</li>
<?php endforeach;?>
<li><strong>Subtotal</strong>£<?php echo $subtotal; ?></li>
<li><strong>Delivery</strong>£<?php echo $delivery; ?></li>
<li><strong>Total</strong>£<?php echo $total;?></li>
</ul>

<h2>Details</h2>

<ul>
<?php if(!empty($email_data['Checkout']['name'])):?>
<li><strong>Name</strong>: <?php echo $email_data['Checkout']['name']?></li>
<?php endif;?>
<?php if(!empty($email_data['Checkout']['email'])):?>
<li><strong>Email</strong>: <?php echo $email_data['Checkout']['email']?></li>
<?php endif;?>
<?php if(!empty($email_data['Checkout']['telephone'])):?>
<li><strong>Phone</strong>: <?php echo $email_data['Checkout']['telephone']?></li>
<?php endif;?>
</ul>

<?php foreach($email_data['Checkout'] as $key=>$value):?>
<?php if(!preg_match('/name|email|telephone|card_number|valid_from|valid_to|security_code|issue_no/',$key)):?>
<h2><?php echo Inflector::humanize($key);?></h2>
<?php echo $textAssistant->htmlFormatted($value);?> 

<?php endif;?>
<?php endforeach;?>