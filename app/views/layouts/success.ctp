<div class="flash <?php echo isset($class)?$class:'success';?>">
<p><?php echo $content_for_layout;?></p>
<?php if(!isset($class) || ($class=='success')): ?>
<p>You may now <?php echo $html->link('proceed to the checkout',"https://{$_SERVER['HTTP_HOST']}/cart/checkout");?> 
or <a href="/cart/view">view the items in your cart</a>. You may also continue shopping using the menu on the left.</p>
<?php endif;?>
</div>