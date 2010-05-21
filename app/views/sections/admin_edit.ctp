<h2>Edit <?php echo Configure::read('Section.alias');?>: <?php echo $textAssistant->sanitiseText($section['Section']['title'])?></h2>
<div class="content">
<form action="<?php echo $html->url('/sections/edit/'.$html->tagValue('Section/id')); ?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
<?php echo $form->input('Section.title',array('size'=>'60'))?> 
<?php echo $form->input('Section.description',array('cols'=>'60','rows'=>'20'))?> 
<?php if(empty($section['Decorative'][0])):?>
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload/type][',array('value'=>Resource::$types['Decorative'],'id'=>'FileuploadType'))?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="" />
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>"?> 
</div>
<?php endif;?>
<?php echo $form->hidden('Section.id')?> 
<?php echo $form->submit('Save')?> 
</form>
</div>