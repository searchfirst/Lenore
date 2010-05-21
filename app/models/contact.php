<?php
class Contact extends AppModel {
	var $useTable = false;
	var $name = 'Contact';

	var $validate = array(
		'name'			=> array('rule'=>'notEmpty','message'=>"Contacts must give a name."),
		'email'			=> array('rule'=>'email','message'=>"Contacts must give a valid email address"),
		'telephone'		=> '/[0-9\s]*/i',
		'enquiry'		=> array('rule'=>'notEmpty','message'=>"Contacts must include a message.")
	);
	
	function loadInfo() {return new Set(array(array()));}  //temp hack until fixed
	
	function beforeSave() {return true;}
	
}
?>