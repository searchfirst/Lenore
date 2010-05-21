<div class="options">
<?php echo $this->renderElement('new_item_form',array('model'=>'ProtectedItem','controller'=>'ProtectedItems','parentClass'=>'ProtectedSection','parentName'=>$protectedSection['ProtectedSection']['title'],'parentId'=>$protectedSection['ProtectedSection']['id']))?> 
<?php echo $this->renderElement('edit_form',array('id'=>$protectedSection['ProtectedSection']['id'],'title'=>$protectedSection['ProtectedSection']['title']))?> 
<?php echo $this->renderElement('delete_form',array('id'=>$protectedSection['ProtectedSection']['id'],'title'=>$protectedSection['ProtectedSection']['title']))?> 
</div>
<h2>Protected Section: <?php echo $protectedSection['ProtectedSection']['title']?></h2>

<div id="item_display">
<ul>
<li><a href="#item_display_main">Main</a></li>
<li><a href="#item_display_media">Media</a></li>
<li><a href="#item_display_children">Protected Items</a></li>
</ul>

<div id="item_display_main">
<h3 class="tabs-heading">Main</h3>
<div class="information">
<dt>Flags</dt>
<dd><?php if($protectedSection[$this->modelNames[0]]['draft']==1) echo "Draft item";
else echo "Public item";?><br />
<?php if($protectedSection[$this->modelNames[0]]['featured']==1) echo "Featured item";?></dd>
<dt>Created</dt>
<dd><?php echo $time->niceShort($protectedSection['ProtectedSection']['created'])?></dd>
<dt>Modified</dt>
<dd><?php echo $time->niceShort($protectedSection['ProtectedSection']['modified'])?></dd>
<dt>Password</dt>
<dd><?php echo $protectedSection['ProtectedSection']['password']?></dd>
</div>
<div class="content">
<?php echo $textAssistant->htmlFormatted($protectedSection['ProtectedSection']['description'])?> 
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
if(!empty($protectedSection['Decorative'])) echo $this->renderElement('deco_image',array(
	'deco_id'=>$protectedSection['Decorative'][0]['id'],'deco_title'=>$protectedSection['Decorative'][0]['title'],'parent'=>$protectedSection));
else
	echo $this->renderElement('deco_image_empty',array('parent'=>$protectedSection));
?> 
<h4>Inline Media</h4>
<p><?php echo $html->link('Manage inline media','manageinline/'.$protectedSection['ProtectedSection']['id'])?> (<?php
$inline_media_offset = count($protectedSection['Resource']) - ((int) $protectedSection['ProtectedSection']['inline_count']);
if($inline_media_offset == 0)
	echo "You have uploaded the required amount of inline media for this item.";
elseif($inline_media_offset > 0)
	echo "You have too many media files for this item. You need to select $inline_media_offset for deletion.";
else
	echo "You have too few media files for this item. You need to upload ".($inline_media_offset * -1)." more.";
?>)</p>
<h4>Downloads</h4>
<?php if(!empty($protectedSection['Downloadable'])):?>
<?php echo $this->renderElement('download_view',array('resources'=>$protectedSection['Downloadable']))?> 
<?php endif;?>
<?php echo $this->renderElement('download_form',array('parent'=>$protectedSection))?> 
</div>
<hr />
</div>

<div id="item_display_children">
<h3 class="tabs-heading">Protected Items</h3>

<?php if(empty($protectedSection['ProtectedItem'])):?>
<p>No <?php echo MOONLIGHT_PROTECTED_ITEMS_TITLE ?></p>
<?php else:?>
<ul class="item-list">
<?php foreach($protectedSection['ProtectedItem'] as $protectedItem):?>
<li>
<div class="options">
<?php
if(isset($previous_id)) echo $this->renderElement('moveup_form',array(
	'controller'=>'protected_items','model'=>'ProtectedItem','id'=>$protectedItem['id'],'prev_id'=>$previous_id));
$previous_id = $protectedItem['id'];
?>
<?php echo $this->renderElement('edit_form',array(
	'controller'=>'protected_items','id'=>$protectedItem['id'],'model'=>'ProtectedItem','title'=>$protectedItem['title']))?> 
<?php echo $this->renderElement('delete_form',array(
	'controller'=>'ProtectedItems','id'=>$protectedItem['id'],'model'=>'ProtectedItem','title'=>$protectedItem['title']))?> 
</div>
<?php echo $html->link($protectedItem['title'],"/protected_items/view/{$protectedItem['id']}") ?></td>
</li>
<?php endforeach;?>
</ul>
<?php endif;?>
</div>
</div>