<h1><?php echo $section['Section']['title'];?></h1>
<?php if(!empty($section)):?>
<?php echo $this->MediaAssistant->media(array(
	'data' => !empty($section['Decorative'][0])?$section['Decorative'][0]:null,
	'html_attributes' => array('class'=>'deco'),
	'conversion_parameter' => 'crop',
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
<?php endif;?>
<?php echo $this->Form->create('Message',array('url'=>'/'.$this->params['url']['url']));?> 
<?php echo $this->Form->input('Message.name');?> 
<?php echo $this->Form->input('Message.email');?> 
<?php echo $this->Form->input('Message.content');?> 
<?php echo $this->Form->end(__('Send Email',true));?>