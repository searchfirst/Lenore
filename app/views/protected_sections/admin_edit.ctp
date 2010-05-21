<h2>Edit Private Section</h2>
<form action="<?php echo $html->url('/protected_sections/edit/'.$html->tagValue('ProtectedSection/id')); ?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
<?php echo $form->input('ProtectedSection.title', array('size'=>'60','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('ProtectedSection.password', array('type'=>'password','size'=>'60','error'=>'Please enter a password.'))?> 
<?php echo $form->input('ProtectedSection.description',array('cols'=>'60','rows'=>'20','error'=>'Please enter the Description.'))?> 
<?php if(empty($protectedSection['Decorative'][0])):?>
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload/type][',array('value'=>MOONLIGHT_RESTYPE_DECO,'id'=>'FileuploadType'))?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="" />
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>"?> 
</div>
<?php endif;?>
<?php echo $form->hidden('ProtectedSection/id')?> 
<?php echo $form->submit('Save')?> 
</form>