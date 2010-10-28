<?php
$controller = isset($controller)?Inflector::underscore($controller):Inflector::underscore($this->name);
$model = isset($model)?$model:$this->params['models'][0];
?>
<form method="post" action="<?php echo $html->url("/$controller/add")?>">
<?php echo $form->hidden("Referrer/".Inflector::underscore($parentClass)."_id",array('value'=>$parentId))?> 
<button title="<?php echo "Add ".Inflector::humanize($model)." to $parentName"?>">
<img src="<?php echo "http://{$_SERVER['HTTP_HOST']}".$this->webroot."img/admin/new-item-icon.png"?>" alt="" />
<em><?php echo "Add ".Inflector::humanize($model)?></em>
</button>
</form>