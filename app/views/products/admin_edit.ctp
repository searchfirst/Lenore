<h2>Edit Product: <?php echo $textAssistant->link($this->data['Product']['title'],"/products/view/{$this->data['Product']['id']}") ?></h2>
<form action="<?php echo $html->url('/products/edit/'.$html->tagValue('Product/id'))?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
<div class="options">
<?php echo $form->input('Product.draft',array('type'=>'checkbox','value'=>1))?> 
<?php echo $form->input('Product.featured',array('type'=>'checkbox','value'=>1))?> 
</div>
<?php echo $form->input('Product.title',array('size'=>'40','maxlength'=>'150','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('Product.description', array('cols'=>'40','rows'=>'20','error'=>'Please enter the Description.'))?> 
<?php echo $form->input('Product.price',array('size'=>3,'maxlength'=>9)) ?>
<?php echo $form->input('Product.options',array('cols'=>'40','rows'=>'5','class'=>'plain'))?> 
<?php echo $form->input('Product.category_id',array('options'=>$categories,'label'=>'Category'))?> 
<?php if(empty($product['Decorative'])):?>
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload/type][',array('value'=>MOONLIGHT_RESTYPE_DECO,'id'=>'FileuploadType'))?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="" />
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>"?> 
</div>
<?php endif;?>
<?php echo $form->hidden('Product.id')?> 
<?php echo $form->submit('Save')?> 
</form>