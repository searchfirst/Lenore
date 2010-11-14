<h2><?php echo vsprintf("%s: %s",array(Configure::read('Section.alias'),$section['Section']['title'])) ?></h2>
<ul class="hook_menu">
<li><?php echo $this->element('edit_form',array('controller'=>'sections','model'=>'Section','id'=>$section['Section']['id'],'title'=>$section['Section']['title']))?></li>
<li><?php echo $this->element('delete_form',array('controller'=>'sections','model'=>'Section','id'=>$section['Section']['id'],'title'=>$section['Section']['title']))?></li>
</ul>

<div class="content">
<div class="primary">
<?php echo $this->element('sections/admin_view/articles'); ?> 
<?php echo $this->element('sections/admin_view/content'); ?> 
</div>
<div class="secondary">
<?php echo $this->element('sections/admin_view/information'); ?> 
<?php echo $this->element('sections/admin_view/media'); ?> 
</div>
</div>