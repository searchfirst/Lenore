<div class="options">
<?php echo $this->renderElement('new_form');?> 
</div>
<h2>List of <?php echo MOONLIGHT_CATEGORIES_TITLE ?></h2>

<?php if(empty($categories)):?>
<p>No <?php echo MOONLIGHT_CATEGORIES_TITLE?></p>
<?php else:?>
<ul class="item-list">
<?php foreach ($categories as $category): ?>
<li>
<div class="options">
<?php if(isset($previous_id)) echo $this->renderElement('moveup_form',array('id'=>$category['Category']['id'],'prev_id'=>$previous_id));
$previous_id = $category['Category']['id'];?> 
<?php echo $this->renderElement('edit_form',array('id'=>$category['Category']['id'],'title'=>$category['Category']['title']))?> 
<?php echo $this->renderElement('delete_form',array('id'=>$category['Category']['id'],'title'=>$category['Category']['title']))?> 
</div>
<?php echo $html->link($category['Category']['title'],"/categories/view/{$category['Category']['id']}") ?> 
Products: <?php echo count($category['Product']) ?>
<?php unset($previous_sub_id);
foreach ($category['Subcategories'] as $subcategory): ?>
</li>
<li class="sub">
<div class="options">
<?php if(isset($previous_sub_id)) echo $this->renderElement('moveup_form',array('id'=>$subcategory['id'],'prev_id'=>$previous_sub_id));
$previous_sub_id = $subcategory['id'];?> 
<?php echo $this->renderElement('edit_form',array('id'=>$subcategory['id'],'title' => $subcategory['title']))?> 
<?php echo $this->renderElement('delete_form',array('id'=>$subcategory['id'],'title' => $subcategory['title']))?> 
</div>
<?php echo $html->link($subcategory['title'],'/categories/view/'.$subcategory['id'])?> 
Products: <?php echo count($subcategory['Product'])?>
<?php endforeach; ?>
</li>
<?php endforeach; ?>
</ul>
<?php endif;?>