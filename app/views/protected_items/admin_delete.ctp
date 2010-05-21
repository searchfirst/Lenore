<p>Do you really wish to delete this item?</p>
<form action="<?php echo $html->url("/protected_items/delete/$id")?>" method="post">
<?php echo $form->hidden('ProtectedItem.id', array('value'=>$id))?> 
<?php echo $form->submit('Yes, delete this item.')?> 
</form>