<h2><?php echo sprintf("Snippet: %s",$snippet['Snippet']['title']);?></h2>
<ul class="hook_menu">
<li><?php echo $this->element('edit_form',array('controller'=>'snippets','model'=>'Snippet','id'=>$snippet['Snippet']['id'],'title'=>$snippet['Snippet']['title']))?></li>
<li><?php echo $this->element('delete_form',array('controller'=>'snippets','model'=>'Snippet','id'=>$snippet['Snippet']['id'],'title'=>$snippet['Snippet']['title']))?></li>
</ul>
<div class="content">
<?php echo $this->element('snippets/admin_view/content');?> 
</div>