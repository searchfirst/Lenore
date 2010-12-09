<h2><?php echo sprintf('%s: %s',Configure::read('Product.alias'),$product['Product']['title']); ?></h2>
<ul class="hook_menu">
<li><?php echo $html->link(
	sprintf('Edit %s',Configure::read('Product.alias')),
	array('admin'=>true,'controller'=>'products','action'=>'edit',$product['Product']['id']),
	array('class'=>'edit button ajax-modal')
); ?></li>
<li><?php echo $html->link(
	sprintf('Delete %s',Configure::read('Product.alias')),
	array('admin'=>true,'controller'=>'products','action'=>'delete',$product['Product']['id']),
	array('class'=>'delete button ajax-modal')
); ?></li>
</ul>
<div class="content">
<div class="primary">
<?php echo $this->element('products/admin_view/sales'); ?> 
<?php echo $this->element('products/admin_view/content'); ?> 
</div>
<div class="secondary">
<?php echo $this->element('products/admin_view/information'); ?> 
<?php echo $this->element('products/admin_view/media'); ?> 
</div>
</div>
