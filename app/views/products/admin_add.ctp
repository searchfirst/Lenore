<h2>New Product</h2>
<form action="<?php echo $html->url('/products/add'); ?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
<div class="options">
<?php echo $form->input('Product.draft',array('type'=>'checkbox','value'=>1))?> 
<?php echo $form->input('Product.featured',array('type'=>'checkbox','value'=>1))?> 
</div>
<?php echo $form->input('Product.title', array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('Product.description',array(
	'cols'=>'40','rows'=>'20','value'=>$this->renderElement('item_templates/new_product'),'error'=>'Please enter the Description.'))?> 
<?php echo $form->input('Product.price',array('size'=>3,'maxlength'=>9)) ?>
<?php echo $form->input('Product.options',array('cols'=>'40','rows'=>'5','class'=>'plain'))?> 
<?php echo $form->input('Product.category_id',array('options'=>$category,'label'=>'Category','error'=>'Please choose a Category'));?> 
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload/type][',array('value'=>MOONLIGHT_RESTYPE_DECO,'id'=>'FileuploadType'))?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="" />
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>"?> 
</div>
<?php echo $form->submit('Add')?> 
</form>