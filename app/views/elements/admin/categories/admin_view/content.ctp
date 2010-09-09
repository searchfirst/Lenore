<div class="item">
<h3>Content</h3>
<?php if($is_mobile): ?>
<?php echo $textAssistant->htmlFormatted($category['Category']['description'])?> 
<?php else: ?>
<iframe class="preview" src="/<?php echo sprintf('%s/%s',Inflector::tableize(Configure::read('Category.alias')),$category['Category']['slug']); ?>"></iframe>
<?php endif; ?>
</div>