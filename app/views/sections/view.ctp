<h1><?php echo $section['Section']['title']?></h1>
<?php echo $this->MediaAssistant->media(array(
	'data' => !empty($section['Decorative'][0])?$section['Decorative'][0]:null,
	'html_attributes' => array('class'=>'banner'),
	'conversion_parameter' => 'banner',
	'model' => 'Section'
));?> 
<?php echo $this->TextAssistant->format(array(
	'text'=>$section['Section']['description'],
	'media'=>$section['Resource'],
	'model'=>'Section',
	'media_options'=>array(
		'conversion_parameter'=>'crop',
		'html_parameters'=>array()
	)
));?> 
<ul class="articles">
<?php foreach($section['Article'] as $article):?>
<li>
<?php if(!empty($article['Decorative'][0])) echo $mediaAssistant->mediaLink($article['Decorative'][0],array('class'=>'deco'),'crop',false,null,'Article');?>
<?php echo $html->link($article['title'],"/{$section['Section']['slug']}/{$article['slug']}")?>
</li>
<?php endforeach;?>
</ul>