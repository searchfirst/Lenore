<h2><?php echo sprintf("Message: %s",$message['Message']['subject']);?></h2>
<ul class="hook_menu">
<li><?php echo $this->element('edit_form',array('controller'=>'messages','model'=>'Message','id'=>$message['Message']['id'],'title'=>$message['Message']['title']))?></li>
<li><?php echo $this->element('delete_form',array('controller'=>'messages','model'=>'Message','id'=>$message['Message']['id'],'title'=>$message['Message']['title']))?></li>
</ul>
<div class="content">
<?php echo $this->element('messages/admin_view/content');?> 
</div>