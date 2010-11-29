<h1><?php echo $email['subject']; ?></h1>
<ul>
<li><b>From:</b> <?php echo $email['name']; ?></li>
<?php if(!empty($email['additional_parameters'])): ?><?php foreach($email['additional_parameters'] as $param => $p_val): ?>
<li><?php echo sprintf('<b>%s:</b> %s',Inflector::humanize($param),$p_val);?></li>
<?php endforeach; ?><?php endif; ?>
</ul>
<?php if(!empty($email['content'])): ?>
<?php echo $email['content']; ?>
<?php echo $this->TextAssistant->format(array(
	'text' => $email['content']
)); ?>
<?php endif ?>