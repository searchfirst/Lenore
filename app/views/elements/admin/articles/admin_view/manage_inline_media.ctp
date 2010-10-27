<?php if($inline_media['balance']>0):?>
<p class="problem notification">There are too many items here. You need to remove <?php echo $inline_media['balance'];?> item.</p>
<?php endif;?>
<?php if(!empty($article['Resource'])):?>
<ul>
<?php foreach($article['Resource'] as $resource):?>
<li><?php echo $mediaAssistant->media(array('data'=>$resource,'conversion_parameter'=>'crop','model'=>'article'));?></li>
<?php endforeach;?>
</ul>
<?php endif;?>

<?php if($inline_media['balance']<0):?>
<?php echo $form->create('Article',array('type'=>'file','url'=>array('action'=>'edit')));?>
<p class="notification">You need to upload <?php echo abs($inline_media['balance']) ?> more media files</p>
<?php for($x=1;$x<=abs($inline_media['balance']);$x++):?>
<?php echo $form->hidden("Resource.$x.type",array('value'=>Resource::$types['Inline']));?> 
<?php echo $form->input("Resource.$x.file",array('label'=>'Media','type'=>'file'));?> 
<?php endfor; ?>
<?php echo $form->input('Article.id',array('value'=>$article['Article']['id']));?>
<?php echo $form->end('Upload media') ?>
<?php endif;?>