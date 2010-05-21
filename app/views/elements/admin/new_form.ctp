<?php
$controller = isset($controller)?Inflector::underscore($controller):Inflector::underscore($this->name);
$model = isset($model)?$model:$this->params['models'][0];
?> 
<form method="get" action="<?php echo $html->url("/$controller/add")?>">
<button title="Add <?php echo $model ?>">
<img src="<?php echo "http://{$_SERVER['HTTP_HOST']}".$this->webroot."img/admin/new-item-icon.png"?>" alt="" />
<em>Add <?php echo $model ?></em>
</button>
</form>