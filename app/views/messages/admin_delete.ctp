<p>Do you really wish to delete this Message?</p>
<?php echo $form->create('Message',array('type'=>'delete'));?> 
<?php echo $form->hidden('Message.id', array('value'=>$id));?> 
<?php echo $form->end('Delete this Message');?>