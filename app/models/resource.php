<?php
class Resource extends AppModel {
	var $name = 'Resource';
	var $validate = array();
	var $hasAndBelongsToMany = array("Article","Section","Product","Category");
	public static $types = array('Decorative'=>0,'Inline'=>1,'Download'=>2);

	function beforeValidate() {
		return true;
	}
	
	function beforeDelete() {
		if($resource=$this->findById($this->id))
			unlink($resource['Resource']['path'].$resource['Resource']['slug'].'.'.$resource['Resource']['extension']);
		return true;
	}


}
?>