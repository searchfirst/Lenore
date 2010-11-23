<article class="hproduct">
<h1 class="fn"><?php echo $product['Product']['title'] ?></h1>
<?php echo $this->Menu->makeMenu($breadcrumb,array('class'=>'breadcrumb'));?> 
<div class="description">
<?php echo $this->MediaAssistant->media(array(
	'data' => !empty($product['Decorative'][0])?$product['Decorative'][0]:null,
	'html_attributes' => array('class'=>'deco photo'),
	'conversion_parameter' => 'banner',
	'model' => 'Product',
	'link' => true
));?> 
<div class="info">
<p>Price: £<span class="price"><?php echo $product['Product']['price'];?></span>
</div>
<?php echo $this->TextAssistant->format(array(
	'text'=>$product['Product']['description'],
	'media'=>$product['Resource'],
	'model'=>'Product',
	'media_options'=>array(
		'html_attributes'=>array(),
		'conversion_parameter'=>'crop',
		'link'=>true
	)
));?> 
</div>
<?php if(Configure::read('Product.sales_options')):?>
<?php echo $this->Form->create('Cart',array(
	'url'=>array('controller'=>'cart','action'=>'add'),
	'class'=>'cart'
));?> 
<?php $options = $productOption->getOptionsArray($product['Product']['options']);?>
<?php if(!empty($options)):?>
<?php foreach ($options as $option_title=>$opts):?>
<?php echo $form->input('Cart.options.'.$option_title,array('options'=>array_combine($opts,$opts),'label'=>array('text'=>$option_title,'for'=>"CartOptions$option_title")));?> 
<?php endforeach;?>
<?php endif;?>
<div><?php echo $form->input('Cart.quantity',array('value'=>'1','size'=>'3','maxlength'=>'3','div'=>false));?> @ £<?php echo $product['Product']['price'];?> each</div>
<?php echo $form->input('Cart.name',array('type'=>'hidden','value'=>$product['Product']['title']));?> 
<?php echo $form->input('Cart.product_id',array('type'=>'hidden','value'=>$product['Product']['id']));?> 
<?php echo $form->end('Add to cart');?> 
<?php endif;?>
</article>