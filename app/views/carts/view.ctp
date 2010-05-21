<h2><?php echo $textAssistant->sanitiseText($this->pageTitle);?></h2>
<?php pr($cart) ?>
<?php if (!empty($cart) && $subtotal>0):?>
<ul class="cart items">
<?php foreach ($cart as $hash => $item):?>
<li>
<span class="left">
<form method="post" action="<?php echo $html->url(array('controller'=>'Carts','action'=>'update'));?>" class="inline-left">
<?php echo $form->input('typehash',array('type'=>'hidden','value'=>$hash)) ?>
<?php echo $form->input('quantity',array('label'=>false,'div'=>false,'size'=>'1','maxlength'=>'3','value'=>$item['quantity'])) ?>
<input type="submit" value="Update" title="Update quantity" />
</form>
<?php echo 'x '.$textAssistant->plainSnippet($item['name']) ?>
<?php if(!empty($item['options'])):?>
, <?php echo implode(', ',array_values($item['options']));?>
<?php endif;?></span>
<form method="post" action="<?php echo $html->url(array('controller'=>'Carts','action'=>'remove'));?>" class="inline">
<span>£<?php print number_format(($item['Product']['price'] * $item['quantity']),2) ?></span>
<?php echo $form->input('typehash',array('type'=>'hidden','value'=>$hash));?> 
<?php echo $form->submit('Remove') ?>
</form>
<b class="clearing"></b>
</li>
<?php endforeach;?>
<li><span class="left">Subtotal</span> <span class="right">£<?php echo number_format($subtotal,2);?></span><b class="clearing"></b></li>
<?php if($delivery):?>
<li><span class="left">Delivery</span> <span class="right">£<?php echo number_format($delivery,2);?></span><b class="clearing"></b></li>
<?php endif;?>
<?php if($vat):?>
<li><span class="left">VAT</span> <span class="right">£<?php echo number_format($vat,2);?></span><b class="clearing"></b></li>
<?php endif;?>
<li class="carttotal"><span class="left">Total</span><span class="right">£<?php echo number_format($total,2);?></span><b class="clearing"></b></li>
</ul>
<form class="inline" method="post" action="<?php echo $html->url(array('controller'=>'Carts','action'=>'clear'));?>">
<?php echo $form->submit('Empty Cart');?>
</form>
<form class="inline" method="post" action="<?php echo $html->url(array('controller'=>'Carts','action'=>'checkout'));?>">
<?php echo $form->submit('Checkout');?>
</form>
<?php else:?>
<p>Shopping cart is empty</p>
<?php endif;?>