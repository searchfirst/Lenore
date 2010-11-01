<h2><?php echo Inflector::pluralize(Configure::read('Section.alias')) ?></h2>
<ul class="hook_menu">
<li><?php echo $html->link(sprintf("Add %s",Configure::read('Section.alias')),array('admin'=>true,'controller'=>'sections','action'=>'add')) ?></li>
</ul>
<div class="content">
<?php if(empty($sections)):?>
<p>No <?php echo Inflector::pluralize(Configure::read('Section.alias')) ?></p>
<?php else:?>
<ul class="sortable sections">
<?php foreach($sections as $section):?>
<li id="<?php echo sprintf("%s_%s",'Section',$section['Section']['id']);?>" class="<?php
$sortable_sections_flags = array();
if($section['Section']['draft']) $sortable_sections_flags[] = 'draft';
if($section['Section']['featured']) $sortable_sections_flags[] = 'featured';
echo implode(" ",$sortable_sections_flags);
?>">
<span><?php echo $html->link($section['Section']['title'],array('admin'=>true,'controller'=>'sections','action'=>'view',$section['Section']['id']))?></span>
<ul class="hook_menu">
<li><?php echo $this->element('edit_form',array('controller'=>'sections','model'=>'Section','id'=>$section['Section']['id'],'title'=>$section['Section']['title']))?></li>
<li><?php echo $this->element('delete_form',array('controller'=>'sections','model'=>'Section','id'=>$section['Section']['id'],'title'=>$section['Section']['title']))?></li>
</ul>
<?php if((integer) $section['Article'] > 0): ?>
<span class="item_count"><?php echo sprintf("%s %s",count($section['Article']),((integer) $section['Article']>1?'Articles':'Article'))?></span>
<?php else: ?>
<span></span>
<?php endif; ?>
</li>
<?php endforeach;?>
<?php endif;?>
</div>