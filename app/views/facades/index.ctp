<h1>Welcome</h1>
<?php echo $this->MediaAssistant->media(array(
	'data' => !empty($home_section['Decorative'][0])?$home_section['Decorative'][0]:null,
	'html_attributes' => array('class'=>'deco'),
	'conversion_parameter' => 'crop',
	'model' => 'Section'
));?> 
<?php echo $this->TextAssistant->format(array(
	'text'=>$home_section['Section']['description'],
	'media'=>$home_section['Resource'],
	'model'=>'Section',
	'media_options'=>array(
		'conversion_parameter'=>'crop',
		'html_parameters'=>array()
	)
));?>