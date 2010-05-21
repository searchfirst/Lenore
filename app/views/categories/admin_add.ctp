<h2>Add Category</h2>
<form action="<?php echo $html->url('/categories/add'); ?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
<div class="options">
<?php //echo $form->labelTag('Category/draft','Draft')?> 
<?php //echo $html->checkbox('Category/draft',null,array('value'=>1))?> 
<?php //echo $form->labelTag('Category/featured','Featured')?> 
<?php //echo $html->checkbox('Category/featured',null,array('value'=>1))?> 
</div>
<?php echo $form->input('Category.title',array('size'=>'60','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('Category.description',array(
	'cols'=>'60','rows'=>'20','value'=>$this->renderElement('item_templates/new_category'),'error'=>'Please enter the Description.'))?> 
<?php if(MOONLIGHT_USE_SUBCATEGORIES && !empty($category_list)):?>
<?php echo $form->select("Category.category_id",$category_list,null,array('error'=>'Please enter the Category Id.','title'=>'Subcategory Of'))?> 
<?php endif;?>
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload/type][',array('value'=>MOONLIGHT_RESTYPE_DECO,'id'=>'FileuploadType'))?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="" />
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>";?> 
</div>
<?php echo $form->submit('Add')?> 
</form>