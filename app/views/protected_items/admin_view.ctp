<div class="options">
<?php echo $this->renderElement('new_item_form',
	array('parentClass'=>'ProtectedSection','parentName'=>$protectedItem['ProtectedSection']['title'],'parentId'=>$protectedItem['ProtectedSection']['id']))?>
<?php echo $this->renderElement('edit_form',
	array('id'=>$protectedItem['ProtectedItem']['id'],'title'=>$protectedItem['ProtectedItem']['title']))?>
<?php echo $this->renderElement('delete_form',
	array('id'=>$protectedItem['ProtectedItem']['id'],'title'=>$protectedItem['ProtectedItem']['title']))?>
</div>
<h2><?php echo $textAssistant->link($protectedItem['ProtectedSection']['title'],"/protected_sections/view/{$protectedItem['ProtectedSection']['id']}")?> — Protected Item: <?php echo $protectedItem['ProtectedItem']['title']?></h2>

<div id="item_display">
<ul>
<li><a href="#item_display_main">Main</a></li>
<li><a href="#item_display_media">Media</a></li>
</ul>

<div id="item_display_main">
<h3 class="tabs-heading">Main</h3>
<div class="information">
<dl>
<dt>Flags</dt>
<dd><?php if($protectedItem[$this->modelNames[0]]['draft']==1) echo "Draft item";
else echo "Public item";?><br />
<?php if($protectedItem[$this->modelNames[0]]['featured']==1) echo "Featured item";?></dd>
<dt>Created</dt>
<dd><?php echo $time->niceShort($protectedItem['ProtectedItem']['created'])?></dd>
<dt>Modified</dt>
<dd><?php echo $time->niceShort($protectedItem['ProtectedItem']['modified'])?></dd>
</dl>
</div>
<div class="content">
<?php echo $textAssistant->htmlFormatted($protectedItem['ProtectedItem']['description'])?> 
</div>
<hr />
</div>

<div id="item_display_media">
<h3 class="tabs-heading">Media</h3>
<div class="help_information">
<?php echo $this->renderElement('help/media')?>
</div>
<div class="content">
<h4>Image</h4>
<?php
if(!empty($protectedItem['Decorative'])) echo $this->renderElement('deco_image',array(
	'deco_id'=>$protectedItem['Decorative'][0]['id'],'deco_title'=>$protectedItem['Decorative'][0]['title'],'parent'=>$protectedItem));
else echo $this->renderElement('deco_image_empty',array('parent'=>$protectedItem));
?> 
<h4>Inline Media</h4>
<p><?php echo $html->link('Manage inline media','manageinline/'.$protectedItem['ProtectedItem']['id'])?> (<?php
$inline_media_offset = count($protectedItem['Resource']) - ((int) $protectedItem['ProtectedItem']['inline_count']);
if($inline_media_offset == 0)
	echo "You have uploaded the required amount of inline media for this item.";
elseif($inline_media_offset > 0)
	echo "You have too many media files for this item. You need to select $inline_media_offset for deletion.";
else
	echo "You have too few media files for this item. You need to upload ".($inline_media_offset * -1)." more.";
?>)</p>
<h4>Downloads</h4>
<?php if(!empty($protectedItem['Downloadable'])):?>
<?php echo $this->renderElement('download_view',array('resources'=>$protectedItem['Downloadable']))?> 
<?php endif;?>
<?php echo $this->renderElement('download_form',array('parent'=>$protectedItem))?> 
</div>
<hr />
</div>



</div>