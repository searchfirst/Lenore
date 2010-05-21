<p>Do you really wish to delete this section?</p>
<form action="<?php echo $html->url("/sections/delete/$id") ?>" method="post">
<?php echo $form->hidden('Section.id', array('value'=>$id)) ?>
<?php echo $form->submit('Yes, delete this section.') ?>
</form>