<h2>New <?php echo Configure::read('Section.alias');?></h2>
<div class="content">
<?php //echo $form->create('Section',array('url'=>array('action'=>'add'),'enctype'=>'multipart/form-data'));?> 
<form enctype="multipart/form-data" id="SectionAdminAddForm" method="post" action="/admin/sections/add" accept-charset="utf-8">
<?php echo $form->input('Section.title', array('size'=>'60','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('Section.description',array(
	'type'=>'textarea','cols'=>'60','rows'=>'20','value'=>$this->element('admin/item_templates/new_section'),'error'=>'Please enter the Description.'))?> 
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload/type][',array('value'=>Resource::$types['Decorative'],'id'=>'FileuploadType'))?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="" />
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>"?> 
</div>
<?php //echo $form->submit('Add')?> 
<?php echo $form->end(array('label'=>sprintf('Add %s',Configure::read('Section.alias')),'div'=>false));?>
</div>