<div class="options">
<?php echo $this->renderElement('new_form');?> 
</div>
<h2>List of <?php echo MOONLIGHT_PROTECTED_SECTIONS_TITLE ?></h2>

<?php if(empty($protectedSections)):?>
<p>No <?php echo MOONLIGHT_PROTECTED_SECTIONS_TITLE ?></p>
<?php else:?>
<ul class="item-list">
<?php foreach ($protectedSections as $protectedSection):?>
<li>
<div class="options">
<?php if(isset($previous_id)) echo $this->renderElement('moveup_form',array('id'=>$protectedSection['ProtectedSection']['id'],'prev_id'=>$previous_id));
$previous_id = $protectedSection['ProtectedSection']['id'];?> 
<?php echo $this->renderElement('edit_form',array('id'=>$protectedSection['ProtectedSection']['id'],'title'=>$protectedSection['ProtectedSection']['title']))?> 
<?php echo $this->renderElement('delete_form',array('id'=>$protectedSection['ProtectedSection']['id'],'title'=>$protectedSection['ProtectedSection']['title']))?> 
</div>
<?php echo $html->link($protectedSection['ProtectedSection']['title'],"/protected_sections/view/{$protectedSection['ProtectedSection']['id']}")?> 
Items: <?php echo count($protectedSection['ProtectedItem']) ?>
</li>
<?php endforeach;?>
</ul>
<?php endif;?>