<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n" ?>
<rss version="2.0" xml:base="http://<?php echo $_SERVER['HTTP_HOST'] ?>/">
<channel>
	<title><?php echo $textAssistant->sanitiseText(Configure::read('Moonlight.website_name'))." | ".$title_for_layout;?></title>
	<description><?php echo $textAssistant->sanitiseText(Configure::read('Moonlight.website_description'));?></description>
<?php if(!empty($mod_date_for_layout)):?>
	<lastBuildDate><?php echo $mod_date_for_layout; ?> GMT</lastBuildDate>
<?php endif;?>
<?php echo $content_for_layout;?>
</channel>
</rss>