<div>
<?php if(!empty($message['Message']['content'])): ?>
<h3>Message Content</h3>
<?php echo $this->TextAssistant->format(array(
	'text'=>$message['Message']['content'],
)) ?> 
<?php endif ?>
</div>