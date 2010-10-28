<div class="item">
<h3>Thumbnail Image</h3>
<?php
if(!empty($category['Decorative'])) echo $this->element('deco_image',array(
		'deco_id'=>$category['Decorative'][0]['id'],'deco_title'=>$category['Decorative'][0]['title'],'parent'=>$category));
else echo $this->element('deco_image_empty',array('parent'=>$category));
?> 
<?php if($inline_media['count'] || $inline_media['balance']!=0):?>
<h3>Inline Media</h3>
<?php echo $this->element('categories/admin_view/manage_inline_media');?>
<?php endif;?>
<h3>Downloads</h3>
<?php if(!empty($category['Downloadable'])):?>
<?php echo $this->element('download_view',array('resources'=>$category['Downloadable']))?> 
<?php endif;?>
<?php echo $this->element('download_form',array('parent'=>$category))?> 
</div>