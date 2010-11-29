<?php echo $email['subject']; ?> 
<?php echo str_repeat('=',strlen($email['subject'])); ?> 

From: <?php echo $email['name']; ?> 
<?php if(!empty($email['additional_parameters'])): ?><?php foreach($email['additional_parameters'] as $param => $p_val): ?>
<?php echo sprintf('%s: %s',Inflector::humanize($param),$p_val);?> 
<?php endforeach; ?><?php endif; ?>

<?php if(!empty($email['content'])): ?>
<?php echo $email['content']; ?>
<?php endif ?>