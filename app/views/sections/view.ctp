<h2><?php echo $section['Section']['title']?></h2>
<div class="section_item">
<?php if(!empty($section['Downloadable'])):?>
<ul class="download_list">
<?php foreach($section['Downloadable'] as $download_item):?>
<li><?php echo $mediaAssistant->downloadResourceLink($download_item)?></li>
<?php endforeach;?>
</ul>
<?php endif;?>
<?php if(!empty($section['Decorative'][0])) echo $mediaAssistant->mediaLink($section['Decorative'][0],array('class'=>'banner'),'banner');?>
<?php echo $textAssistant->htmlFormatted($section['Section']['description'],$section['Resource'])?>
<div class="article_list">
<?php foreach($section['Article'] as $article):?>
<div class="item">
<h3><?php if(!empty($article['Decorative'][0])) echo $mediaAssistant->mediaLink($article['Decorative'][0],array('class'=>'deco'),'crop',false,null,'Article');?>
<?php echo $html->link($article['title'],"/{$section['Section']['slug']}/{$article['slug']}")?></h3>
<ul class="info">
<li>Posted: <?php echo $time->niceShort($article['created']) ?></li>
<?php if(MOONLIGHT_ARTICLES_COMMENTS_ENABLED):?>
<li>Comments: <?php echo count($article['Comment']).' | '.$html->link('Leave feedback',"/{$section['Section']['slug']}/{$article['slug']}#post_comment") ?></li>
<?php endif;?>
</ul>
<?php echo $textAssistant->htmlFormattedSnippet($article['description'])?>
</div>
<?php endforeach;?>
</div>
</div>