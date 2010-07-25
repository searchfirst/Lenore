<h2>Edit <?php echo Configure::read('Article.alias') ?>: <?php echo $this->data['Article']['title'] ?></h2>
<div class="content">
<?php echo $form->create('Article',array('type'=>'file')); ?> 
<?php echo $form->input('Article.title',array('size'=>'60','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('Article.description',array('cols'=>'60','rows'=>'20','error'=>'Please enter the Description.'))?> 
<?php echo $form->input('Article.section_id',array('label'=>Configure::read('Section.alias')))?> 
<?php if(empty($article['Decorative'])):?>
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload.type][',array('value'=>Resource::$types['Decorative'],'id'=>'FileuploadType'))?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="">
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>"?> 
</div>
<?php endif;?>
<fieldset><legend>Flags</legend>
<?php echo $form->input('Article.draft');?> 
<?php echo $form->input('Article.featured');?> 
</fieldset>
<?php echo $form->hidden('Article.id')?> 
<?php echo $form->end('Save')?> 
</div>