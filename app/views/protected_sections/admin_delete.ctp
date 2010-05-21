<p>Do you really wish to delete this section?</p>
<form action="<?php echo $html->url("/protected_sections/delete/$id")?>" method="post">
<?php echo $form->hidden('ProtectedSection.id', array('value'=>$id)) ?>
<?php echo $form->submit('Yes, delete this section.') ?>
</form>