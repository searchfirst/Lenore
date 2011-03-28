<?php if($inline_media['balance']>0): ?>
<p class="problem notification">There are too many items here. You need to remove <?php echo $inline_media['balance'] ?> item.</p>
<?php endif ?>
<?php if(!empty($category['Resource'])): ?>
<ul class="sortable resources admin_list">
<?php foreach($category['Resource'] as $resource): ?>
<li id="Resource_<?php echo $resource['id'] ?>">
<span><?php echo $mediaAssistant->media(array('data'=>$resource,'conversion_parameter'=>'admin_crop','model'=>'section')) ?></span>
<ul class="hook_menu">
<li><?php echo $this->element('edit_form',array('controller'=>'resources','model'=>'Resource','id'=>$resource['id'])) ?></li>
<li><?php echo $this->element('delete_form',array('controller'=>'resources','model'=>'Resource','id'=>$resource['id'])) ?></li>
</ul>
</li>
<?php endforeach ?>
</ul>
<?php endif ?>

<?php if($inline_media['balance']<0): ?>
<?php echo $form->create('Category',array('type'=>'file','url'=>array('action'=>'edit'))) ?>
<p class="notification">You need to upload <?php echo abs($inline_media['balance']) ?> more media files</p>
<?php for($x=1;$x<=abs($inline_media['balance']);$x++): ?>
<?php echo $form->hidden("Resource.$x.type",array('value'=>Resource::$types['Inline'])) ?> 
<?php echo $form->input("Resource.$x.file",array('label'=>'Media','type'=>'file')) ?> 
<?php endfor ?>
<?php echo $form->input('Category.id',array('value'=>$category['Category']['id'])) ?>
<?php echo $form->end('Upload media') ?>
<?php endif ?>
