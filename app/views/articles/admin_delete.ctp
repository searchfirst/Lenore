<?php echo $form->create('Article',array($id));?> 
<?php echo $form->hidden('Article.id',array('value'=>$id));?> 
<?php echo $form->end(sprintf("Yes, I really want to delete this %s",Configure::read('Article.alias')));?> 