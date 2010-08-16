<h2><?php echo vsprintf("%s: %s",array(Configure::read('Section.alias'),$section['Section']['title'])) ?></h2>
<ul class="hook_menu">
<?php if($section['Section']['articles_enabled']):?>
<li><?php echo $html->link(sprintf("Add %s",Configure::read('Article.alias')),array('admin'=>true,'controller'=>'articles','action'=>'add','?'=>array('data[Article][section_id]'=>$section['Section']['id'])));?></li>
<?php endif; ?>
<li><?php echo $this->element('admin/edit_form',array('controller'=>'sections','model'=>'Section','id'=>$section['Section']['id'],'title'=>$section['Section']['title']))?></li>
<li><?php echo $this->element('admin/delete_form',array('controller'=>'sections','model'=>'Section','id'=>$section['Section']['id'],'title'=>$section['Section']['title']))?></li>
</ul>

<div class="content">
<div class="primary">
<?php echo $this->element('admin/sections/admin_view/articles'); ?> 
<?php echo $this->element('admin/sections/admin_view/content'); ?> 
</div>
<div class="secondary">
<?php echo $this->element('admin/sections/admin_view/information'); ?> 
<?php echo $this->element('admin/sections/admin_view/media'); ?> 
</div>
</div>