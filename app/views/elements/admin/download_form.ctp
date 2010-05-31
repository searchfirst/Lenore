<?php
$controller = isset($controller)?$controller:$this->name;
$model = isset($model)?$model:$this->params['models'][0];
?>
<form method="post" action="<?php echo $html->url("/".Inflector::underscore($this->name)."/edit/".$parent[$model]['id'])?>" enctype="multipart/form-data">
<?php echo $form->input('Fileupload/title][',array('id'=>'FileuploadDownloadableTitle','label'=>array('for'=>'FileuploadDownloadableTitle','text'=>'Download Title')))?> 
<?php //echo $form->label('Fileupload/description][','Description',array('for'=>'FileuploadDownloadableDescription'))?> 
<?php echo $form->input('Fileupload/description][',array(
	'cols'=>'80','rows'=>'3','id'=>'FileuploadDownloadableDescription','label'=>array('text'=>'Description','for'=>'FileuploadDownloadableDescription')))?> 
<?php echo $form->hidden('Fileupload/type][',array('value'=>Resource::$types['Download'],'id'=>'FileuploadDownloadableType'))?> 
<div>
<input type="file" name="Fileupload[]" id="FileuploadDownloadable" />
</div>
<?php echo $form->submit('Add a download',array('div'=>false))?> 
</form>
