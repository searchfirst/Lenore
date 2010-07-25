<h2>New <?php echo Configure::read('Article.alias') ?></h2>
<div class="content">
<?php echo $form->create(array('type'=>'file'));?> 
<?php echo $form->input('Article.title', array('size'=>'60','label'=>'Title','error'=>'Please enter the Title.'))?> 
<?php echo $form->input('Article.description',array('cols'=>'60','rows'=>'20','value'=>$this->element('admin/item_templates/new_article'),'error'=>'Please enter the Description.'));?> 
<?php if(!empty($this->data['Article']['section_id'])):?>
<?php echo $form->hidden('Article.section_id');?> 
<?php else:?>
<?php echo $form->input('Article.section_id', array('options'=>$sections,'label'=>'Page','error'=>'Please select the Page.'));?> 
<?php endif;?>
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload.type][',array('value'=>Resource::$types['Decorative'],'id'=>'FileuploadType'));?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="" />
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>";?> 
</div>
<fieldset><legend>Flags</legend>
<?php echo $form->input('Article.draft');?> 
<?php echo $form->input('Article.featured');?> 
</fieldset>
<?php echo $form->end('Add');?> 
</div>