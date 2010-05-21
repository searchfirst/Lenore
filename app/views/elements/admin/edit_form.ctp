<?php
$controller = isset($controller)?Inflector::underscore($controller):Inflector::underscore($this->name);
$model = isset($model)?$model:$this->params['models'][0];
?> 
<form method="post" action="<?php echo $html->url("/$controller/edit/$id")?>" class="model_command">
<button title="Edit <?php echo $textAssistant->sanitiseText($title) ?>">
<img src="<?php echo "http://{$_SERVER['HTTP_HOST']}".$this->webroot."images/edit-icon.png"?>" alt="" />
<em>Edit <?php echo $model ?></em>
</button>
</form>
