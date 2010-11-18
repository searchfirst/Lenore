<h2>Edit Snippet: <?php echo $textAssistant->sanitise($this->data['Snippet']['title'])?></h2>
<div class="content">
<?php echo $form->create('Snippet',array('options'=>'file')) ?> 
<?php echo $form->input('Snippet.title',array('size'=>'60'))?> 
<?php echo $form->input('Snippet.content',array(
	'cols'=>'60',
	'rows'=>'20',
	'class'=>'rich'
));?>  
<?php echo $form->hidden('Snippet.id')?> 
<?php echo $form->end('Save changes')?> 
</div>