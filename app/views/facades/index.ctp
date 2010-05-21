<h2>Welcome</h2>
<div class="article_list">

<?php if(!empty($home_section['Decorative'][0]))
echo $mediaAssistant->mediaLink($home_section['Decorative'][0],array('class'=>'deco'),'crop',true,null,'Section')?> 
<?php echo $textAssistant->htmlFormatted($home_section['Section']['description'],$home_section['Resource'],'Article',
	$mediaAssistant->generateMediaLinkAttributes($home_section['Resource'],array('rel'=>'home-gallery')))?> 
<?php if(!empty($home_articles)):?>
<?php foreach($home_articles as $home_article){?>
<div class="item">
<h3><?php echo $home_article['title'] ?></h3>
<?php echo $textAssistant->htmlFormattedSnippet($home_article['description'])?>
</div>
<?php }?>
<?php endif;?>

<?php if(!empty($star_articles)):?>
<div class="star_articles">
<?php foreach($star_articles as $star_article) {?>
<div class="item">
<h3><?php echo $textAssistant->link($star_article['Article']['title'],"/{$star_article['Section']['slug']}/{$star_article['Article']['slug']}")?></h3>
<ul class="info">
<li>Posted: <?php echo $time->niceShort($star_article['Article']['created']) ?></li>
<?php if(MOONLIGHT_ARTICLES_ENABLE_COMMENTS):?>
<li>Comments: <?php echo count($star_article['Comment']).' | '.$html->link('Leave feedback',"/{$star_article['Section']['slug']}/{$star_article['Article']['slug']}#post_comment") ?></li>
<?php endif;?>
<?php foreach($star_article['Downloadable'] as $star_download) {?>
<li><?php echo $mediaAssistant->downloadResourceLink($star_download,null,null,'Article')?></li>
<?php }?>
</ul>
<?php echo $textAssistant->htmlFormattedSnippet($star_article['Article']['description'])?>
</div>
<?php }?>
</div>
<?php endif;?>

<?php if(!empty($blog_articles)):?>
<div class="recent_articles">
<?php foreach($blog_articles as $i=>$blog_article) {?>
<?php if($i==0):?>
<div class="item">
<h3><?php if(!empty($blog_article['Decorative'][0]))
echo $mediaAssistant->mediaLink($blog_article['Decorative'][0],array('class'=>'deco'),'crop',true,null,'Article');
?> 
<?php echo $textAssistant->link($blog_article['Article']['title'],"/{$blog_article['Section']['slug']}/{$blog_article['Article']['slug']}")?></h3>
<ul class="info">
<li>Posted: <?php echo $time->niceShort($blog_article['Article']['created'])?></li>
<?php if(MOONLIGHT_ARTICLES_ENABLE_COMMENTS):?>
<li>Comments: <?php echo count($blog_article['Comment']).' | '.$html->link('Leave feedback',"/{$blog_article['Section']['slug']}/{$blog_article['Article']['slug']}#post_comment") ?></li>
<?php endif;?>
</ul>
<?php echo $textAssistant->htmlFormattedSnippet($blog_article['Article']['description'])?> 
</div>
<h3>Recent Articles</h3>
<ul>
<?php else:?>
<li><?php echo $textAssistant->link($blog_article['Article']['title'],"/{$blog_article['Section']['slug']}/{$blog_article['Article']['slug']}")?></li>
<?php endif;?>
<?php }?>
</ul>
</div>
<?php endif;?>

</div>