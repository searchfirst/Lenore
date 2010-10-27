<p>Do you really wish to delete this media?</p>
<?php echo $form->create('Resource',array('type'=>'delete','url'=>array($id))); ?> 
<?php echo $form->hidden('Resource.id', array('value'=>$id)) ?>
<?php echo $form->end('Yes, delete this media.') ?>
</form>