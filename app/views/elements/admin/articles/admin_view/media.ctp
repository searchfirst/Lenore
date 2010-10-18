<div class="item">
<h3>Thumbnail Image</h3>
<?php
if(!empty($section['Decorative'])) echo $this->element('admin/deco_image',array('deco_id'=>$article['Decorative'][0]['id'],
	'deco_title'=>$article['Decorative'][0]['title'],'parent'=>$article));
else echo $this->element('admin/deco_image_empty',array('model'=>'Article','parent'=>$article));
?> 
<?php if($inline_media['count'] || $inline_media['balance']!=0):?>
<h3>Inline Media</h3>
<?php echo $this->element('admin/articles/admin_view/manage_inline_media');?>
<?php endif;?>
<h3>Downloads</h3>
<?php if(empty($section['Downloadable'])):?>
<?php else:?>
<?php echo $this->element('admin/download_view',array(
	'resources'=>$article['Downloadable']
)); ?>
<?php endif;?> 
<?php echo $this->element('admin/download_form',array('model'=>'Article','parent'=>$article));?> 
</div>
