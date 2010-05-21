<h2>List Items</h2>

<table cellpadding="0" cellspacing="0">
<thead><tr>
<th>Title</th>
<th class="ancillary">Section</th>
</tr></thead>
<tfoot><tr><td colspan="2"><?php print $html->formTag('/protected_items/add')?>
<?php print $html->submit('New Protected Item')?></form></td></tr></tfoot>
<tbody>
<?php foreach ($protectedItems as $protectedItem):?>
<tr>
<td>
<?php echo $this->renderElement('edit_form',array('id'=>$protectedItem['ProtectedItem']['id'],'title'=>$protectedItem['ProtectedItem']['title']))?> 
<?php echo $this->renderElement('delete_form',array('id'=>$protectedItem['ProtectedItem']['id'],'title'=>$protectedItem['ProtectedItem']['title']))?> 
<?php echo $html->link($protectedItem['ProtectedItem']['title'],"/protected_items/view/{$protectedItem['ProtectedItem']['id']}")?>
</td>
<td><?php echo $html->link($protectedItem['ProtectedItem']['title'],"/protected_sections/view/{$protectedItem['ProtectedSection']['id']}")?></td>
</tr>
<?php endforeach; ?>
<?php if(empty($protectedItems)):?>
<tr><td colspan="2">No <?php echo MOONLIGHT_PROTECTED_ITEMS_TITLE ?></td></tr>
<?php endif;?>
</tbody>
</table>