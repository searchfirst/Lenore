<?php $model = isset($model)?$model:$this->params['models'][0];?>
<form method="post" action="<?php echo $html->url("/".strtolower($this->name)."/edit/".$parent[$model]['id'])?>" enctype="multipart/form-data">
<?php echo $form->hidden('Fileupload/type][',array('value'=>MOONLIGHT_RESTYPE_DECO,'id'=>'FileuploadDecorativeType'))?> 
<?php //echo $form->file('Fileupload[]',array('name'=>'Fileupload[]'))?>
<input type="file" name="Fileupload[]" id="Fileupload" />
<?php echo $form->submit('Upload new image',array('div'=>false))?> 
</form>