<p>Do you really wish to delete this category?</p>
<form action="<?php echo $html->url("/products/delete/$id")?>" method="post">
<?php echo $form->hidden('Product.id', array('value'=>$id))?> 
<?php echo $form->submit('Yes, delete this product.')?> 
</form>