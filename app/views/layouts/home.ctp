<!doctype html>

<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<title><?php echo $textAssistant->sanitise(Configure::read('Moonlight.website_name'))." | ".$textAssistant->sanitise(Configure::read('Moonlight.website_description')) ?></title>
<?php if(!empty($metadata_for_layout)) echo $this->element('metadata',array(
	'cache'=>true,'key'=>md5(serialize($metadata_for_layout))
));?> 
<?php echo $this->element('css',array(
	'cache'=>true,'key'=>'fcss'
));?> 
<?php echo $this->element('js',array(
	'cache'=>true,'key'=>'fjs'
));?> 
</head>
<body id="home"><?php echo $session->check('Message.flash')?$session->flash():''; ?><div id="main">
<header id="header" role="banner">
<h1><?php echo $textAssistant->sanitise(Configure::read('Moonlight.website_name'));?></h1>
</header>
<?php echo $this->element('menu',array(
	'cache'=>true,'key'=>'fmenu'
))?> 
<?php echo $this->element('sidebar',array(
	'cache'=>true,'key'=>'fsidebar'
))?> 
<section id="content" role="main">
<?php echo $content_for_layout;?> 
</section>
<?php echo $this->element('footer',array(
	'cache'=>true,'key'=>'ffooter'
))?> 
</div></body>
</html>