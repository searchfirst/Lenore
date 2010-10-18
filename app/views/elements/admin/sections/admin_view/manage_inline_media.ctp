<?php if($inline_media['balance']>0):?>
<p class="problem notification">There are too many items here. You need to remove <?php echo $inline_media['balance'];?> item.</p>
<?php endif;?>
<?php if(!empty($section['Resource'])):?>
<ul>
<?php foreach($section['Resource'] as $resource):?>
<li><?php echo $mediaAssistant->media(array('data'=>$resource,'conversion_parameter'=>'crop','model'=>'section'));?></li>
<?php endforeach;?>
</ul>
<?php endif;?>

<?php if($inline_media['balance']<0):?>
<?php echo $form->create('Section',array('type'=>'file','url'=>'edit'));?>
<p class="notification">You need to upload <?php echo abs($inline_media['balance']) ?> more media files</p>
<?php for($x=1;$x<=abs($inline_media['balance']);$x++):?>
<?php echo $form->hidden("Resource.$x.type",array('value'=>Resource::$types['Inline']));?> 
<?php echo $form->input("Resource.$x.file",array('label'=>'Media','type'=>'file'));?> 
<?php endfor; ?>
<?php echo $form->input('Section.id',array('value'=>$section['Section']['id']));?>
<?php echo $form->end('Upload media') ?>
<?php endif;?>