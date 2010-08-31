<div class="item">
<h3>Content</h3>
<?php if($is_mobile): ?>
<?php echo $textAssistant->htmlFormatted($section['Section']['description'])?> 
<?php else: ?>
<iframe class="preview" src="/<?php echo $section['Section']['slug'] ?>"></iframe>
<?php endif; ?>
</div>