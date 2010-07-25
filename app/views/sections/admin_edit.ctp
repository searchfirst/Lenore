<h2>Edit <?php echo Configure::read('Section.alias');?>: <?php echo $textAssistant->sanitiseText($section['Section']['title'])?></h2>
<div class="content">
<?php echo $form->create('Section',array('options'=>'file')) ?> 
<?php echo $form->input('Section.title',array('size'=>'60'))?> 
<?php echo $form->input('Section.description',array('cols'=>'60','rows'=>'20'))?> 
<?php if(empty($section['Decorative'][0])):?>
<div>
<label for="Fileupload">Upload an image</label>
<?php echo $form->hidden('Fileupload/type][',array('value'=>Resource::$types['Decorative'],'id'=>'FileuploadType'))?> 
<input type="file" name="Fileupload[]" id="Fileupload" value="" />
<?php if(isset($GLOBALS['Fileupload_error'])) echo "<div class=\"error_message\">{$GLOBALS['Fileupload_error']}</div>"?> 
</div>
<?php endif;?>
<fieldset><legend><?php echo sprintf("Enable %s",Inflector::pluralize(Configure::read('Article.alias'))); ?></legend>
<p><?php echo sprintf("Enable %s",strtolower(Inflector::pluralize(Configure::read('Article.alias')))); ?> only if the <?php echo strtolower(Configure::read('Section.alias')) ?> you are creating will be similar to a news feed or blog.</p>
<?php echo $form->input('Section.articles_enabled'); ?> 
</fieldset>
<fieldset><legend>Flags</legend>
<?php echo $form->input('Section.draft'); ?> 
<?php echo $form->input('Section.featured'); ?> 
</fieldset>
<?php echo $form->hidden('Section.id')?> 
<?php echo $form->end('Save changes')?> 
</div>