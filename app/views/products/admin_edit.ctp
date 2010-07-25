<h2>Edit Product: <?php echo $textAssistant->sanitiseText($this->data['Product']['title']); ?></h2>
<div class="content">
<?php echo $form->create('Product',array('options'=>'file')); ?> 
<?php echo $form->input('Product.title',array('size'=>'40','maxlength'=>'150')); ?> 
<?php echo $form->input('Product.description', array('cols'=>'40','rows'=>'20')); ?> 
<?php if(empty($product['Decorative'])):?>
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload.type][',array('value'=>Resource::$types['Decorative'],'id'=>'FileuploadType'))?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="" />
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>"?> 
</div>
<?php endif;?>
<fieldset><legend><?php echo sprintf('%s Options',Configure::read('Product.alias')); ?></legend>
<?php echo $form->input('Product.category_id',array('options'=>$categories,'label'=>sprintf('%s Category',Configure::read('Category.alias'))))?> 
<?php echo $form->input('Product.price',array('size'=>3,'maxlength'=>9)) ?>
<?php echo $form->input('Product.options',array('cols'=>'40','rows'=>'5','class'=>'plain'))?> 
</fieldset>
<fieldset><legend>Flags</legend>
<?php echo $form->input('Product.draft'); ?>
<?php echo $form->input('Product.featured'); ?> 
</fieldset>
<?php echo $form->hidden('Product.id')?> 
<?php echo $form->end('Save')?> 
</div>