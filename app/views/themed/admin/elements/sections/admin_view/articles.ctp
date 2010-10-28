<?php if($section['Section']['articles_enabled']): ?>
<div class="item">
<h3><?php echo Inflector::pluralize(Configure::read('Article.alias'));?></h3>
<?php if(empty($section['Article'])):?>
<p>No <?php echo Inflector::pluralize(Configure::read('Article.alias'));?></p>
<?php else:?>
<ul class="sortable articles">
<?php foreach($section['Article'] as $article):?>
<li id="Article_<?php echo $article['order_by']; ?>">
<span><?php echo $html->link($article['title'],array('admin'=>true,'controller'=>'articles','action'=>'view',$article['id']))?></span>
<ul class="hook_menu">
<li><?php echo $this->element('edit_form',array('controller'=>'articles','model'=>'Article','id'=>$article['id'],'title'=>$article['title']))?></li>
<li><?php echo $this->element('delete_form',array('controller'=>'articles','model'=>'Article','id'=>$article['id'],'title'=>$article['title']))?></li>
</ul>
<span class="mover"></span>
<span class="dates">
<i><?php echo $time->format('d M Y',$article['created']) ?></i>
<i><?php echo $time->format('d M Y',$article['modified']) ?></i>
</span>
</li>
<?php endforeach;?>
</ul>
<?php endif;?>
</div>
<?php endif;?>