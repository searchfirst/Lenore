<h1><?php echo $article['Article']['title'] ?></h1>
<?php echo $this->Menu->makeMenu($breadcrumb,array('class'=>'breadcrumb'));?> 
<?php echo $this->MediaAssistant->media(array(
	'data' => !empty($article['Decorative'][0])?$article['Decorative'][0]:null,
	'html_attributes' => array('class'=>'banner'),
	'conversion_parameter' => 'banner',
	'model' => 'Article'
));?> 
<?php echo $this->TextAssistant->format(array(
	'text'=>$article['Article']['description'],
	'media'=>$article['Resource'],
	'model'=>'Article',
	'media_options'=>array(
		'conversion_parameter'=>'crop',
		'html_parameters'=>array()
	)
));?> 
