<p>Do you really wish to delete this <?php echo Inflector::humanize(Configure::read('Section.alias'));?>?</p>
<?php echo $form->create('Section',array('type'=>'delete'));?> 
<?php echo $form->hidden('Section.id', array('value'=>$id));?> 
<?php echo $form->end(sprintf('Delete this %s',Inflector::humanize(Configure::read('Section.alias'))));?>