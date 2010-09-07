<h2><?php echo sprintf('Add %s',Configure::read('Product.alias'));?></h2>
<div class="content">
<?php echo $form->create('Product',array('type'=>'file'));?> 
<?php echo $form->input('Product.title', array('size'=>'40','maxlength'=>'150'));?> 
<?php echo $form->input('Product.description',array(
	'cols'=>'40',
	'rows'=>'20',
	'value'=>$this->element('item_templates/new_product'),
	'label'=>'Content'
));?> 
<?php echo $form->input('Product.price',array('size'=>3,'maxlength'=>9));?>
<?php echo $form->input('Product.options',array('cols'=>'40','rows'=>'5','class'=>'plain'))?> 
<?php echo $form->input('Product.category_id',array('label'=>'Category'));?> 
<?php echo $form->hidden('Resource.1.type',array('value'=>Resource::$types['Decorative']))?> 
<?php echo $form->input('Resource.1.file',array('label'=>'Thumbnail Image','type'=>'file')); ?> 
<fieldset><legend>Flags</legend>
<?php echo $form->input('Product.draft')?> 
<?php echo $form->input('Product.featured')?> 
</fieldset>
<?php echo $form->input('Product.id');?> 
<?php echo $form->end(array('label'=>sprintf('Add %s',Configure::read('Product.alias'))));?> 
</div>