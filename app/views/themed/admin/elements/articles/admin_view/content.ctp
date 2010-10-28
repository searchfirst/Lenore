<div class="item">
<h3>Content</h3>
<?php if($is_mobile): ?>
<?php echo $textAssistant->htmlFormatted($article['Article']['description'])?> 
<?php else: ?>
<iframe class="preview" src="/<?php echo $article['Section']['slug'].'/'.$article['Article']['slug'] ?>"></iframe>
<?php endif; ?>
</div>