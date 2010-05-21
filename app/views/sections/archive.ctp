<h2><?php echo $page_title; ?></h2>
<div class="archive_list">
<?php foreach(array_reverse($archive_array,true) as $year => $archive_year):?>
<h3><?php echo $html->link($year, "/archive/$year") ?></h3>
<?php foreach(array_reverse($archive_year,true) as $month => $archive_month):?>
<?php if(!empty($archive_month)):?>
<h4><?php echo $html->link($month, "/archive/$year/$month") ?></h4>
<ul>
<?php foreach($archive_month as $article):?>
<li><?php echo $textAssistant->link($article['Article']['title'],"/{$article['Section']['slug']}/{$article['Article']['slug']}") ?></li>
<?php endforeach;?>
</ul>
<?php endif;?>
<?php endforeach;?>
<?php endforeach;?>
</div>