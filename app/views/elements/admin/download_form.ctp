<?php echo $form->create(null,array('type'=>'file','url'=>array('action'=>'edit')));?> 
<?php echo $form->input('Resource.1.title');?> 
<?php echo $form->input('Resource.1.description',array('type'=>'textarea'));?> 
<?php echo $form->hidden("Resource.1.type",array('value'=>Resource::$types['Download']));?> 
<?php echo $form->input("Resource.1.file",array('label'=>'File','type'=>'file'));?> 
<?php echo $form->end('Add a download');?>