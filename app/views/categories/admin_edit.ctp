<h2>Edit Category: <?php echo $textAssistant->link($this->data['Category']['title'],"/categories/view/{$this->data['Category']['id']}") ?></h2>
<form action="<?php echo $html->url("/categories/edit/{$this->data['Category']['id']}"); ?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
<?php echo $form->input('Category.title',array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('Category.description',array('cols'=>'40','rows'=>'20','error'=>'Please enter the Description.'))?> 
<?php if(MOONLIGHT_USE_SUBCATEGORIES && !empty($category_list) && empty($category['Subcategories'])):?>
<?php echo $form->select('Category.category_id',$category_list,null,array('label'=>'Sub-Category Of:','error'=>"Your category can't be a subcategory of itself."))?> 
<?php endif;?>
<?php if(empty($category['Decorative'])):?>
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload/type][',array('value'=>MOONLIGHT_RESTYPE_DECO,'id'=>'FileuploadType'))?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="" />
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>"?> 
</div>
<?php endif;?>
<?php echo $form->hidden('Category.id')?> 
<?php echo $form->submit('Save')?> 
</form>