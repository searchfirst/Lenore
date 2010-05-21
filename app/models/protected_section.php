<?php
class ProtectedSection extends AppModel {
	var $name = 'ProtectedSection';
	var $validate = array(
		'title'			=>	array('rule'=>'notEmpty','message'=>"Protected Sections must include a title."),
//		'description'	=>	array('rule'=>'notEmpty','message'=>"Protected Sections must include some content.")
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
	var $hasMany = array(
				"ProtectedItem" => array(
					"dependent" => true,
					"order" => "order_by ASC"
				)
	);
	var $recursive = 1;

	function authenticate($username,$password_hash) {
		if(empty($username) || empty($password_hash))
			return false;
		else {
			$user_auth_data = $this->findByTitle($username);
			if(!empty($user_auth_data) && ($password_hash === md5($user_auth_data['ProtectedSection']['password'])))
				return $user_auth_data;
			else
				return false;
		}
	}

	function beforeSave() {
		if(empty($this->id))
			$this->data[$this->name]['slug'] = $this->getUniqueSlug($this->data[$this->name]['title']);
		return true;
	}

}
?>