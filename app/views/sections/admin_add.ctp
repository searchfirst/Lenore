<h2><?php echo sprintf('Add %s',Configure::read('Section.alias')); ?></h2>
<div class="content">
<?php echo $form->create('Section',array('type'=>'file'));?> 
<?php echo $form->input('Section.title', array('size'=>'60','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('Section.description',array(
	'cols'=>'60',
	'rows'=>'20',
	'value'=>$this->element('admin/item_templates/new_section'),
	'label'=>'Content'
))?> 
<?php echo $form->hidden('Resource.1.type',array('value'=>Resource::$types['Decorative']))?> 
<?php echo $form->input('Resource.1.file',array('type'=>'file','label'=>'Thumbnail Image')); ?> 
<fieldset><legend><?php echo sprintf("Enable %s",Inflector::pluralize(Configure::read('Article.alias'))); ?></legend>
<p><?php echo sprintf("Enable %s",strtolower(Inflector::pluralize(Configure::read('Article.alias')))); ?> only if the <?php echo strtolower(Configure::read('Section.alias')) ?> you are creating will be similar to a news feed or blog.</p>
<?php echo $form->input('Section.articles_enabled'); ?> 
</fieldset>
<fieldset><legend>Flags</legend>
<?php echo $form->input('Section.draft'); ?> 
<?php echo $form->input('Section.featured'); ?> 
</fieldset>
<?php echo $form->end(array('label'=>sprintf('Add %s',Configure::read('Section.alias')),'div'=>false));?>
</div>