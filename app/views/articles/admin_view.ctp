<div class="options">
<?php echo $this->renderElement('new_item_form',
	array('parentClass'=>'Section','parentName'=>$article['Section']['title'],'parentId'=>$article['Section']['id']))?>
<?php echo $this->renderElement('edit_form',
	array('id'=>$article['Article']['id'],'model'=>'Article','count'=>0,'title'=>$article['Article']['title']))?>
<?php echo $this->renderElement('delete_form',
	array('id'=>$article['Article']['id'],'model'=>'Article','count'=>0,'title'=>$article['Article']['title']))?>
</div>
<h2><?php echo $textAssistant->link($article['Section']['title'],"/sections/view/{$article['Section']['id']}")?> — <?php echo MOONLIGHT_ARTICLES_TITLE ?>: <?php echo $article['Article']['title']?></h2>

<div id="item_display">
<ul>
<li><a href="#item_display_main">Main</a></li>
<li><a href="#item_display_media">Media</a></li>
<li><a href="#item_display_meta">SEO</a></li>
</ul>


<div id="item_display_main">
<h3 class="tabs-heading">Main</h3>
<div class="information">
<dl>
<dt>Flags</dt>
<dd><?php if($article[$this->modelNames[0]]['draft']==1) echo "Draft item";
else echo "Public item";?><br />
<?php if($article[$this->modelNames[0]]['featured']==1) echo "Featured item";?></dd>
<dt>Created</dt>
<dd><?php echo $time->niceShort($article['Article']['created'])?></dd>
<dt>Modified</dt>
<dd><?php echo $time->niceShort($article['Article']['modified'])?></dd>
</dl>
</div>
<div class="content">
<?php echo $textAssistant->htmlFormatted($article['Article']['description'])?>
</div>
</div>
<div id="item_display_media">
<div class="help_information">
<h4>Inline Media</h4>
<p>Images, or other media, that you have included in your content with use of the {[media]} tag. Using this tag, Moonlight will automatically replace it with the media files that you upload.</p>
<h4>Downloads</h4>
<p>Downloads could include PDF datasheets, extra images for download, or software downloads. Ensure that you provide a title and description with your download so that your visitors understand the download.</p>
</div>
<div class="content">
<h3 class="tabs-heading">Media</h3>
<h4>Image</h4>
<?php
if(!empty($article['Decorative']))
	echo $this->renderElement('deco_image',array(
		'deco_id'=>$article['Decorative'][0]['id'],
		'deco_title'=>$article['Decorative'][0]['title'],
		'model_name'=>'Article',
		'parent'=>$article));
else
		echo $this->renderElement('deco_image_empty',array(
		'model_name'=>'Article',
		'parent'=>$article));
?>
<h4>Inline Media</h4>
<p><?php echo $html->link('Manage inline media','manageinline/'.$article['Article']['id']) ?> (<?php
$inline_media_offset = count($article['Resource']) - ((int) $article['Article']['inline_count']);
if($inline_media_offset == 0)
	echo "You have uploaded the required amount of inline media for this item.";
elseif($inline_media_offset > 0)
	echo "You have too many media files for this item. You need to select $inline_media_offset for deletion.";
else
	echo "You have too few media files for this item. You need to upload ".($inline_media_offset * -1)." more.";
?>)</p>
<h4>Downloads</h4>
<?php if(empty($article['Downloadable'])):?>
<?php else:?>
<?php echo $this->renderElement('download_view',array(
	'resources'=>$article['Downloadable']
)); ?>
<?php endif;?>
<?php echo $this->renderElement(
		'download_form',array(
			'model_name'=>'Article',
			'parent'=>$article
		)
	);
?>
</div>
</div>
<div id="item_display_meta">
<h3 class="tabs-heading">SEO</h3>
<div class="help_information">
<p class="info">Use this area to make tweaks to the metadata on the page. If you are unsure about whether to do this then leave it alone. None of this is required so you can leave it blank if you wish.</p>
</div>
<dl>
<dt>Description</dt>
<?php if(!empty($article['Article']['meta_description'])):?>
<dd><?php echo $textAssistant->sanitiseText($article['Article']['meta_description'])?></dd>
<?php else:?>
<dd>&lt;Not set&gt;</dd>
<?php endif;?>
<dt>Keywords</dt>
<?php if(!empty($article['Article']['meta_keywords'])):?>
<dd><?php echo $textAssistant->sanitiseText($article['Article']['meta_keywords'])?></dd>
<?php else:?>
<dd>&lt;Not set&gt;</dd>
<?php endif;?>
</dl>
<form method="post" accept-type="UTF-8" action="<?php echo $html->url("/articles/edit/{$article['Article']['id']}")?>">
<fieldset>
<legend>Update Metadata</legend>
<?php echo $form->input('Article.meta_description',array('value'=>$article['Article']['meta_description']))?> 
<?php echo $form->input('Article.meta_keywords',array('value'=>$article['Article']['meta_keywords']))?> 
<?php echo $form->hidden('Article.id',array('value'=>$article['Article']['id']))?> 
<?php echo $form->submit('Update')?> 
</fieldset>
</form>
</div>
</div>