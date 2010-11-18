<p>Do you really wish to delete this Snippet?</p>
<?php echo $form->create('Snippet',array('type'=>'delete'));?> 
<?php echo $form->hidden('Snippet.id', array('value'=>$id));?> 
<?php echo $form->end('Delete this Snippet');?>