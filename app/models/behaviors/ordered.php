<?php
class OrderedBehavior extends ModelBehavior {
	function afterSave(&$model,$created) {if($created && empty($model->ordered)) $model->saveField('order_by',$model->id);}
}
