<h2>Edit Article</h2>
<form action="<?php echo $html->url('/articles/edit/'.$html->tagValue('Article/id')); ?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
<div class="options">
<?php echo $form->input('Article/draft',array('type'=>'checkbox','value'=>1))?> 
<?php echo $form->input('Article/featured',array('type'=>'checkbox','value'=>1))?> 
<?php if(defined('MOONLIGHT_ARTICLES_ENABLE_COMMENTS') && MOONLIGHT_ARTICLES_ENABLE_COMMENTS):?>
<?php echo $form->input('Article/disabled_comments',array('type'=>'checkbox','value'=>1))?> 
<?php endif;?>
</div>
<?php echo $form->input('Article/title',array('size'=>'60','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('Article/description',array('cols'=>'60','rows'=>'20','error'=>'Please enter the Description.'))?> 
<?php echo $form->input('Article/section_id',array('options'=>$sections,'value'=>$html->tagValue('Article/section_id'),'label'=>'Page','error'=>'Please select the Section.'))?> 
<?php if(empty($article['Decorative'])):?>
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload/type][',array('value'=>MOONLIGHT_RESTYPE_DECO,'id'=>'FileuploadType'))?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="" />
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>"?> 
</div>
<?php endif;?>
<?php echo $form->hidden('Article/id')?> 
<?php echo $form->submit('Save')?> 
</form>
