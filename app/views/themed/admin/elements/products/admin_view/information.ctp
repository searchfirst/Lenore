<div class="item">
<h3>Information</h3>
<p><?php echo sprintf('%s Category: ',Configure::read('Category.alias')).$html->link($product['Category']['title'],array('admin'=>true,'controller'=>'categories','action'=>'view',$product['Category']['id']));?></p>
<div class="dates">
<p><b>Created</b> on <?php echo $time->format('d/m/Y',$product['Product']['created'])?> and
<b>edited</b> on <?php echo $time->format('d/m/Y',$product['Product']['modified'])?></p>
</div>
<div class="flags" data-tgl-uri="/admin/products/edit/<?php echo $product['Product']['id'];?>" data-tgl-mdl="Product" data-tgl-id="<?php echo $product['Product']['id'];?>">
<ul>
<?php if($product['Product']['draft']==1): ?>
<li role="checkbox" aria-checked="true" data-tgl-fld="draft">Draft</li>
<?php else: ?>
<li role="checkbox" aria-checked="false" data-tgl-fld="draft">Published</li>
<?php endif; ?>
<?php if($product['Product']['featured']==1): ?>
<li role="checkbox" aria-checked="true" data-tgl-fld="featured">Featured</li>
<?php else: ?>
<li role="checkbox" aria-checked="false" data-tgl-fld="featured">Not Featured</li>
<?php endif; ?>
</ul>
</div>
<div class="meta">
<ul class="Product">
<li><b>Description</b> <span class="editable meta_description"><?php echo $textAssistant->sanitiseText($product['Product']['meta_description']); ?></span></li>
<li><b>Keywords</b> <span class="editable meta_keywords"><?php echo $textAssistant->sanitiseText($product['Product']['meta_keywords']); ?></span></li>
</ul>
<?php echo $form->create('Product',array('url'=>sprintf('edit/%s',$product['Product']['id']))); ?> 
<fieldset>
<legend>Update Metadata</legend>
<?php echo $form->input('Product.meta_description',array('value'=>$product['Product']['meta_description']))?> 
<?php echo $form->input('Product.meta_keywords',array('value'=>$product['Product']['meta_keywords']))?> 
<?php echo $form->hidden('Product.id')?> 
</fieldset>
<?php echo $form->end('Update')?> 
</div>
</div>