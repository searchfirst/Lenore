<h2>New Article</h2>
<form action="<?php echo $html->url('/articles/add')?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
<div class="options">
<?php echo $form->input('Article/draft',array('type'=>'checkbox','value'=>1))?> 
<?php echo $form->input('Article/featured',array('type'=>'checkbox','value'=>1))?> 
<?php if(defined('MOONLIGHT_ARTICLES_ENABLE_COMMENTS') && MOONLIGHT_ARTICLES_ENABLE_COMMENTS):?>
<?php echo $form->input('Article/disable_comments',array('type'=>'checkbox','value'=>1,'label'=>'No Comments'))?>
<?php endif;?>
</div>
<?php echo $form->input('Article/title', array('size'=>'60','label'=>'Title','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('Article/description',array('cols'=>'60','rows'=>'20',
	'value'=>$this->renderElement('item_templates/new_article'),'error'=>'Please enter the Description.'));?> 
<?php echo $form->input('Article/section_id', array('options'=>$sections,'label'=>'Page','error'=>'Please select the Page.'))?> 
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload/type][',array('value'=>MOONLIGHT_RESTYPE_DECO,'id'=>'FileuploadType'))?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="" />
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>";?> 
</div>
<?php echo $form->submit('Add')?> 
</form>