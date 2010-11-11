<h2>Edit <?php echo Configure::read('Article.alias') ?>: <?php echo $this->data['Article']['title'] ?></h2>
<div class="content">
<?php echo $form->create('Article',array('type'=>'file')); ?> 
<?php echo $form->input('Article.title',array('size'=>'60','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('Article.description',array(
	'cols'=>'60',
	'rows'=>'20',
	'label'=>'Content',
	'class'=>'rich'
));?> 
<?php echo $form->input('Article.section_id',array('label'=>Configure::read('Section.alias')))?> 
<?php if(empty($article['Decorative'])):?>
<?php echo $form->hidden('Resource.1.type',array('value'=>Resource::$types['Decorative']));?> 
<?php echo $form->input('Resource.1.file',array('label'=>'Thumbnail Image','type'=>'file'));?> 
<?php endif;?>
<fieldset><legend>Flags</legend>
<?php echo $form->input('Article.draft');?> 
<?php echo $form->input('Article.featured');?> 
</fieldset>
<?php echo $form->hidden('Article.id')?> 
<?php echo $form->end('Save')?> 
</div>