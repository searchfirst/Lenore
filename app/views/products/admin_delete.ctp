<p>Do you really wish to delete this <?php echo Inflector::humanize(Configure::read('Product.alias'));?>?</p>
<?php echo $form->create('Product',array('type'=>'delete'));?> 
<?php echo $form->hidden('Product.id', array('value'=>$id));?> 
<?php echo $form->end(sprintf('Delete this %s',Inflector::humanize(Configure::read('Product.alias'))));?>