<?php
class Snippet extends AppModel {
    var $name = 'Snippet';
    var $validate = array(
		'title' => array('rule'=>'notEmpty','message'=>"Snippets need titles.")
	);

	function afterSave($created) {
		parent::afterSave($created);
		$this->clearViewCache();
	}

	function afterDelete() {
		parent::afterDelete();
		$this->clearViewCache();
	}

	function clearViewCache() {
		Cache::delete('snippets_f','lenore');
	}
}
