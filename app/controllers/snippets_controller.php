<?php
class SnippetsController extends AppController {
	var $name = 'Snippets';

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allowedActions = array();
	}

	function admin_index() {
		$this->set('snippets',$this->Snippet->find('all'));
	}

	function admin_view($id=null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Snippet.','flash/default',array('class'=>'success'));
			$this->redirect($this->referer('/admin/snippets/'));
		} else {
			$this->Snippet->recursive = 2;
			$snippet = $this->Snippet->read(null,$id);
			$this->data = $snippet;
			if($snippet) {
				$this->set('snippet', $snippet);
				$this->set('id',$id);
			} else {
				$this->viewPath = 'errors';
				$this->render('not_found');
			}
		}
	}

	function admin_add() {
		$this->view = 'Theme';
		if(!empty($this->data)) {
			if($this->Snippet->save($this->data)) {
				$this->Session->setFlash("This item has been saved.",'flash/default',array('class'=>'success'));
				$this->redirect(sprintf('/admin/snippets/view/%s',$this->Snippet->id));
			} else {
				$this->Session->setFlash('Please correct errors below.','flash/default',array('class'=>'error'));
			}
		}
	}

	function admin_edit($id=null) {
		$this->view = 'Theme';
		if($id!=null) {
			if(!empty($this->data)) {
				if($this->Snippet->save($this->data)) {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash("This item has been saved.",'flash/default',array('class'=>'success'));
						$this->redirect(sprintf('/admin/snippets/view/%s',$this->data['Snippet']['id']));
					} else {
						$this->generalAjax($this->Snippet->ajaxFlagArray($id,'success'));
					}
				} else {
					if(!$this->RequestHandler->isAjax()) {
						$this->set('title_for_layout',sprintf('Edit Snippet: %s',$this->data['Snippet']['title']));
						$this->Session->setFlash('Please correct errors below.','flash/default',array('class'=>'error'));
					} else {
						$this->generalAjax(array('status'=>'fail'));
					}
				}
			} else {
				$this->data = $this->Snippet->read(null, $id);
				$this->set('title_for_layout',sprintf('Edit Snippet: %s',$this->data['Snippet']['title']));
			}
		} else {
				$this->viewPath = 'errors';
				$this->render('not_found');
		}
	}

	function admin_delete($id=null) {
		if(!$id) {
			$this->viewPath = 'errors';
			$this->render('not_found');
		} else {
			$this->set('id',$id);
			if(!empty($this->data['Snippet']['id'])) {
				if($this->Snippet->delete($this->data['Snippet']['id'])) {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash('Snippet deleted','flash/default',array('class'=>'success'));
						$this->redirect('/admin/snippets/');
					} else {
						$this->generalAjax(array('status'=>'success','model'=>'snippet','id'=>$this->data['Snippet']['id']));
					}
				} else {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash('There was an error deleting this Snippet','flash/default',array('class'=>'error'));
						$this->redirect('/admin/snippet/');
 					} else {
						$this->generalAjax(array('status'=>'fail'));
					}
				}
			} else {
				$this->data = array('Snippet'=>array('id'=>$id));
			}
		}
	}
}
