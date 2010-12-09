<?php
class Category extends AppModel {
	var $name = 'Category';
	var $actsAs = array('Ordered');
	var $validate = array(
		'title' => array('rule'=>'notEmpty','message'=>"A Category must have a title.")
	);
	var $hasAndBelongsToMany = array(
		"Resource" => array(
			"dependent" => true,
			"conditions" => "Resource.type=1",
			"order" => "Resource.order_by ASC"
		),
		"Decorative" => array(
			"dependent" => true,
			"className" => "Resource",
			"conditions" => "Decorative.type=0",
			"order" => "Decorative.order_by ASC"
		),
		"Downloadable" => array(
			"dependent" => true,
			"className" => "Resource",
			"conditions" => "Downloadable.type=2",
			"order" => "Downloadable.order_by ASC"
		)
	);
	var $hasMany = array(
		"Product" => array(
			"dependent" => true,
			/*"conditions" => "Product.draft=0",*/
			"order" => "Product.order_by ASC"
		),
		"Subcategories" => array(
			"className" => "Category",
			"foreignKey" => "category_id",
			"dependent" => true,
			"order" => "Subcategories.order_by ASC"
		)
	);
	var $belongsTo = array(
		"Parent" => array(
			"className" => "Category",
			"foreignKey" => "category_id"
		)
	);
	var $order = 'Category.order_by ASC';
	var $recursive = 2;

	function beforeSave() {
		parent::beforeSave();
		$this->correctCategoryIdEmptyString();
		return true;
	}
	function correctCategoryIdEmptyString() {
		if(isset($this->data['Category']['category_id']) && $this->data['Category']['category_id']==='')
			$this->data['Category']['category_id'] = null;
	}
}
