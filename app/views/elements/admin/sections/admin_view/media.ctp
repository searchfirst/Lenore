<div class="item">
<h3>Thumbnail Image</h3>
<?php
if(!empty($section['Decorative'])) echo $this->element('admin/deco_image',array('deco_id'=>$section['Decorative'][0]['id'],
	'deco_title'=>$section['Decorative'][0]['title'],'parent'=>$section));
else echo $this->element('admin/deco_image_empty',array('model'=>'Section','parent'=>$section));
?> 
<h3>Inline Media</h3>
<p><?php echo $html->link('Manage inline media','manageinline/'.$section['Section']['id']) ?> (<?php
$inline_media_offset = count($section['Resource']) - ((int) $section['Section']['inline_count']);
if($inline_media_offset == 0)
	echo "You have uploaded the required amount of inline media for this item.";
elseif($inline_media_offset > 0)
	echo "You have too many media files for this item. You need to select $inline_media_offset for deletion.";
else
	echo "You have too few media files for this item. You need to upload ".($inline_media_offset * -1)." more.";
?>)</p>
<h3>Downloads</h3>
<?php if(empty($section['Downloadable'])):?>
<?php else:?>
<?php echo $this->element('admin/download_view',array(
	'resources'=>$section['Downloadable']
)); ?>
<?php endif;?> 
<?php echo $this->element('admin/download_form',array('model'=>'Section','parent'=>$section));?> 
</div>