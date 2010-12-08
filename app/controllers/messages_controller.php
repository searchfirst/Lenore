<?php
class MessagesController extends AppController {

	var $name = 'Messages';
	var $uses = array('Message','Section');
	var $components = array('Notify');
	var $paginate = array(
		'limit' => 5,
		'page' => 1,
		'order' => array('Message.created' => 'desc')
	);

	function beforeFilter() {
		parent::beforeFilter();
		if(empty($this->params['named']['page'])) $this->paginate['page'] = 1;
	}

	function index($type='contact-us') {
		$title = Inflector::humanize(str_replace('-','_',$type));
		if($this->Message->isValidType($type)) {
			$this->set('title_for_layout',$title);
			if(!empty($section['Section']['meta_description']) || !empty($section['Section']['meta_keywords'])) {
				$this->set('metadata_for_layout',array(
					'description'=>$section['Section']['meta_description'],'keywords'=>$section['Section']['meta_keywords']
				));
			}
			$section = $this->Section->find('first',array('conditions'=>array('Section.slug'=>$type)));
			$this->set('section',$section);
			if(!empty($this->data['Message'])) {
				$this->Message->getOptions($this->data['Message'],$type);
				if($this->Message->save($this->data)) {
					$this->Notify->send($this->data['Message']);
					$this->Session->setFlash(__('Your message has been sent',true),'flash/default',array('class'=>'success'));
					$this->redirect($this->referer('/'));
				} else {$this->Session->setFlash(__('There was an error saving your message. Check for errors and try again',true),'flash/default',array('class'=>'error'));}
			}
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}

	function admin_index() {
		if($this->RequestHandler->isAjax()) {
			$this->generalAjax($this->restfulPaginate('Message'));
		} else {
			$this->set('messages',$this->paginate('Message'));
		}
	}

	function admin_view($id=null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Message.','flash/default',array('class'=>'success'));
			$this->redirect($this->referer('/admin/messages/'));
		} else {
			$this->Message->recursive = 2;
			$message = $this->Message->read(null,$id);
			$this->data = $message;
			if($message) {
				$this->set('message', $message);
				$this->set('id',$id);
			} else {
				$this->viewPath = 'errors';
				$this->render('not_found');
			}
		}
	}

	function admin_delete($id=null) {
		if(!$id) {
			$this->viewPath = 'errors';
			$this->render('not_found');
		} else {
			$this->set('id',$id);
			if(!empty($this->data['Message']['id'])) {
				if($this->Message->delete($this->data['Message']['id'])) {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash('Message deleted','flash/default',array('class'=>'success'));
						$this->redirect($this->referer('/admin/messages/'));
					} else {
						$this->generalAjax(array('status'=>'success','model'=>'message','id'=>$this->data['Message']['id']));
					}
				} else {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash('There was an error deleting this Message','flash/default',array('class'=>'error'));
						$this->redirect('/admin/messages/');
 					} else {
						$this->generalAjax(array('status'=>'fail'));
					}
				}
			} else {
				$this->data = array('Message'=>array('id'=>$id));
			}
		}
	}
}