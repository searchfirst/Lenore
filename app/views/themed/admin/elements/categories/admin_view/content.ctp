<div class="item">
<h3>Content</h3>
<?php if($is_mobile): ?>
<?php echo $textAssistant->htmlFormatted($category['Category']['description'])?> 
<?php else: ?>
<iframe class="preview" src="<?php echo $this->Html->url(array(
	'controller'=>'categories',
	'slug'=>$category['Category']['slug'],
	'admin'=>false,
	'action'=>'view'
)) ?>"></iframe>
<?php endif; ?>
</div>
