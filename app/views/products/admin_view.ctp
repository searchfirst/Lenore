<h2><?php echo sprintf('%s: %s',Configure::read('Product.alias'),$product['Category']['title']); ?></h2>
<ul class="hook_menu">
<?php echo $this->element('admin/edit_form',array('id'=>$product['Product']['id'],'controller'=>'products','model'=>'Product','title'=>$product['Product']['title']))?> 
<?php echo $this->element('admin/delete_form',array('id'=>$product['Product']['id'],'controller'=>'products','model'=>'Product','title'=>$product['Product']['title']))?> 
</ul>

<ul class="tab_hooks">
<li><a href="#item_display_main">Main</a></li>
<li><a href="#item_display_media">Media</a></li>
<?php if(Configure::read('Product.sales_options')):?>
<li><a href="#item_display_sales">Sales</a></li>
<?php endif;?>
<li><a href="#item_display_meta">SEO</a></li>
</ul>

<div id="item_display_main" class="tab_page">
<h3>Main</h3>
<div class="content">
<?php echo $textAssistant->htmlFormatted($product['Product']['description'])?>
</div>
<div class="information">
<dl>
<dt>Flags</dt>
<dd><?php if($product['Product']['draft']==1) echo "Draft item";
else echo "Public item";?><br />
<?php if($product['Product']['featured']==1) echo "Featured item";?></dd>
<dt>Created</dt>
<dd><?php echo $time->niceShort($product['Product']['created'])?></dd>
<dt>Modified</dt>
<dd><?php echo $time->niceShort($product['Product']['modified'])?></dd>
</dl>
</div>
</div>

<div id="item_display_media" class="tab_page">
<h3>Media</h3>
<div class="content">
<h4>Image</h4>
<?php
if(!empty($product['Decorative'])) echo $this->element('admin/deco_image',array(
	'deco_id'=>$product['Decorative'][0]['id'],'deco_title'=>$product['Decorative'][0]['title'],'parent'=>$product));
else echo $this->element('admin/deco_image_empty',array('parent'=>$product));
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
<?php echo $this->element('admin/download_view',array(
	'resources'=>$product['Downloadable']
)); ?>
<?php endif;?>
<?php echo $this->element('admin/download_form',array('parent'=>$product))?> 
</div>
<div class="information">
<?php echo $this->element('admin/help/media'); ?> 
</div>
</div>

<?php if(Configure::read('Product.sales_options')):?>
<div id="item_display_sales" class="tab_page">
<h3>Sales</h3>
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

<div id="item_display_meta" class="tab_page">
<h3>SEO</h3>
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
<div class="information">
<?php echo $this->element('admin/help/seo')?> 
</div>
</div>