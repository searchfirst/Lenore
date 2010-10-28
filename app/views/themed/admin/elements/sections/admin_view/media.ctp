<div class="item">
<h3>Thumbnail Image</h3>
<?php
if(!empty($section['Decorative'])) echo $this->element('deco_image',array('deco_id'=>$section['Decorative'][0]['id'],
	'deco_title'=>$section['Decorative'][0]['title'],'parent'=>$section));
else echo $this->element('deco_image_empty',array('model'=>'Section','parent'=>$section));
?> 
<?php if($inline_media['count'] || $inline_media['balance']!=0):?>
<h3>Inline Media</h3>
<?php echo $this->element('sections/admin_view/manage_inline_media');?> 
<?php endif;?>
<h3>Downloads</h3>
<?php if(empty($section['Downloadable'])):?>
<?php else:?>
<?php echo $this->element('download_view',array('resources'=>$section['Downloadable']));?> 
<?php endif;?>
<?php echo $this->element('download_form',array('model'=>'Section','parent'=>$section));?> 
</div>
