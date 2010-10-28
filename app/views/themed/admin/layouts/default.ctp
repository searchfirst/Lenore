<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<title><?php if(!empty($title_for_layout)) echo $textAssistant->sanitiseText("$title_for_layout |");?> Website Administration</title>
<?php echo $this->element('js');?> 
<?php echo $this->element('css');?>
</head>
<body><?php if ($session->check('Message.flash')) echo $session->flash();?><?php if($session->check('Message.auth')) echo $session->flash('auth');?><div id="main">
<header id="header">
<h1>Moonlight</h1>
<?php echo $this->element('menu')?> 
</header>
<div id="content">
<?php
echo $content_for_layout;
?> 
</div>
</div>
</body>
</html>