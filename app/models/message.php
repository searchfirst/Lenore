<?php
class Message extends AppModel {
	var $name = 'Message';
	var $validate = array(
		'name' => array(
			'rule'=>'notEmpty',
			'message'=>"You must give a name."
		),
		'email' => array(
			'rule'=>'email',
			'message'=>"You must give a valid email address"
		),
/*		'phone' => array(
			'phone' => array(
				'rule' => array('phone',null,$this->cc)
			)
		),*/
		'content' => array(
			'rule'=>'notEmpty',
			'message'=>"You must include a message."
		)
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
		if(!empty($this->data['Message']['additional_parameters']) && is_array($this->data['Message']['additional_parameters'])) {
			$this->data['Message']['additional_parameters'] = serialize($this->data['Message']['additional_parameters']);
		}
		return true;
	}

	function afterFind($results,$primary) {
		parent::afterFind();
		if(!empty($results[0]['Message'])) {
			if(!empty($results[0]['Message']['additional_parameters'])) {
				$results[0]['Message']['additional_parameters'] = unserialize($results[0]['Message']['additional_parameters']);
			}
		}
		return true;
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
?>