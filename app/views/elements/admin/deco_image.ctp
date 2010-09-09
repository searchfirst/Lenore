<span><?php echo $mediaAssistant->mediaLink($parent['Decorative'][0],null,'crop',true);?></span>
<ul class="hook_menu">
<li><?php echo $html->link(
	sprintf('Delete %s',Configure::read('Resource.alias')),
	array(
		'admin'=>true,
		'controller'=>'resources',
		'action'=>'delete',
		$parent['Decorative'][0]['id']
	),
	array(
		'class'=>'ajax-modal delete button'
	)
); ?></li>
</ul>