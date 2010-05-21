<?php if(!empty($metadata_for_layout['description'])):?>
<meta name="Description" content="<?php echo $textAssistant->sanitiseText($metadata_for_layout['description'],true,true)?>">
<?php endif;?><?php if(!empty($metadata_for_layout['keywords'])):?>
<meta name="Keywords" content="<?php echo $textAssistant->sanitiseText($metadata_for_layout['keywords'],true,true)?>">
<?php endif;?>