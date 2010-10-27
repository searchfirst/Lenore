<div class="item">
<h3>Information</h3>
<p><?php echo sprintf('%s: ',Configure::read('Section.alias')).$html->link($article['Section']['title'],array('admin'=>true,'controller'=>'sections','action'=>'view',$article['Section']['id']));?></p>
<div class="dates">
<p><b>Created</b> on <?php echo $time->format('d/m/Y',$article['Article']['created'])?> and
<b>edited</b> on <?php echo $time->format('d/m/Y',$article['Article']['modified'])?></p>
</div>
<div class="flags">
<ul>
<?php if($article['Article']['draft']==1): ?>
<li class="draft">Draft</li>
<?php else: ?>
<li class="no-draft">Published</li>
<?php endif; ?>
<?php if($article['Article']['featured']==1): ?>
<li class="featured">Featured</li>
<?php else: ?>
<li class="no-featured">Not Featured</li>
<?php endif; ?>
</ul>
</div>
<div class="meta">
<ul class="Article">
<li><b>Description</b> <span class="editable meta_description"><?php echo $textAssistant->sanitise($article['Article']['meta_description']); ?></span></li>
<li><b>Keywords</b> <span class="editable meta_keywords"><?php echo $textAssistant->sanitise($article['Article']['meta_keywords']); ?></span></li>
</ul>
<?php echo $form->create('Article',array('url'=>sprintf('edit/%s',$article['Article']['id'])));?> 
<fieldset>
<legend>Update Metadata</legend>
<?php echo $form->input('Article.meta_description',array('value'=>$article['Article']['meta_description']))?> 
<?php echo $form->input('Article.meta_keywords',array('value'=>$article['Article']['meta_keywords']))?> 
<?php echo $form->hidden('Article.id')?> 
<?php echo $form->end('Update')?> 
</fieldset>
</form>
</div>
</div>