<p>Do you really wish to delete this category?</p>
<?php echo $form->create('Product',array('url'=>"delete/$id")); ?> 
<?php echo $form->hidden('Product.id', array('value'=>$id))?> 
<?php echo $form->end(array('label'=>'Yes, delete this product.'))?> 
</form>