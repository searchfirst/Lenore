<h2><?php echo Inflector::pluralize(Configure::read('Section.alias')) ?></h2>
<ul class="hook_menu">
<li><?php echo $html->link(sprintf("Add %s",Configure::read('Section.alias')),array('admin'=>true,'controller'=>'sections','action'=>'add')) ?></li>
</ul>
<div class="content">
<?php if(empty($sections)):?>
<p>No <?php echo Inflector::pluralize(Configure::read('Section.alias')) ?></p>
<?php else:?>
<table class="sortable">
<colgroup span="2"></colgroup>
<colgroup span="2" class="flags"></colgroup>
<colgroup span="2" class="dates"></colgroup>
<thead><tr>
<th>Title</th>
<th><?php echo Inflector::pluralize(Configure::read('Article.alias'));?></th>
<th colspan="2">Flags</th>
<th>Created</th>
<th>Modified</th>
</thead>
<tbody class="sections">
<?php foreach($sections as $section):?>
<tr id="<?php echo sprintf("%s_%s",'Section',$section['Section']['id']);?>">
<td><span><?php echo $html->link($section['Section']['title'],array('admin'=>true,'controller'=>'sections','action'=>'view',$section['Section']['id']))?></span>
<ul class="hook_menu">
<li><?php echo $html->link(sprintf("Edit %s",Configure::read('Section.alias')),array('admin'=>true,'controller'=>'section','action'=>'edit')) ?></li>
<li><?php echo $html->link(sprintf("Delete %s",Configure::read('Section.alias')),array('admin'=>true,'controller'=>'section','action'=>'delete')) ?></li>
</ul>
</td>
<td><?php echo count($section['Article'])?></td>
<td><img src="/img/admin/flag-<?php if($section['Section']['draft']) {
echo "draft.png\" alt=\"Draft\"";
} else {
echo "published.png\" alt=\"Published\"";
}?> class="flag"></td>
<td><img src="/img/admin/flag-<?php if($section['Section']['flagged']) {
echo "flagged.png\" alt=\"Flagged\"";
} else {
echo "unflagged.png\" alt=\"Unflagged\"";
}?> class="flag"></td>
<td><?php echo substr($time->toRSS($section['Section']['created']),0,-15);?></td>
<td><?php echo substr($time->toRSS($section['Section']['modified']),0,-15);?></td>
</tr>
<?php endforeach;?>
</tbody>
</table>
<?php endif;?>
</div>