<h2><?php echo $category['Category']['title']?></h2>
<?php if(!empty($category['Product'])):?>
<p><a href="#product_list">Skip to the products</a></p>
<?php endif; ?>
<?php if(!empty($category['Decorative'][0])) echo $mediaAssistant->mediaLink($category['Decorative'][0],array('class'=>'banner'),'banner',false,null,'Category');?>
<?php echo $textAssistant->htmlFormatted($category['Category']['description'],$category['Resource'],'Category') ?>
<div class='product_list' id="product_list">
<?php if(!empty($category['Product'])):?>
<ul>
<?php foreach($category['Product'] as $product):?>
<li class="item">
<span class="thumbnail"><a href="<?php echo $html->url("/products/{$category['Category']['slug']}/{$product['slug']}") ?>">
<?php if(!empty($product['Decorative'][0])) echo $mediaAssistant->mediaLink($product['Decorative'][0],array('class'=>'alt deco'),'crop',false,null,'Product');?></span>
<?php echo $textAssistant->plainSnippet($product['title']) ?>
</a>
</li>
<?php endforeach; ?>
</ul>
<?php else:?>
<?php endif;?>
</div>