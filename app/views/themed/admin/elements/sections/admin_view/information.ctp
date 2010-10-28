<div class="item">
<h3>Information</h3>
<div class="dates">
<p><b>Created</b> on <?php echo $time->format('d/m/Y',$section['Section']['created'])?> and
<b>edited</b> on <?php echo $time->format('d/m/Y',$section['Section']['modified'])?></p>
</div>
<div class="flags" data-tgl-uri="/admin/sections/edit/<?php echo $section['Section']['id'];?>" data-tgl-mdl="Section" data-tgl-id="<?php echo $section['Section']['id'];?>">
<ul>
<?php if($section['Section']['draft']==1): ?>
<li role="checkbox" aria-checked="true" data-tgl-fld="draft">Draft</li>
<?php else: ?>
<li role="checkbox" aria-checked="false" data-tgl-fld="draft">Published</li>
<?php endif; ?>
<?php if($section['Section']['featured']==1): ?>
<li role="checkbox" aria-checked="true" data-tgl-fld="featured">Featured</li>
<?php else: ?>
<li role="checkbox" aria-checked="false" data-tgl-fld="featured">Not Featured</li>
<?php endif; ?>
</ul>
</div>
<div class="meta">
<ul class="Section">
<li><b>Description</b> <span class="editable meta_description"><?php echo $textAssistant->sanitise($section['Section']['meta_description']); ?></span></li>
<li><b>Keywords</b> <span class="editable meta_keywords"><?php echo $textAssistant->sanitise($section['Section']['meta_keywords']); ?></span></li>
</ul>
<?php echo $form->create('Section',array('url'=>sprintf('edit/%s',$section['Section']['id']))); ?> 
<fieldset>
<legend>Update Metadata</legend>
<?php echo $form->input('Section.meta_description',array('value'=>$section['Section']['meta_description']))?> 
<?php echo $form->input('Section.meta_keywords',array('value'=>$section['Section']['meta_keywords']))?> 
<?php echo $form->hidden('Section.id')?> 
<?php echo $form->end('Update')?> 
</fieldset>
</form>
</div>
</div>