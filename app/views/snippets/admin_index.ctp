<h2>Snippets</h2>
<ul class="hook_menu">
<li><?php echo $html->link("Add Snippet",array('admin'=>true,'controller'=>'snippets','action'=>'add'));?></li>
</ul>
<div class="content">
<?php if(empty($snippets)):?>
<p>No Snippets</p>
<?php else:?>
<ul class="admin_list">
<?php foreach($snippets as $snippet):?>
<li>
<span><?php echo $html->link($snippet['Snippet']['title'],array('admin'=>true,'controller'=>'snippets','action'=>'view',$snippet['Snippet']['id']))?></span>
<ul class="hook_menu">
<li><?php echo $this->element('edit_form',array('controller'=>'snippets','model'=>'Snippet','id'=>$snippet['Snippet']['id'],'title'=>$snippet['Snippet']['title']))?></li>
<li><?php echo $this->element('delete_form',array('controller'=>'snippets','model'=>'Snippet','id'=>$snippet['Snippet']['id'],'title'=>$snippet['Snippet']['title']))?></li>
</ul>
</li>
<?php endforeach;?>
<?php endif;?>
</div>