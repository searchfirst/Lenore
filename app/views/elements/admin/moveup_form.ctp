<?php
$controller = isset($controller)?Inflector::underscore($controller):Inflector::underscore($this->name);
$model = isset($model)?$model:$this->params['models'][0];
?>
<form method="post" action="<?php echo $html->url("/$controller/moveup/")?>">
<?php echo $form->hidden("$model/id",array('id'=>"{$model}Id{$id}",'value'=>$id))?> 
<?php echo $form->hidden("$model/prev_id",array('id'=>"{$model}PrevId{$id}",'value'=>$prev_id))?>
<button title="Move item up">
<img src="/img/admin/moveup-icon.png" alt="" />
<em>Move item up</em>
</button>
</form>