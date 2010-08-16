<h2>Edit Category: <?php echo $textAssistant->sanitiseText($this->data['Category']['title']); ?></h2>
<div class="content">
<?php echo $form->create('Category',array('type'=>'file')); ?> 
<?php echo $form->input('Category.title',array('size'=>'60','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('Category.description',array(
	'cols'=>'60',
	'rows'=>'20',
	'label'=>'Content'
));?> 
<?php if(Configure::read('Category.use_subcategories') && !empty($categories)): ?>
<?php echo $form->input('Category.category_id',array(
	'empty'=>true,
	'label'=>sprintf('Parent %s Category',Configure::read('Category.alias'))
)); ?> 
<?php endif;?>
<?php if(empty($category['Decorative'])):?>
<?php echo $form->hidden('Resource.1.type',array('value'=>Resource::$types['Decorative'])); ?> 
<?php echo $form->input('Resource.1.file',array('type'=>'file','label'=>'Thumbnail Image')); ?> 
<?php endif;?>
<fieldset><legend>Flags</legend>
<?php echo $form->input('Category.draft'); ?> 
<?php echo $form->input('Category.featured'); ?> 
</fieldset>
<?php echo $form->hidden('Category.id')?> 
<?php echo $form->end('Save')?> 
</form>
</div>