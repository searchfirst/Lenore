<div class="item">
<h3><?php echo sprintf('%s',Inflector::pluralize(Configure::read('Product.alias'))) ?></h3>
<ul class="hook_menu">
<li><?php echo $html->link(sprintf('Add %s',Configure::read('Product.alias')),array('admin'=>true,'controller'=>'products','action'=>'add','?'=>array('data[Product][category_id]'=>$category['Category']['id']))); ?></li>
</ul>
<ul class="sortable products admin_list">
<?php if(empty($category['Product'])):?>
<li>No Products</li>
<?php else:?>
<?php foreach($category['Product'] as $product):?>
<li id="Product_<?php echo $product['id']; ?>" class="<?php
$sortable_products_flags = array();
if($product['draft']) $sortable_products_flags[] = 'draft';
if($product['featured']) $sortable_products_flags[] = 'featured';
echo implode(" ",$sortable_products_flags);
?>">
<span><?php echo $html->link($product['title'],array('admin'=>true,'controller'=>'products','action'=>'view',$product['id'])); ?></span>
<ul class="hook_menu">
<li><?php echo $this->element('edit_form',array('controller'=>'products','id'=>$product['id'],'model'=>'Product','title'=>$product['title']))?></li>
<li><?php echo $this->element('delete_form',array('controller'=>'products','model'=>'Product','id'=>$product['id'],'title'=>$product['title']))?></li>
</ul>
</li>
<?php endforeach;?>
<?php endif;?>
</ul>
</div>
