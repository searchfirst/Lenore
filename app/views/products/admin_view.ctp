<div class="options">
<?php echo $this->renderElement('new_item_form',array(
	'parentClass'=>'Category','parentName'=>$product['Category']['title'],'parentId'=>$product['Category']['id']))?> 
<?php echo $this->renderElement('edit_form',array('id'=>$product['Product']['id'],'title'=>$product['Product']['title']))?> 
<?php echo $this->renderElement('delete_form',array('id'=>$product['Product']['id'],'title'=>$product['Product']['title']))?> 
</div>
<h2><?php echo $textAssistant->link($product['Category']['title'],"/categories/view/{$product['Category']['id']}")?> — Product: <?php echo $product['Product']['title']?></h2>

<div id="item_display">
<ul>
<li><a href="#item_display_main">Main</a></li>
<li><a href="#item_display_media">Media</a></li>
<?php if(defined('MOONLIGHT_PRODUCTS_SALES_OPTIONS') && MOONLIGHT_PRODUCTS_SALES_OPTIONS):?>
<li><a href="#item_display_sales">Sales</a></li>
<?php endif;?>
<li><a href="#item_display_meta">SEO</a></li>
</ul>
<div id="item_display_main">
<h3 class="tabs-heading">Main</h3>
<div class="information">
<dl>
<dt>Flags</dt>
<dd><?php if($product[$this->modelNames[0]]['draft']==1) echo "Draft item";
else echo "Public item";?><br />
<?php if($product[$this->modelNames[0]]['featured']==1) echo "Featured item";?></dd>
<dt>Created</dt>
<dd><?php echo $time->niceShort($product['Product']['created'])?></dd>
<dt>Modified</dt>
<dd><?php echo $time->niceShort($product['Product']['modified'])?></dd>
</dl>
</div>
<div class="content">
<?php echo $textAssistant->htmlFormatted($product['Product']['description'])?>
</div>
<hr />
</div>
<div id="item_display_media">
<h3 class="tabs-heading">Media</h3>
<div class="help_information">
<?php echo $this->renderElement('help/media') ?>
</div>
<div class="content">
<h4>Image</h4>
<?php
if(!empty($product['Decorative'])) echo $this->renderElement('deco_image',array(
	'deco_id'=>$product['Decorative'][0]['id'],'deco_title'=>$product['Decorative'][0]['title'],'parent'=>$product));
else echo $this->renderElement('deco_image_empty',array('parent'=>$product));
?> 
<h4>Inline Media</h4>
<p><?php echo $html->link('Manage inline media','manageinline/'.$product['Product']['id']) ?> (<?php
$inline_media_offset = count($product['Resource']) - ((int) $product['Product']['inline_count']);
if($inline_media_offset == 0)
	echo "<strong>You have uploaded the required amount of inline media for this item.</strong>";
elseif($inline_media_offset > 0)
	echo "<strong>You have too many media files for this item. You need to select $inline_media_offset for deletion.</strong>";
else
	echo "<strong>You have too few media files for this item. You need to upload ".($inline_media_offset * -1)." more.</strong>";
?>)</p>
<h4>Downloads</h4>
<?php if(!empty($product['Downloadable'])):?>
<?php echo $this->renderElement('download_view',array(
	'resources'=>$product['Downloadable']
)); ?>
<?php endif;?>
<?php echo $this->renderElement('download_form',array('parent'=>$product))?> 
</div>
<hr />
</div>
<?php if(defined('MOONLIGHT_PRODUCTS_SALES_OPTIONS') && MOONLIGHT_PRODUCTS_SALES_OPTIONS):?>
<div id="item_display_sales">
<h3 class="tabs-heading">Sales</h3>
<div class="content">
<dl>
<dt>Price</dt>
<?php if(!empty($product['Product']['price'])):?>
<dd>£<?php echo round($product['Product']['price'],2);?></dd>
<?php else:?>
<dd>No price set</dd>
<?php endif;?>
<dt>Options</dt>
<?php if(!empty($product['Product']['options'])):?>
<dd><?php echo $product['Product']['options'] ?></dd>
<?php else:?>
<dd>No options set</dd>
<?php endif;?>
</dl>
</div>
</div>
<?php endif;?>
<div id="item_display_meta">
<h3 class="tabs-heading">SEO</h3>
<div class="help_information">
<?php echo $this->renderElement('help/seo')?> 
</div>
<div class="content">
<dl>
<dt>Description</dt>
<?php if(!empty($product['Product']['meta_description'])):?>
<dd><?php echo $textAssistant->sanitiseText($product['Product']['meta_description'])?></dd>
<?php else:?>
<dd>&lt;Not set&gt;</dd>
<?php endif;?>
<dt>Keywords</dt>
<?php if(!empty($product['Product']['meta_keywords'])):?>
<dd><?php echo $textAssistant->sanitiseText($product['Product']['meta_keywords'])?></dd>
<?php else:?>
<dd>&lt;Not set&gt;</dd>
<?php endif;?>
</dl>
<form method="post" accept-type="UTF-8" action="<?php echo $html->url("/products/edit/{$product['Product']['id']}")?>">
<fieldset>
<legend>Update Metadata</legend>
<?php echo $form->input('Product.meta_description',array('value'=>$product['Product']['meta_description']))?> 
<?php echo $form->input('Product.meta_keywords',array('value'=>$product['Product']['meta_keywords']))?> 
<?php echo $form->hidden('Product.id',array('value'=>$product['Product']['id']))?> 
<?php echo $form->submit('Update')?> 
</fieldset>
</form>
</div>
<hr />
</div>
</div>