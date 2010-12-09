<div class="item">
<h3>Thumbnail Image</h3>
<?php if(!empty($product['Decorative'])): ?>
<?php echo $this->element('deco_image',array(
	'deco_id'=>$product['Decorative'][0]['id'],'deco_title'=>$product['Decorative'][0]['title'],'parent'=>$product,'model'=>'Product'
));?> 
<?php else: ?>
<?php echo $this->element('deco_image_empty',array('parent'=>$product));?> 
<?php endif ?>
<?php if($inline_media['count'] || $inline_media['balance']!=0): ?>
<h3>Inline Media</h3>
<?php echo $this->element('products/admin_view/manage_inline_media');?>
<?php endif ?>
<h3>Downloads</h3>
<?php if(!empty($product['Downloadable'])):?>
<?php echo $this->element('download_view',array('resources'=>$product['Downloadable']))?> 
<?php endif;?>
<?php echo $this->element('download_form',array('parent'=>$product))?> 
</div>
