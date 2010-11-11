<p>Do you really wish to delete this <?php echo Inflector::humanize(Configure::read('Article.alias'));?>?</p>
<?php echo $form->create('Article',array('type'=>'delete'));?> 
<?php echo $form->hidden('Article.id',array('value'=>$id));?> 
<?php echo $form->end(sprintf("Delete this %s",Configure::read('Article.alias')));?> 