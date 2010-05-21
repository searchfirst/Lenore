<h2><?php echo $textAssistant->sanitiseText($this->pageTitle);?></h2>
<p><span style="float:left;margin-right:5px;margin-bottom:5px"><script src="https://siteseal.thawte.com/cgi/server/thawte_seal_generator.exe"></script></span>
<strong>Secure transactions for this website are done using a Thawte certificate to ensure secure transmission of your information. Click on the image to the left to verify our certification.</strong></p>
<br class="clearing" />
<?php if (!empty($cart) && $subtotal>0):?>
<ul class="cart items">
<?php foreach ($cart as $hash => $item):?>
<li>
<span class="left"><?php echo $item['quantity'].'x '.$textAssistant->plainSnippet($item['name']) ?>
<?php if(!empty($item['options'])):?>
, <?php echo implode(', ',array_values($item['options']));?>
<?php endif;?></span>
<b class="clearing"></b>
</li>
<?php endforeach;?>
<li class="carttotal"><span class="left">Total</span><span class="right">£<?php echo number_format($total,2);?></span><b class="clearing"></b></li>
</ul>
<form action="<?php echo $html->url(array('controller'=>'Carts','action'=>'checkout')); ?>" method="post" accept-charset="UTF-8">
<fieldset><legend>Delivery Address</legend>
<?php echo $form->input('Checkout.name',array('size'=>'20','error'=>'You must give your name.'))?> 
<?php echo $form->input('Checkout.email',array('size'=>'20','error'=>'You must give a valid email address.'))?> 
<?php echo $form->input('Checkout.telephone', array('size'=>'20','error'=>'You must give a valid phone number.'))?> 
<?php echo $form->input('Checkout.address', array('rows'=>4,'cols'=>20,'error'=>'You must give a delivery address.'))?> 
<?php echo $form->input('Checkout.post_code', array('size'=>'20','error'=>'You must give a valid post code.'))?> 
</fieldset>
<fieldset><legend>Payment Details</legend>
<?php echo $form->input('Checkout.name_on_card', array('size'=>'20','error'=>'You must give a valid name.'))?> 
<?php echo $form->input('Checkout.carholder_address', array('rows'=>4,'cols'=>20))?> 
<?php echo $form->input('Checkout.cardholder_post_code', array('size'=>'20'))?> 
<?php echo $form->input('Checkout.card_type', array('options'=>$cardTypes,'empty'=>true,'error'=>'You must pick a card type.'))?> 
<?php echo $form->input('Checkout.card_number', array('size'=>'20','maxlength'=>16,'error'=>'You must give a valid card number.'))?> 
<?php echo $form->input('Checkout.security_code', array('size'=>'20','error'=>'You must give a valid security code.'))?> 
<?php echo $form->input('Checkout.issue_no', array('size'=>'20','error'=>'You must give a valid issue number.'))?> 
<?php echo $form->input('Checkout.valid_from', array('size'=>'20','error'=>'You must give a valid date.'))?> 
<?php echo $form->input('Checkout.valid_to', array('size'=>'20','error'=>'You must give a valid date.'))?> 
</fieldset>
<?php echo $form->submit('Complete Order',array('div'=>false))?> 
</form>
<?php else:?>
<p>Shopping cart is empty</p>
<?php endif;?>