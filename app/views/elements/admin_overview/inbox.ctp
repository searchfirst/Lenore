<h3>Inbox</h3>
<?php if(!empty($messages_a)): ?>
<ul class="admin_list">
<?php foreach($messages_a as $message): ?>
<li>
<?php echo $this->Html->link($message['Message']['subject'],array('admin'=>true,'controller'=>'messages','action'=>'view',$message['Message']['id'])) ?>
<ul class="hook_menu">
<li><?php echo $this->Html->link(__('Delete Message',true),
array(
	'admin'=>true,'controller'=>'messages','action'=>'delete',$message['Message']['id']
),
array(
	'class'=>'ajax-modal delete'
)) ?></li>
</ul>
</li>
<?php endforeach ?>
</ul>
<ul class="paginate">
<?php echo $this->Paginator->numbers(array(
	'separator'=>false,
	'tag'=>'li',
	'model'=>'Message',
	'url'=>array('admin'=>'true','controller'=>'messages')
)) ?>
</ul>
<?php else: ?>
<p>Empty Inbox</p>
<?php endif ?>