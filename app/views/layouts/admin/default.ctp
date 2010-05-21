<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php if(!empty($title_for_layout)) echo $textAssistant->sanitiseText("$title_for_layout |");?> Website Administration</title>
<?php echo $this->element('admin/js');?> 
<?php echo $this->element('admin/css');?>
</head>
<body><div id="main">
<header id="header">
<h1>Moonlight</h1>
<?php echo $this->element('admin/menu')?> 
</header>
<div id="content">
<?php
echo $content_for_layout;
?> 
</div>
<?php if ($session->check('Message.flash')) echo $session->flash();?>
<?php if($session->check('Message.auth')) echo $session->flash('auth');?>
</div></body>
</html>