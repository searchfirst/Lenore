<h2>Manage Inline Media</h2>
<div class="content">
<p><?php echo $html->link(sprintf('< Back to %s: %s',Configure::read('Section.alias'),$section['Section']['title']),array('admin'=>true,'controller'=>'sections','action'=>'view',$section['Section']['id'])); ?></p>
<?php if($inline_data['actual_count']>$inline_data['db_count']):?>
<p class="message">You have too many media files for the inline content of this item. You need to delete <?php print ($inline_data['actual_count']-$inline_data['db_count']);?></p>
<?php endif;?>
<?php if($inline_data['db_count']>$inline_data['actual_count']):?>
<p class="message">You need to upload media files to match the content of this item.</p>
<?php endif;?>
<?php if(!empty($media_data)):?>
<ul class="item-list"><?php
$previous_id = 0;
foreach($media_data as $media_item):?>
<li><?php echo $this->element('admin/inline_media',array('media_data'=>$media_item,'parent'=>$inline_data,'media_previous'=>$previous_id))?></li>
<?php $previous_id = $media_item['id'];
endforeach;?></ul
<?php endif;?>
<?php if($inline_data['db_count']>$inline_data['actual_count']):?>
<form action="<?php echo $html->url("/".Inflector::underscore($this->name)."/edit/{$section['Section']['id']}")?>" method="post" enctype="multipart/form-data">
<?php for($i=1;$i<=($inline_data['db_count']-$inline_data['actual_count']);$i++):?>
<div>
<?php echo $form->label('Fileupload.title][',"Descriptive Title $i",array('for'=>"FileuploadTitle$i"))?> 
<?php echo $form->input('Fileupload.title][',array('id'=>"FileuploadTitle$i",'label'=>false,'div'=>false));?> 
</div>
<div>
<?php echo $form->hidden('Fileupload.type][',array('value'=>Resource::$types['Inline'],'id'=>"Fileupload$i"))?> 
<?php echo $form->label("Input$i","Choose media file $i")?> 
<input type="file" name="Fileupload[]" id="FileuploadInput<?php echo $i ?>" />
</div>
<?php endfor;?>
<?php echo $form->submit('Upload inline media')?> 
</form>
<?php endif;?>
</div>