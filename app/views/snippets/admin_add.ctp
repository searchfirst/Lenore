<h2><?php echo __('Add Snippet');?></h2>
<div class="content">
<?php echo $form->create('Snippet');?> 
<?php echo $form->input('Snippet.title',array('size'=>'60'))?> 
<?php echo $form->input('Snippet.content',array(
	'cols'=>'60',
	'rows'=>'20',
	'class'=>'rich'
))?> 
<?php echo $form->end(array('label'=>'Add Snippet','div'=>false));?>
</div>