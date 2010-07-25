<h2><?php echo sprintf('%s Category: %s',Configure::read('Category.alias'),$category['Category']['title']); ?></h2>
<ul class="hook_menu">
<li><?php echo $html->link(sprintf('Add %s',Configure::read('Product.alias')),array('admin'=>true,'controller'=>'products','action'=>'add','?'=>array('data[Category][id]'=>$category['Category']['id']))); ?></li>
<li><?php echo $this->element('admin/edit_form',array('id'=>$category['Category']['id'],'l_title'=>sprintf('%s Category',Configure::read('Category.alias')),'controller'=>'categories','model'=>'Category'))?> </li>
<li><?php echo $this->element('admin/delete_form',array('id'=>$category['Category']['id'],'l_title'=>sprintf('%s Category',Configure::read('Category.alias')),'controller'=>'categories','model'=>'Category'))?> </li>
</ul>

<ul class="tab_hooks">
<li><a href="#item_display_main">Main</a></li>
<li><a href="#item_display_media">Media</a></li>
<li><a href="#item_display_children">Products</a></li>
<li><a href="#item_display_meta">SEO</a></li>
</ul>

<div id="item_display_main" class="tab_page">
<h3>Main</h3>
<div class="content">
<?php echo $textAssistant->htmlFormatted($category['Category']['description']) ?> 
</div>
<div class="information">
<dl>
<dt>Flags</dt>
<dd><?php if($category['Category']['draft']==1) echo "Draft item";
else echo "Public item";?><br />
<?php if($category['Category']['featured']==1) echo "Featured item";?></dd>
<dt>Created</dt>
<dd><?php echo $time->niceShort($category['Category']['created'])?></dd>
<dt>Modified</dt>
<dd><?php echo $time->niceShort($category['Category']['modified'])?></dd>
</dl>
</div>
</div>

<div id="item_display_media" class="tab_page">
<h3>Media</h3>
<div class="content">
<h4>Image</h4>
<?php
if(!empty($category['Decorative'])) echo $this->element('admin/deco_image',array(
		'deco_id'=>$category['Decorative'][0]['id'],'deco_title'=>$category['Decorative'][0]['title'],'parent'=>$category));
else echo $this->element('admin/deco_image_empty',array('parent'=>$category));
?> 
<h4>Inline Media</h4>
<p><?php echo $html->link('Manage inline media','manageinline/'.$category['Category']['id']) ?> (<?php
$inline_media_offset = count($category['Resource']) - ((int) $category['Category']['inline_count']);
if($inline_media_offset == 0)
	echo "You have uploaded the required amount of inline media for this item.";
elseif($inline_media_offset > 0)
	echo "You have too many media files for this item. You need to select $inline_media_offset for deletion.";
else
	echo "You have too few media files for this item. You need to upload ".($inline_media_offset * -1)." more.";
?>)</p>
<h4>Downloads</h4>
<?php if(!empty($category['Downloadable'])):?>
<?php echo $this->element('admin/download_view',array('resources'=>$category['Downloadable']))?> 
<?php endif;?>
<?php echo $this->element('admin/download_form',array('parent'=>$category))?> 
</div>
<div class="help_information">
<?php echo $this->element('admin/help/media')?> 
</div>
</div>

<div id="item_display_children" class="tab_page">
<h3>Products</h3>
<div class="content">
<table class="sortable">
<colgroup></colgroup>
<colgroup span="2" class="flags"></colgroup>
<colgroup span="2" class="dates"></colgroup>
<thead>
<tr>
<th>Title</th>
<th colspan="2">Flags</th>
<th>Created</th>
<th>Modified</th>
</tr>
</thead>
<tbody class="products">
<?php if(empty($category['Product'])):?>
<tr><td colspan="5">No Products</td></tr>
<?php else:?>
<?php foreach($category['Product'] as $product):?>
<tr>
<td>
<span><?php echo $html->link($textAssistant->sanitiseText($product['title']),array('admin'=>true,'controller'=>'products','action'=>'view',$product['id'])); ?></span>
<ul class="hook_menu">
<li><?php echo $this->element('admin/edit_form',array('controller'=>'Products','id'=>$product['id'],'model'=>'Product','title'=>$product['title']))?></li>
<li><?php echo $this->element('admin/delete_form',array('controller'=>'Products','model'=>'Product','id'=>$product['id'],'title'=>$product['title']))?></li>
</ul>
</td>
<td><img src="/img/admin/flag-<?php if($product['draft']) {
echo "draft.png\" alt=\"Draft\"";
} else {
echo "published.png\" alt=\"Published\"";
}?> class="flag"></td>
<td><img src="/img/admin/flag-<?php if($product['featured']) {
echo "flagged.png\" alt=\"Featured\"";
} else {
echo "unflagged.png\" alt=\"Normal\"";
}?> class="flag"></td>
<td><?php echo $time->format('d M Y',$product['created']); ?></td>
<td><?php echo $time->format('d M Y',$product['modified']); ?></td>
</tr>
<?php endforeach;?>
<?php endif;?>
</tbody>
</table>
</div>
</div>

<div id="item_display_meta" class="tab_page">
<h3>SEO</h3>
<div class="content">
<dl>
<dt>Description</dt>
<?php if(!empty($category['Category']['meta_description'])):?>
<dd><?php echo $textAssistant->sanitiseText($category['Category']['meta_description'])?></dd>
<?php else:?>
<dd>&lt;Not set&gt;</dd>
<?php endif;?>
<dt>Keywords</dt>
<?php if(!empty($category['Category']['meta_keywords'])):?>
<dd><?php echo $textAssistant->sanitiseText($category['Category']['meta_keywords'])?></dd>
<?php else:?>
<dd>&lt;Not set&gt;</dd>
<?php endif;?>
</dl>
<form method="post" accept-type="UTF-8" action="<?php echo $html->url("/categories/edit/{$category['Category']['id']}")?>">
<fieldset>
<legend>Update Metadata</legend>
<?php echo $form->input('Category.meta_description',array('value'=>$category['Category']['meta_description']))?> 
<?php echo $form->input('Category.meta_keywords',array('value'=>$category['Category']['meta_keywords']))?> 
<?php echo $form->hidden('Category.id',array('value'=>$category['Category']['id']))?> 
<?php echo $form->submit('Update')?> 
</fieldset>
</form>
</div>
<div class="help_information">
<?php echo $this->element('admin/help/seo')?>
</div>
</div>
