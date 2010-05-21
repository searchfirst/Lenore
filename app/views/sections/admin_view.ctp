<h2><?php echo vsprintf("%s: %s",array(Configure::read('Section.alias'),$section['Section']['title'])) ?></h2>
<ul class="hook_menu">
<li><?php echo $html->link(sprintf("Add %s",Configure::read('Article.alias')),array('admin'=>true,'controller'=>'articles','action'=>'add','?'=>array('section_id'=>$section['Section']['id'])));?></li>
<li><?php echo $html->link(sprintf("Edit %s",Configure::read('Section.alias')),array('admin'=>true,'controller'=>'sections','action'=>'edit',$section['Section']['id']));?></li>
<li><?php echo $html->link(sprintf("Delete %s",Configure::read('Section.alias')),array('admin'=>true,'controller'=>'sections','action'=>'delete',$section['Section']['id']));?></li>
</ul>

<ul class="tab_hooks">
<li><a href="#item_display_main">Main</a></li>
<li><a href="#item_display_media">Media</a></li>
<li><a href="#item_display_children">Articles</a></li>
<li><a href="#item_display_meta">SEO</a></li>
</ul>

<div id="item_display_main" class="tab_page">
<h3>Main</h3>
<div class="content">
<?php echo $textAssistant->htmlFormatted($section['Section']['description'])?> 
</div>
<div class="information">
<dl>
<dt>Flags</dt>
<dd><?php if((int)$section['Section']['draft']) echo "Draft item";
else echo "Public item";?><br />
<?php if((int)$section['Section']['featured']) echo "Featured item";?></dd>
<dt>Created</dt>
<dd><?php echo $time->niceShort($section['Section']['created'])?></dd>
<dt>Modified</dt>
<dd><?php echo $time->niceShort($section['Section']['modified'])?></dd>
</dl>
</div>
</div>

<div id="item_display_media" class="tab_page">
<h3>Media</h3>
<div class="content">
<h4>Image</h4>
<?php
if(!empty($section['Decorative'])) echo $this->element('admin/deco_image',array('deco_id'=>$section['Decorative'][0]['id'],
	'deco_title'=>$section['Decorative'][0]['title'],'parent'=>$section));
else echo $this->element('admin/deco_image_empty',array('model'=>'Section','parent'=>$section));
?> 
<h4>Inline Media</h4>
<p><?php echo $html->link('Manage inline media','manageinline/'.$section['Section']['id']) ?> (<?php
$inline_media_offset = count($section['Resource']) - ((int) $section['Section']['inline_count']);
if($inline_media_offset == 0)
	echo "You have uploaded the required amount of inline media for this item.";
elseif($inline_media_offset > 0)
	echo "You have too many media files for this item. You need to select $inline_media_offset for deletion.";
else
	echo "You have too few media files for this item. You need to upload ".($inline_media_offset * -1)." more.";
?>)</p>
<h4>Downloads</h4>
<?php if(empty($section['Downloadable'])):?>
<?php else:?>
<?php echo $this->element('admin/download_view',array(
	'resources'=>$section['Downloadable']
)); ?>
<?php endif;?> 
<?php echo $this->element('admin/download_form',array('model'=>'Section','parent'=>$section));?> 
</div>
<div class="help_information">
<?php echo $this->element('admin/help/media')?> 
</div>
</div>

<div id="item_display_children" class="tab_page">
<h3><?php echo Inflector::pluralize(Configure::read('Article.alias'));?></h3>
<div class="content">
<?php if(empty($section['Article'])):?>
<p>No <?php echo Inflector::pluralize(Configure::read('Article.alias'));?></p>
<?php else:?>
<ul class="item-list">
<?php foreach($section['Article'] as $article):?>
<li>
<div class="options">
<?php if(!isset($page_data)):?>
<?php if(isset($previous_id)) echo $this->element('admin/moveup_form',array('controller'=>'articles','model' => 'Article','id'=>$article['id'],'prev_id'=>$previous_id));
$previous_id = $article['id'];?> 
<?php endif;?> 
<?php echo $this->element('admin/edit_form',array('controller'=>'articles','model'=>'Article','id'=>$article['id'],'title'=>$article['title']))?> 
<?php echo $this->element('admin/delete_form',array('controller'=>'articles','model'=>'Article','id'=>$article['id'],'title'=>$article['title']))?> 
</div>
<?php echo $html->link($article['title'],"/articles/view/{$article['id']}")?>
</li>
<?php endforeach;?>
</ul>
<?php endif;?>
<?php if(isset($page_data)):?>
<ul class="page_list">
<?php if($page_data['has_prev']):?>
<?php $previous_link = $page_data['current'] > 2 ? '/'.($page_data['current']-1) : '';?>
<li><?php print $html->link("Back","/sections/view/{$section['Section']['id']}$previous_link#item_display_children") ?></li>
<?php endif;?>
<?php if($page_data['has_next']):?>
<?php $next_link = "/".($page_data['current']+1);?>
<li><?php print $html->link("Next","/sections/view/{$section['Section']['id']}$next_link#item_display_children") ?></li>
<?php endif;?>
</ul>
<?php endif;?>
</div>
</div>

<div id="item_display_meta" class="tab_page">
<h3 class="tabs-heading">SEO</h3>
<div class="content">
<dl>
<dt>Description</dt>
<?php if(!empty($section['Section']['meta_description'])):?>
<dd><?php echo $textAssistant->sanitiseText($section['Section']['meta_description'])?></dd>
<?php else:?>
<dd>&lt;Not set&gt;</dd>
<?php endif;?>
<dt>Keywords</dt>
<?php if(!empty($section['Section']['meta_keywords'])):?>
<dd><?php echo $textAssistant->sanitiseText($section['Section']['meta_keywords'])?></dd>
<?php else:?>
<dd>&lt;Not set&gt;</dd>
<?php endif;?>
</dl>
<form method="post" accept-type="UTF-8" action="<?php echo $html->url("/sections/edit/{$section['Section']['id']}")?>">
<fieldset>
<legend>Update Metadata</legend>
<?php echo $form->input('Section.meta_description',array('value'=>$section['Section']['meta_description']))?> 
<?php echo $form->input('Section.meta_keywords',array('value'=>$section['Section']['meta_keywords']))?> 
<?php echo $form->hidden('Section.id',array('value'=>$section['Section']['id']))?> 
<?php echo $form->submit('Update')?> 
</fieldset>
</form>
</div>
<div class="help_information">
<?php echo $this->element('admin/help/seo')?>
</div>
</div>