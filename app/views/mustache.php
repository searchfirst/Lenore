<?php
App::import('Core','Theme');
class MustacheView extends ThemeView {
	function __construct($controller,$register=true) {
		parent::__construct($controller,$register);
		App::import('Vendor','mustache');
	}

	function render($action=null,$layout=null,$file=null) {
		$m = new Mustache;
		return $m->render(parent::render($action,$layout,$file),$this->viewVars);
	}
}