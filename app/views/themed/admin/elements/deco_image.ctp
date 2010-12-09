<span><?php echo $mediaAssistant->media(array(
	'data' => !empty($parent['Decorative'][0])?$parent['Decorative'][0]:null,
	'html_attributes' => array('class'=>''),
	'conversion_parameter' => 'crop',
	'model' => $model
));?></span>
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
