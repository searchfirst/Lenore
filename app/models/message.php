<?php
class Message extends AppModel {
	var $name = 'Message';
	var $validate = array(
		'name' => array(
			'rule'=>'notEmpty',
			'message'=>"You must give a name."
		),
	);
	var $actsAs = array(
		'Cacher' => array('register_caches'=>array('messages_a'))
	);

	function __construct($id=false,$table=null,$ds=null) {
		parent::__construct($id,$table,$ds);
		if($cc = Configure::read('Lenore.i18n.country_code')) {
			$this->cc = strtolower($cc);
			$i18n_plugin = 'Localized.'.$cc.'Validation';
			App::import('Lib',$i18n_plugin);
		}
	}

	function beforeSave() {
		parent::beforeSave();
		$this->serializeAdditionalParameters();
		return true;
	}

	function afterFind($results,$primary) {
		$results = parent::afterFind($results,$primary);
		$this->unserializeAdditionalParameters($results);
		return $results;
	}

	function unserializeAdditionalParameters(&$data) {
		foreach($data as $x=>$message) if(!empty($message['Message']['additional_parameters']))
			$data[$x]['Message']['additional_parameters'] = unserialize($message['Message']['additional_parameters']);
	}

	function serializeAdditionalParameters() {
		if(!empty($this->data['Message']['additional_parameters']) && is_array($this->data['Message']['additional_parameters']))
			$this->data['Message']['additional_parameters'] = serialize($this->data['Message']['additional_parameters']);
	}

	function getOptions(&$options,$type) {
		$type = str_replace('-','_',$type);
		$config_options = Configure::read("Message.options.{$type}");
		$options = array_merge($config_options,$options);
	}
	
	function isValidType($type) {
		$type = str_replace('-','_',$type);
		return (bool) Configure::read("Message.options.{$type}");
	}
}
