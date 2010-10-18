<div class="item">
<h3>Information</h3>
<div class="dates">
<p><b>Created</b> on <?php echo $time->format('d/m/Y',$category['Category']['created'])?> and
<b>edited</b> on <?php echo $time->format('d/m/Y',$category['Category']['modified'])?></p>
</div>
<div class="flags">
<ul>
<?php if($category['Category']['draft']==1): ?>
<li class="draft">Draft</li>
<?php else: ?>
<li class="no-draft">Published</li>
<?php endif; ?>
<?php if($category['Category']['featured']==1): ?>
<li class="featured">Featured</li>
<?php else: ?>
<li class="no-featured">Not Featured</li>
<?php endif; ?>
</ul>
</div>
<div class="meta">
<ul class="Category">
<li><b>Description</b> <span class="editable meta_description"><?php echo $textAssistant->sanitiseText($category['Category']['meta_description']); ?></span></li>
<li><b>Keywords</b> <span class="editable meta_keywords"><?php echo $textAssistant->sanitiseText($category['Category']['meta_keywords']); ?></span></li>
</ul>
<?php echo $form->create('Category',array('url'=>sprintf('edit/%s',$category['Category']['id']))); ?> 
<fieldset>
<legend>Update Metadata</legend>
<?php echo $form->input('Category.meta_description',array('value'=>$category['Category']['meta_description']))?> 
<?php echo $form->input('Category.meta_keywords',array('value'=>$category['Category']['meta_keywords']))?> 
<?php echo $form->hidden('Category.id')?> 
</fieldset>
<?php echo $form->end('Update')?> 
</div>
</div>