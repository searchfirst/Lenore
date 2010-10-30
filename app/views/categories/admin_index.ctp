<h2><?php echo sprintf('%s Categories',Configure::read('Category.alias')); ?></h2>
<ul class="hook_menu">
<li><?php echo $this->element('admin/new_form',array('model'=>'Category','controller'=>'categories','l_title'=>'Product Category'));?></li>
</ul>
<div class="content">
<?php if(empty($categories)):?>
<p>No <?php echo Inflector::pluralize(Configure::read('Category.alias')); ?></p>
<?php else:?>
<ul class="sortable categories">
<?php foreach ($categories as $category): ?>
<li id="Category_<?php echo $category['Category']['id']; ?>" class="<?php
$sortable_categories_flags = array();
if($category['Category']['draft']) $sortable_categories_flags[] = 'draft';
if($category['Category']['featured']) $sortable_categories_flags[] = 'featured';
echo implode(" ",$sortable_categories_flags);
?>">
<span><?php echo $html->link($category['Category']['title'],array('admin'=>true,'controller'=>'categories','action'=>'view',$category['Category']['id'])); ?></span>
<ul class="hook_menu">
<li><?php echo $this->element('edit_form',array('id'=>$category['Category']['id'],'title'=>$category['Category']['title'],'controller'=>'categories','model'=>'Category','l_title'=>'Product Category'))?> </li>
<li><?php echo $this->element('delete_form',array('id'=>$category['Category']['id'],'title'=>$category['Category']['title'],'controller'=>'categories','model'=>'Category','l_title'=>'Product Category'))?> </li>
</ul>
<span class="children">
<?php if(!empty($category['Product'])): ?>
<i class="products"><?php echo sprintf('%s %s',count($category['Product']),Inflector::pluralize(Configure::read('Product.alias'))); ?></i>
<?php endif; ?>
<?php if(!empty($category['Subcategories'])): ?>
<i class="subcategories"><?php echo sprintf('%s Sub-%s',count($category['Subcategories']),Inflector::pluralize(Configure::read('Category.alias'))); ?></i>
<?php endif ?>
</span>
<span class="mover"></span>
</li>
<?php endforeach; ?>
</ul>
<?php endif;?>
</div>