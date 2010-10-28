<h2><?php echo sprintf('%s Category: %s',Configure::read('Category.alias'),$category['Category']['title']); ?></h2>
<ul class="hook_menu">
<li><?php echo $html->link(
	sprintf('Edit %s Category',Configure::read('Category.alias')),
	array('admin'=>true,'controller'=>'categories','action'=>'edit',$category['Category']['id']),
	array('class'=>'edit button')
); ?></li>
<li><?php echo $html->link(
	sprintf('Delete %s Category',Configure::read('Category.alias')),
	array('admin'=>true,'controller'=>'categories','action'=>'delete',$category['Category']['id']),
	array('class'=>'delete button')
); ?></li>
</ul>
<div class="content">
<div class="primary">
<?php echo $this->element('categories/admin_view/subcategories'); ?> 
<?php echo $this->element('categories/admin_view/products'); ?> 
<?php echo $this->element('categories/admin_view/content'); ?> 
</div>
<div class="secondary">
<?php echo $this->element('categories/admin_view/information'); ?> 
<?php echo $this->element('categories/admin_view/media'); ?> 
</div>
</div>