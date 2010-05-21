<h2><?php echo $html->link($article['Section']['title'],"/{$article['Section']['slug']}") ?> » <?php echo $article['Article']['title'] ?></h2>
<div class="article_display">
<?php if(!empty($article['Downloadable']))
	echo $this->renderElement('download_list',array('downloads'=>$article['Downloadable']));?>
<?php if(!empty($article['Decorative'][0])) echo $mediaAssistant->mediaLink($article['Decorative'][0],array('class'=>'deco'),'crop',true,array('rel'=>'article-gallery')); ?>
<?php echo $textAssistant->htmlFormatted($article['Article']['description'],$article['Resource'],'article',
	$mediaAssistant->generateMediaLinkAttributes($article['Resource'],array('rel'=>'article-gallery')));?>
</div>
<?php if(MOONLIGHT_ARTICLES_ENABLE_COMMENTS):?>
<div class="comments" id="view_comments">
<?php if(!empty($article['Comment'])):?>
<h3>Comments</h3>
<?php foreach($article['Comment'] as $comment) {?>
<?php print $textAssistant->htmlFormatted(strip_tags($comment['description']))?> 
<ul class="info">
<li>Author: <?php if(empty($comment['uri'])) echo $textAssistant->sanitiseText($comment['author'],true,true);
else echo $html->link($textAssistant->sanitiseText($comment['author'],true,true),$comment['uri']) ?></li>
<li>Posted: <?php echo $time->niceShort($comment['created'])?></li>
</ul>
<?php }?>
<?php endif;?>
<form action="<?php echo $html->url('/comments') ?>" method="post" accept-charset="UTF-8" id="post_comment">
<fieldset>
<legend>Leave a comment</legend>
<p class="info">You must provide at least your name, a valid email address (used for spam checking and moderation), and a message.</p>
<?php echo $form->input('Comment.author',array('size'=>'60','maxlength'=>'150','label'=>'Name','error'=>'Please provide your name.'))?> 
<?php echo $form->input('Comment.email', array('size'=>'60','maxlength'=>'200','error'=>'Please provide a valid email address. It will be kept totally private.'))?> 
<?php echo $form->input('Comment.uri',array('type'=>'text','size'=>'60','maxlength'=>'256','label'=>'Website','error'=>'You must begin your address with.'))?> 
<?php echo $form->input('Comment.description',array('cols'=>'40','rows'=>'7','label'=>'Comment','error'=>'Please enter some feedback.'))?> 
<?php echo $form->hidden('Comment.article_id',array('value'=>$article['Article']['id']))?> 
<?php echo $form->submit('Submit Feedback')?> 
</fieldset>
</form>
</div>
<?php endif;?>