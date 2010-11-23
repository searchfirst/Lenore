<h1><?php echo $category['Category']['title']?></h1>
<?php echo $this->MediaAssistant->media(array(
	'data' => !empty($category['Decorative'][0])?$category['Decorative'][0]:null,
	'html_attributes' => array('class'=>'banner'),
	'conversion_parameter' => 'banner',
	'model' => 'Category'
));?> 
<?php echo $this->TextAssistant->format(array(
	'text'=>$category['Category']['description'],
	'media'=>$category['Resource'],
	'model'=>'Category',
	'media_options'=>array(
		'html_attributes'=>array(),
		'conversion_parameter'=>'crop'
	)
));?> 
<div>
<?php if(!empty($category['Product'])):?>
<ul>
<?php foreach($category['Product'] as $product):?>
<li>
<a href="<?php echo $html->url("/products/{$category['Category']['slug']}/{$product['slug']}") ?>">
<?php echo $this->MediaAssistant->media(array(
	'data' => !empty($product['Decorative'][0])?$product['Decorative'][0]:null,
	'conversion_parameter' => 'crop',
	'model' => 'Product',
));?> 
<?php echo $product['title'];?> 
</a>
</li>
<?php endforeach;?>
</ul>
<?php else:?>
<?php endif;?>
</div>