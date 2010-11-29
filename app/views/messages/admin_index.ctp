<h2>Messages</h2>
<ul class="hook_menu">
<li><?php echo $html->link("Add Message",array('admin'=>true,'controller'=>'messages','action'=>'add'));?></li>
</ul>
<div class="content">
<?php if(empty($messages)):?>
<p>No Messages</p>
<?php else:?>
<ul class="admin_list">
<?php foreach($messages as $message):?>
<li>
<span><?php echo $html->link($message['Message']['subject'],array('admin'=>true,'controller'=>'messages','action'=>'view',$message['Message']['id']))?></span>
<ul class="hook_menu">
<li><?php echo $this->element('edit_form',array('controller'=>'messages','model'=>'Message','id'=>$message['Message']['id'],'title'=>$message['Message']['subject']))?></li>
<li><?php echo $this->element('delete_form',array('controller'=>'messages','model'=>'Message','id'=>$message['Message']['id'],'title'=>$message['Message']['subject']))?></li>
</ul>
</li>
<?php endforeach;?>
<?php endif;?>
<ul class="paginate">
<?php echo $this->Paginator->numbers(array(
	'separator'=>'',
	'tag'=>'li',
	'model'=>'Message'
)) ?>
</ul>
</div>