<p>Do you really wish to delete this category?</p>
<form action="<?php echo $html->url("/admin/categories/delete/$id")?>" method="post">
<?php echo $form->hidden('Category.id', array('value'=>$id))?>
<?php echo $form->submit('Yes, delete this category.')?>
</form>