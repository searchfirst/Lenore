<?php
class HelperComponent extends Object {
	var $controller;

	function startup(&$controller) {
		$this->controller = $controller;
		if(isset($controller->actionHelpers)) {
			$this->pushHelpers();
		}
	}

	function pushHelpers() {
		foreach($this->controller->actionHelpers as $helper) {
			$_helper = ucfirst($helper);
			App::Import('Helper',$_helper);
			$_helperClassName = $helper.'Helper';
			$this->controller->{$helper} = new $_helperClassName();
		}
	}
}
?>