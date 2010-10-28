<div class="item">
<h3>Content</h3>
<?php if($is_mobile): ?>
<?php echo $textAssistant->htmlFormatted($product['Product']['description'])?> 
<?php else: ?>
<iframe class="preview" src="/<?php echo sprintf('%s/%s/%s',Inflector::tableize(Configure::read('Category.alias')),$product['Category']['slug'],$product['Product']['slug']); ?>"></iframe>
<?php endif; ?>
</div>