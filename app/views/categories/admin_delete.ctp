<p>Do you really wish to delete this <?php echo Inflector::humanize(Configure::read('Category.alias'));?>?</p>
<?php echo $form->create('Category',array('type'=>'delete'));?> 
<?php echo $form->hidden('Category.id', array('value'=>$id));?> 
<?php echo $form->end(sprintf('Delete this %s',Inflector::humanize(Configure::read('Category.alias'))));?>