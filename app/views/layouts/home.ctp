<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $textAssistant->sanitiseText(Configure::read('Moonlight.website_name'))." | ".$textAssistant->sanitiseText(Configure::read('Moonlight.website_description')) ?></title>
<?php if(!empty($metadata_for_layout)) echo $this->renderElement('metadata');?> 
<?php echo $this->element('css',array('cache'=>'1 day'))?> 
<?php echo $this->element('js',array('cache'=>'1 day')) ?> 
</head>
<body id="home"><div id="main">
<header id="header">
<h1><?php echo $textAssistant->sanitiseText(Configure::read('Moonlight.website_name'));?></h1>
</header>
<?php echo $this->element('menu',array('cache'=>'1 day'))?> 
<?php echo $this->element('sidebar')?> 
<div id="content">
<?php
if ($session->check('Message.flash')) echo $session->flash();
echo $content_for_layout;
?> 
</div>
<?php echo $this->element('footer',array('cache'=>'1 day'))?> 
</div></body>
</html>
