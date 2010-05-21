<?php
class Cart extends AppModel {
	var $name = 'Cart';
	var $validate = array();
	var $useTable = false;

	function schema() {
		return array(
			'options' => array(
				'type' => 'array'
				),
			'name' => array(
				'type' => 'string',
				'length' => 255
				),
			'typehash' => array(
				'type' => 'string',
				'length' => 32
				),
			'quantity' => array(
				'type' => 'integer'
				)
		);
	}
	
	function mergeContents(&$new_data,&$existing_data) {
		$data = $existing_data;
		$typehash = md5($new_data['Cart']['name'].serialize($new_data['Cart']['options']));
		if(isset($data[$typehash]))
			$data[$typehash]['quantity'] = (int) $data[$typehash]['quantity'] + (int) $new_data['Cart']['quantity'];
		else
			$data[$typehash] = $new_data['Cart'];
		return $data;
	}
	
	function getContents($session_data) {
		return $session_data;
	}

}
?>