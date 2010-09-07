<?php $model = isset($model)?$model:$this->params['models'][0];?>
<?php echo $form->create($model,array('type'=>'file','url'=>sprintf('/admin/%s/edit/%s',Inflector::tableize($this->name),$parent[$model]['id']))); ?> 
<?php echo $form->hidden('Resource.1.type',array('value'=>Resource::$types['Decorative']))?> 
<?php echo $form->input('Resource.1.file',array('label'=>'Thumbnail Image','type'=>'file')) ?> 
<?php echo $form->input("$model.id",array('value'=>$parent[$model]['id'])); ?> 
<?php echo $form->end(array('label'=>'Upload new image','div'=>false))?> 
</form>