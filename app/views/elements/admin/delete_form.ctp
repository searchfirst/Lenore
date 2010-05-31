<?php $controller = isset($controller)?Inflector::underscore($controller):Inflector::underscore($this->name);
$model = isset($model)?$model:$this->params['models'][0];?>
<form method="post" action="<?php echo $html->url("/$controller/delete/$id")?>" enctype="multipart/form-data" class="delete_form">
<?php echo $form->hidden("$model/id", array('id'=>"{$model}DeleteId{$id}",'value'=>0))?> 
<?php echo $form->hidden("$model/check_id", array('id'=>"{$model}CheckId{$id}",'class'=>'check','value'=>$id))?> 
<button title="Delete <?php echo $title?>">
<img src="/img/admin/delete-icon.png" ?>" alt="" />
<em>Delete <?php echo $model ?></em>
</button>
</form>
