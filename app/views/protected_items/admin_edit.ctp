<h2>Edit Item: <?php echo $textAssistant->link($this->data['ProtectedItem']['title'],"/protected_items/view/{$this->data['ProtectedItem']['id']}")?></h2>
<form action="<?php echo $html->url("/protected_items/edit/{$this->data['ProtectedItem']['id']}")?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
<?php echo $form->input('ProtectedItem.title',array('size'=>'60','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('ProtectedItem.description',array('cols'=>'60','rows'=>'20','error'=>'Please enter the Description.'))?> 
<?php echo $form->input('ProtectedItem.protected_section_id',array(
	'options'=>$protectedSections,'label'=>'Protected Section','error'=>'Please select the Protected Section.'))?> 
<?php if(empty($protectedItem['Decorative'])):?> 
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload/type][',array('value'=>MOONLIGHT_RESTYPE_DECO,'id'=>'FileuploadType'))?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="" />
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>"?> 
</div>
<?php endif;?>
<?php echo $form->hidden('ProtectedItem.id')?> 
<?php echo $form->submit('Save')?> 
</form>