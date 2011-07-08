<?php
class CacherBehavior extends ModelBehavior {
	function setup(&$model,$settings) {
		$this->settings['registered_caches'] = array();
		if(!empty($settings['register_caches']) && is_array($settings['register_caches']))
			$this->settings['registered_caches'] = array_merge(
				$this->settings['registered_caches'],
				$settings['register_caches']
			);
	}
	function afterSave(&$model,$created) {
		$this->deleteRegisteredCaches();
	}
	function afterDelete(&$model) {
		$this->deleteRegisteredCaches();
	}
	function deleteRegisteredCaches(&$model) {
		if(!empty($this->settings['registered_caches']) && is_array($this->settings['registered_caches']))
			foreach($this->settings['registered_caches'] as $cache)
				Cache::delete($cache,'lenore');
	}
}
