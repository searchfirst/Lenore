<?php
class ResourcesController extends AppController
{
	var $name = 'Resources';
	var $helpers = array('Javascript','Html', 'Form');

	function beforeValidate() {
		$this->sanitiseData();
		return true;
	}
	
	function admin_delete($id) {
		if(!$id) {
			$this->Session->setFlash('Invalid Media');
			$this->redirect($this->referer('/'));
		}
		if($this->data['Resource']['id']==$id && $this->Resource->delete($id)) {
			$this->Session->setFlash('Media deleted');
			$this->redirect($this->referer('/'));
		} else {
			$this->set('id',$id);
		}
	}

	function admin_moveup() {
		if(isset($this->data['Resource']['id']) && isset($this->data['Resource']['prev_id'])) {
			if($this->Resource->swapFieldData($this->data['Resource']['id'],$this->data['Resource']['prev_id'],'order_by'))
				$this->redirect($this->referer('/'));
			else { 
				$this->Session->setFlash('There was an error swapping the media');
				$this->redirect($this->referer('/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid media. Check you selected the correct media');
			$this->redirect($this->referer('/'));
		}
	}

	function admin_reorder() {
		$ajax_result = true;
		if(!(empty($this->data['Initial'])||empty($this->data['Final']))) {
			$new_ids = $this->data['Final'];
			$current_orders = $this->Resource->find('all',array(
				'conditions' => array('Resource.id'=>$this->data['Initial']),
				'recursive' => 0,
				'fields' => array('Resource.id','Resource.order_by'),
				'order' => 'Resource.order_by ASC'
			));
			foreach($current_orders as $x=>$co) {
				$resource = array('Resource'=>array('id'=>$new_ids[$x],'order_by'=>$co['Resource']['order_by']));
				if(!$this->Resource->save($resource)) $ajax_result = $ajax_result && false;
			}
		} else {
			$ajax_result = $ajax_result && false;
		}
		$this->set('ajax_result',$ajax_result?'Success':'Fail');	
	}
}
