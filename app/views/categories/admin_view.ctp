<div class="options">
<?php echo $this->renderElement('new_item_form',
	array('model'=>'Product','controller'=>'Products','parentClass'=>'Category','parentName'=>$category['Category']['title'],'parentId'=>$category['Category']['id']))?>
<?php echo $this->renderElement('edit_form',array('id'=>$category['Category']['id'],'title'=>$category['Category']['title']))?> 
<?php echo $this->renderElement('delete_form',array('id'=>$category['Category']['id'],'title' => $category['Category']['title']))?> 
</div>
<h2>Category: <?php echo $category['Category']['title']?></h2>

<div id="item_display">
<ul>
<li><a href="#item_display_main">Main</a></li>
<li><a href="#item_display_media">Media</a></li>
<li><a href="#item_display_children">Products</a></li>
<li><a href="#item_display_meta">SEO</a></li>
</ul>

<div id="item_display_main">
<h3 class="tabs-heading">Main</h3>
<div class="information">
<dl>
<dt>Flags</dt>
<dd><?php if($category[$this->modelNames[0]]['draft']==1) echo "Draft item";
else echo "Public item";?><br />
<?php if($category[$this->modelNames[0]]['featured']==1) echo "Featured item";?></dd>
<dt>Created</dt>
<dd><?php echo $time->niceShort($category['Category']['created'])?></dd>
<dt>Modified</dt>
<dd><?php echo $time->niceShort($category['Category']['modified'])?></dd>
</dl>
</div>
<div class="content">
<?php echo $textAssistant->htmlFormatted($category['Category']['description']) ?> 
</div>
<hr />
</div>

<div id="item_display_media">
<div class="help_information">
<?php echo $this->renderElement('help/media')?> 
</div>
<div class="content">
<h3 class="tabs-heading">Media</h3>
<h4>Image</h4>
<?php
if(!empty($category['Decorative'])) echo $this->renderElement('deco_image',array(
		'deco_id'=>$category['Decorative'][0]['id'],'deco_title'=>$category['Decorative'][0]['title'],'parent'=>$category));
else echo $this->renderElement('deco_image_empty',array('parent'=>$category));
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
<?php echo $this->renderElement('download_view',array('resources'=>$category['Downloadable']))?> 
<?php endif;?>
<?php echo $this->renderElement('download_form',array('parent'=>$category))?> 
</div>
<hr />
</div>

<div id='item_display_children'>
<h3 class="tabs-heading">Products</h3>

<?php if(empty($category['Product'])):?>
<p>No Products</p>
<?php else:?>
<ul class="item-list">
<?php foreach($category['Product'] as $product):?>
<li>
<div class="options">
<?php if(isset($previous_id)) echo $this->renderElement('moveup_form',array('controller'=>'Products','id'=>$product['id'],'model'=>'Product','prev_id'=>$previous_id));
$previous_id = $product['id'];?> 
<?php echo $this->renderElement('edit_form',array('controller'=>'Products','id'=>$product['id'],'model'=>'Product','title'=>$product['title']))?> 
<?php echo $this->renderElement('delete_form',array('controller'=>'Products','model'=>'Product','id'=>$product['id'],'title'=>$product['title']))?> 
</div>
<?php echo $html->link($product['title'],"/products/view/{$product['id']}")?> 
</li>
<?php endforeach;?>
</ul>
<?php endif;?>
</div>

<div id="item_display_meta">
<div class="help_information">
<?php echo $this->renderElement('help/seo')?>
</div>
<div class="content">
<h3 class="tabs-heading">SEO</h3>
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
<hr />
</div>
</div>