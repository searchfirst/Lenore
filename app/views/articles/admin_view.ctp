<h2><?php echo sprintf('%s: %s',Configure::read('Article.alias'),$article['Article']['title']); ?></h2>
<ul class="hook_menu">
<li><?php echo $html->link(
	sprintf('Edit %s',Configure::read('Article.alias')),
	array('admin'=>true,'controller'=>'articles','action'=>'edit',$article['Article']['id']),
	array('class'=>'edit button')
); ?></li>
<li><?php echo $html->link(
	sprintf('Delete %s',Configure::read('Article.alias')),
	array('admin'=>true,'controller'=>'articles','action'=>'delete',$article['Article']['id']),
	array('class'=>'delete button')
); ?></li>
</ul>
<div class="content">
<div class="primary">
<?php echo $this->element('articles/admin_view/content'); ?> 
</div>
<div class="secondary">
<?php echo $this->element('articles/admin_view/information'); ?> 
<?php echo $this->element('articles/admin_view/media'); ?> 
</div>
</div>