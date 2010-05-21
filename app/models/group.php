<?php
class Group extends AppModel {
	var $name = 'Group';
	var $displayField = 'name';
	var $hasMany = array('User');
	var $actsAs = array('Acl'=>array('type'=>'requester'));

	function parentNode() {return null;}
}
?>