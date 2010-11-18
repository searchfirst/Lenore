<?php
class Product extends AppModel {
    var $name = 'Product';
    var $validate = array(
		'title'			=>	array('rule'=>'notEmpty','message'=>"Products must have a title."),
		'description'	=>	array('rule'=>'notEmpty','message'=>"Products must have some content.")
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
	var $belongsTo = array("Category");
	var $recursive = 1;
}