<h2>Edit Product: <?php echo $textAssistant->sanitiseText($this->data['Product']['title']); ?></h2>
<div class="content">
<?php echo $form->create('Product',array('options'=>'file')); ?> 
<?php echo $form->input('Product.title',array('size'=>'40','maxlength'=>'150'));?> 
<?php echo $form->input('Product.description',array(
	'cols'=>'40',
	'rows'=>'20',
	'label'=>'Content',
	'class'=>'rich'
));?> 
<?php if(empty($this->data['Decorative'])):?>
<?php echo $form->hidden('Resource.1.type',array('value'=>Resource::$types['Decorative']))?> 
<?php echo $form->input('Resource.1.file',array('label'=>'Thumbnail Image','type'=>'file')); ?> 
<?php endif;?>
<fieldset><legend><?php echo sprintf('%s Options',Configure::read('Product.alias')); ?></legend>
<?php if(Configure::read('Product.sales_options')): ?>
<?php echo $form->input('Product.price',array('size'=>3,'maxlength'=>9)) ?>
<?php echo $form->input('Product.options',array('cols'=>'40','rows'=>'5','class'=>'plain'))?> 
<?php endif; ?>
<?php echo $form->input('Product.category_id',array('options'=>$categories,'label'=>sprintf('%s Category',Configure::read('Category.alias'))))?> 
</fieldset>
<fieldset><legend>Flags</legend>
<?php echo $form->input('Product.draft'); ?>
<?php echo $form->input('Product.featured'); ?> 
</fieldset>
<?php echo $form->hidden('Product.id')?> 
<?php echo $form->end('Save')?> 
</div>