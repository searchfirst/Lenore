<h2><?php echo sprintf('%s Categories',Configure::read('Category.alias')); ?></h2>
<ul class="hook_menu">
<li><?php echo $this->element('admin/new_form',array('model'=>'Category','controller'=>'categories','l_title'=>'Product Category'));?></li>
</ul>
<div class="content">
<table class="sortable">
<colgroup span="2"></colgroup>
<colgroup span="2" class="flags"></colgroup>
<colgroup span="2" class="dates"></colgroup>
<thead>
<tr>
<th>Title</th>
<th><?php echo Inflector::pluralize(Configure::read('Category.alias')) ?></th>
<th colspan="2">Flags</th>
<th>Created</th>
<th>Modified</th>
</tr>
</thead>
<tbody class="categories">
<?php if(empty($categories)):?>
<tr><td colspan="6">No <?php echo Inflector::pluralize(Configure::read('Category.alias')); ?></td></tr>
<?php else:?>
<?php foreach ($categories as $category): ?>
<tr id="Category_<?php echo $category['Category']['id']; ?>">
<td>
<span><?php echo $html->link($textAssistant->sanitiseText($category['Category']['title']),array('admin'=>true,'controller'=>'categories','action'=>'view',$category['Category']['id'])); ?></span>
<ul class="hook_menu">
<li><?php echo $this->element('admin/edit_form',array('id'=>$category['Category']['id'],'title'=>$category['Category']['title'],'controller'=>'categories','model'=>'Category','l_title'=>'Product Category'))?> </li>
<li><?php echo $this->element('admin/delete_form',array('id'=>$category['Category']['id'],'title'=>$category['Category']['title'],'controller'=>'categories','model'=>'Category','l_title'=>'Product Category'))?> </li>
</ul>
</td>
<td><?php echo count($category['Product']) ?></td>
<td><img src="/img/admin/flag-<?php if($category['Category']['draft']) {
echo "draft.png\" alt=\"Draft\"";
} else {
echo "published.png\" alt=\"Published\"";
}?> class="flag"></td>
<td><img src="/img/admin/flag-<?php if($category['Category']['featured']) {
echo "flagged.png\" alt=\"Featured\"";
} else {
echo "unflagged.png\" alt=\"Normal\"";
}?> class="flag"></td>
<td><?php echo $time->format('d M Y',$category['Category']['created']) ?></td>
<td><?php echo $time->format('d M Y',$category['Category']['modified']) ?></td>
<?php unset($previous_sub_id);
foreach ($category['Subcategories'] as $subcategory): ?>
</li>
<li class="sub">
<div class="options">
<?php if(isset($previous_sub_id)) echo $this->element('moveup_form',array('id'=>$subcategory['id'],'prev_id'=>$previous_sub_id));
$previous_sub_id = $subcategory['id'];?> 
<?php echo $this->element('admin/edit_form',array('id'=>$subcategory['id'],'title' => $subcategory['title']))?> 
<?php echo $this->element('admin/delete_form',array('id'=>$subcategory['id'],'title' => $subcategory['title']))?> 
</div>
<?php echo $html->link($subcategory['title'],'/categories/view/'.$subcategory['id'])?> 
Products: <?php echo count($subcategory['Product'])?>
<?php endforeach; ?>
</tr>
<?php endforeach; ?>
<?php endif;?>
</tbody>
</table>
</div>