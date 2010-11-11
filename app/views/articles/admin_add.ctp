<h2>New <?php echo Configure::read('Article.alias') ?></h2>
<div class="content">
<?php echo $form->create(array('type'=>'file'));?> 
<?php echo $form->input('Article.title', array('size'=>'60','label'=>'Title','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('Article.description',array(
	'cols'=>'60',
	'rows'=>'20',
	'value'=>$this->element('admin/item_templates/new_article'),
	'label'=>'Content',
	'class'=>'rich'
));?> 
<?php if(!empty($this->data['Article']['section_id'])):?>
<?php echo $form->hidden('Article.section_id');?> 
<?php else:?>
<?php echo $form->input('Article.section_id', array('options'=>$sections,'label'=>'Page','error'=>'Please select the Page.'));?> 
<?php endif;?>
<?php echo $form->hidden('Resource.1.type',array('value'=>Resource::$types['Decorative']));?> 
<?php echo $form->input('Resource.1.file',array('label'=>'Thumbnail Image','type'=>'file'));?> 
<fieldset><legend>Flags</legend>
<?php echo $form->input('Article.draft');?> 
<?php echo $form->input('Article.featured');?> 
</fieldset>
<?php echo $form->end('Add');?> 
</div>