<p>Do you really wish to delete this section?</p>
<?php echo $form->create('Section',array('type'=>'delete')); ?> 
<?php echo $form->hidden('Section.id', array('value'=>$id)) ?>
<?php echo $form->end('Yes, delete this section.') ?>
</form>