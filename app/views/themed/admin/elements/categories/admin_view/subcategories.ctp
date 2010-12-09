<?php if(!empty($category['Subcategories'])):?>
<div class="item">
<h3><?php echo sprintf('%s Subcategories',Configure::read('Category.alias')); ?></h3>
<ul class="sortable categories admin_list">
<?php foreach($category['Subcategories'] as $subcat):?>
<li id="Category_<?php echo $subcat['id']; ?>" class="<?php
$sortable_products_flags = array();
if($subcat['draft']) $sortable_products_flags[] = 'draft';
if($subcat['featured']) $sortable_products_flags[] = 'featured';
echo implode(" ",$sortable_products_flags);
?>">
<span><?php echo $html->link($subcat['title'],array('admin'=>true,'controller'=>'categories','action'=>'view',$subcat['id'])); ?></span>
<ul class="hook_menu">
<li><?php echo $this->element('edit_form',array('controller'=>'categories','id'=>$subcat['id'],'model'=>'Category','title'=>$subcat['title']))?></li>
<li><?php echo $this->element('delete_form',array('controller'=>'categories','model'=>'Category','id'=>$subcat['id'],'title'=>$subcat['title']))?></li>
</ul>
<?php if(!empty($subcat['Product'])):?>
<span class="children">
<i><?php echo sprintf('%s %s',count($subcat['Product']),Inflector::pluralize(Configure::read('Product.alias'))); ?></i>
</span>
<?php endif; ?>
</li>
<?php endforeach;?>
</ul>
</div>
<?php endif;?>
