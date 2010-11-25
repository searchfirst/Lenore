<?php
class NotifyComponent extends Object {
	var $components = array('Email');
	var $controller;
	var $settings;

	function startup(&$controller) {
		if(empty($controller->plugin)) $this->controller = $controller;
		$this->settings = ($settings = Configure::read('Message.Notify'))?$settings:array();
	}
	
	function send($options=array()) {
		/**
		 * Needed options keys:
		 *	content
		 * Optional:
		 *	additional_parameters
		 */
		if(!empty($options)) {
			$default_options = array(
				'to' => $this->settings['to'],
				'subject' => '[No Subject]',
				'template' => 'notify',
				'send_as' => 'text'
			);
			$options = array_merge($default_options,$options);
			$this->Email->to = $options['to'];
			$this->Email->subject = $options['subject'];
			$this->Email->from = $options['email'];
			$this->Email->template = $options['template'];
			$this->controller->set('email',$options);
			return $this->Email->send();
		} else {trigger_error('No data array passed to Notify::send()');}
	}
}