<div class="options">
<?php if($media_previous) echo $this->renderElement('moveup_form',
	array('model'=>'Resource','controller'=>'Resources','id'=>$media_data['id'],'prev_id'=>$media_previous));?>
<?php echo $this->renderElement('delete_form',array('model'=>'Resource','controller'=>'Resources','id'=>$media_data['id'],'title'=>$media_data['title']));?> 
</div>
<?php echo $mediaAssistant->mediaLink($media_data,array(),'crop',true)?>
