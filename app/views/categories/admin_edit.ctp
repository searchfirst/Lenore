<h2>Edit Category: <?php echo $textAssistant->sanitiseText($this->data['Category']['title']); ?></h2>
<div class="content">
<form action="<?php echo $html->url("/categories/edit/{$this->data['Category']['id']}"); ?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
<?php echo $form->input('Category.title',array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('Category.description',array('cols'=>'40','rows'=>'20','error'=>'Please enter the Description.'))?> 
<?php if(Configure::read('Category.use_subcategories') && empty($category['Subcategories'])):?>
<?php //echo $form->select('Category.category_id',$category_list,null,array('label'=>'Sub-Category Of:','error'=>"Your category can't be a subcategory of itself."))?> 
<?php echo $form->input('Category.category_id',array('empty'=>true,'label'=>sprintf('Parent %s Category',Configure::read('Category.alias'))))?> 
<?php endif;?>
<?php if(empty($category['Decorative'])):?>
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload/type][',array('value'=>Resource::$types['Decorative'],'id'=>'FileuploadType'))?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="" />
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>"?> 
</div>
<?php endif;?>
<?php echo $form->hidden('Category.id')?> 
<?php echo $form->submit('Save')?> 
</form>
</div>