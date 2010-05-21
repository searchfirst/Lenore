<?php
class Checkout extends AppModel {
	var $name = 'Checkout';
	var $validate = array(
		'name' => VALID_NOT_EMPTY,
		'address' => VALID_NOT_EMPTY,
		'post_code' => VALID_NOT_EMPTY,
		'telephone' => VALID_NOT_EMPTY,
		'email' => VALID_NOT_EMPTY,
		'name_on_card' => VALID_NOT_EMPTY,
		'card_type' => VALID_NOT_EMPTY,
		'card_number' => VALID_NOT_EMPTY,
		'security_code' => VALID_NOT_EMPTY,
		'valid_to' => VALID_NOT_EMPTY
	);
	var $useTable = false;

	var $cardTypes = array('Visa','Mastercard','Switch');

	function schema() {
		return array(
			'name' => array(
				'type' => 'string',
				'length' => 255
				),
			'address' => array(
				'type' => 'string',
				'length' => 1000
				),
			'post_code' => array(
				'type' => 'string',
				'length' => 10
				),
			'telephone' => array(
				'type' => 'string',
				'length' => 50
				),
			'email' => array(
				'type' => 'string',
				'length' => 255
				),
			'name_on_card' => array(
				'type' => 'string',
				'length' => 10
				),
			'card_type' => array(
				'type' => 'string',
				'length' => 10
				),
			'card_number' => array(
				'type' => 'integer',
				'length' => 16
				),
			'security_code' => array(
				'type' => 'integer',
				'length' => 3
				),
			'issue_no' => array(
				'type' => 'string',
				'length' => 3
				),
			'valid_from' => array(
				'type' => 'string',
				'length' => 10
				),
			'valid_to' => array(
				'type' => 'string',
				'length' => 10
				)
		);
	}
	
	
}
?>