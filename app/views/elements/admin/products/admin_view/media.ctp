<div class="item">
<h3>Thumbnail Image</h3>
<?php
if(!empty($product['Decorative'])) echo $this->element('admin/deco_image',array(
		'deco_id'=>$product['Decorative'][0]['id'],'deco_title'=>$product['Decorative'][0]['title'],'parent'=>$product));
else echo $this->element('admin/deco_image_empty',array('parent'=>$product));
?> 
<h3>Inline Media</h3>
<p><?php echo $html->link('Manage inline media','manageinline/'.$product['Category']['id']) ?> (<?php
$inline_media_offset = count($product['Resource']) - ((int) $product['Category']['inline_count']);
if($inline_media_offset == 0)
	echo "You have uploaded the required amount of inline media for this item.";
elseif($inline_media_offset > 0)
	echo "You have too many media files for this item. You need to select $inline_media_offset for deletion.";
else
	echo "You have too few media files for this item. You need to upload ".($inline_media_offset * -1)." more.";
?>)</p>
<h3>Downloads</h3>
<?php if(!empty($product['Downloadable'])):?>
<?php echo $this->element('admin/download_view',array('resources'=>$product['Downloadable']))?> 
<?php endif;?>
<?php echo $this->element('admin/download_form',array('parent'=>$product))?> 
</div>