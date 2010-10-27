<div class="item">
<h3>Information</h3>
<div class="dates">
<p><b>Created</b> on <?php echo $time->format('d/m/Y',$section['Section']['created'])?> and
<b>edited</b> on <?php echo $time->format('d/m/Y',$section['Section']['modified'])?></p>
</div>
<div class="flags" data-toggle-uri="/admin/sections/edit/...">
<ul>
<?php if($section['Section']['draft']==1): ?>
<li class="draft" role="checkbox" aria-checked="true" data-controller="sections" data-id="<?php echo $section['Section']['id'];?>" data-field="draft" data-currentstate="1">Draft</li>
<?php else: ?>
<li class="no-draft" role="checkbox" aria-checked="false" data-controller="sections" data-id="<?php echo $section['Section']['id'];?>" data-field="draft" data-currentstate="0">Published</li>
<?php endif; ?>
<?php if($section['Section']['featured']==1): ?>
<li class="featured" role="checkbox" aria-checked="true" data-controller="sections" data-id="<?php echo $section['Section']['id'];?>" data-field="featured" data-currentstate="1">Featured</li>
<?php else: ?>
<li class="no-featured" role="checkbox" aria-checked="false" data-controller="sections" data-id="<?php echo $section['Section']['id'];?>" data-field="featured" data-currentstate="0">Not Featured</li>
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