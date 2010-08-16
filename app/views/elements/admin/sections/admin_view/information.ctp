<div class="item">
<h3>Information</h3>
<dl>
<dt>Flags</dt>
<dd><?php if((int)$section['Section']['draft']) echo "Draft item";
else echo "Public item";?><br />
<?php if((int)$section['Section']['featured']) echo "Featured item";?></dd>
<dt>Created</dt>
<dd><?php echo $time->niceShort($section['Section']['created'])?></dd>
<dt>Modified</dt>
<dd><?php echo $time->niceShort($section['Section']['modified'])?></dd>
</dl>
<dl>
<dt>Description</dt>
<?php if(!empty($section['Section']['meta_description'])):?>
<dd><?php echo $textAssistant->sanitiseText($section['Section']['meta_description'])?></dd>
<?php else:?>
<dd>&lt;Not set&gt;</dd>
<?php endif;?>
<dt>Keywords</dt>
<?php if(!empty($section['Section']['meta_keywords'])):?>
<dd><?php echo $textAssistant->sanitiseText($section['Section']['meta_keywords'])?></dd>
<?php else:?>
<dd>&lt;Not set&gt;</dd>
<?php endif;?>
</dl>
<form method="post" accept-type="UTF-8" action="<?php echo $html->url("/sections/edit/{$section['Section']['id']}")?>">
<fieldset>
<legend>Update Metadata</legend>
<?php echo $form->input('Section.meta_description',array('value'=>$section['Section']['meta_description']))?> 
<?php echo $form->input('Section.meta_keywords',array('value'=>$section['Section']['meta_keywords']))?> 
<?php echo $form->hidden('Section.id',array('value'=>$section['Section']['id']))?> 
<?php echo $form->submit('Update')?> 
</fieldset>
</form>
</div>