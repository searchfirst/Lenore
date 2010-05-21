<p>Do you really wish to delete this article?</p>
<form action="<?php echo $html->url("/articles/delete/$id")?>" method="post">
<?php echo $form->hidden('Article.id', array('value'=>$id))?>
<?php echo $form->submit('Yes, delete this article.')?>
</form>