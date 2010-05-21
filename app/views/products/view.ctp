<h2><?php echo $html->link($product['Category']['title'],"/products/{$product['Category']['slug']}",array('title'=>"Go back to {$product['Category']['title']}"));?>: <?php echo $product['Product']['title'] ?></h2>
<div class="product_display">
<?php if(!empty($product['Decorative'][0])) echo $mediaAssistant->mediaLink($product['Decorative'][0],array("class"=>"deco"),'banner',true,array("rel"=>"pagegallery"));?> 
<?php echo $textAssistant->htmlFormatted($product['Product']['description'],$product['Resource'],'Product',array("rel"=>"pagegallery"))?> 
<?php if(defined('MOONLIGHT_PRODUCTS_SALES_OPTIONS') && MOONLIGHT_PRODUCTS_SALES_OPTIONS):?>
<form method="post" action="<?php echo $html->url('/cart/add');?>" accept-type="utf-8" class="cart">
<?php $options = $productOption->getOptionsArray($product['Product']['options']);?>
<?php if(!empty($options)):?>
<?php foreach ($options as $option_title=>$opts):?>
<?php echo $form->input('Cart.options.'.$option_title,array('options'=>array_combine($opts,$opts),'label'=>array('text'=>$option_title,'for'=>"CartOptions$option_title")));?> 
<?php endforeach;?>
<?php endif;?>
<div><?php echo $form->input('Cart.quantity',array('value'=>'1','size'=>'3','maxlength'=>'3','div'=>false));?> @ £<?php echo $product['Product']['price'];?> each</div>
<?php echo $form->input('Cart.name',array('type'=>'hidden','value'=>$product['Product']['title']));?> 
<?php echo $form->input('Cart.product_id',array('type'=>'hidden','value'=>$product['Product']['id']));?> 
<?php echo $form->submit('Add to cart');?>
</form>
<?php endif;?>
</div>