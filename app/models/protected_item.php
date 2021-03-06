<?php
class ProtectedItem extends AppModel {
    var $name = 'ProtectedItem';
    var $validate = array(
		'title'			=>	array('rule'=>'notEmpty','message'=>"Protected Items must have a title."),
		'description'	=>	array('rule'=>'notEmpty','message'=>"Protected Items must have some content.")
	);
	var $hasAndBelongsToMany = array(
					"Resource" => array(
						"dependent" => true,
						"conditions" => "Resource.type=1",
						"order" => "order_by ASC"
					),
					"Decorative" => array(
						"dependent" => true,
						"className" => "Resource",
						"conditions" => "Decorative.type=0",
						"order" => "order_by ASC"
					),
					"Downloadable" => array(
						"dependent" => true,
						"className" => "Resource",
						"conditions" => "Downloadable.type=2",
						"order" => "order_by ASC"
					)
	);
	var $belongsTo = array("ProtectedSection");
	var $recursive = 1;

	function beforeSave() {
		if(empty($this->id))
			$this->data[$this->name]['slug'] = $this->getUniqueSlug($this->data[$this->name]['title']);
		return true;
	}
}
?>