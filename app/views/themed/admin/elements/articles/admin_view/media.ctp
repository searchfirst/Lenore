<div class="item">
<h3>Thumbnail Image</h3>
<?php if(!empty($article['Decorative'])): ?>
<?php echo $this->element('deco_image',array(
	'deco_id'=>$article['Decorative'][0]['id'],'deco_title'=>$article['Decorative'][0]['title'],'parent'=>$article,'model'=>'Article'
));?> 
<?php else: ?>
<?php echo $this->element('deco_image_empty',array('parent'=>$article));?> 
<?php endif ?>
<?php if($inline_media['count'] || $inline_media['balance']!=0):?>
<h3>Inline Media</h3>
<?php echo $this->element('articles/admin_view/manage_inline_media');?>
<?php endif;?>
<h3>Downloads</h3>
<?php if(empty($section['Downloadable'])):?>
<?php else:?>
<?php echo $this->element('download_view',array(
	'resources'=>$article['Downloadable']
)); ?>
<?php endif;?> 
<?php echo $this->element('download_form',array('model'=>'Article','parent'=>$article));?> 
</div>
