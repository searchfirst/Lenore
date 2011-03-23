<?php
class SectionsController extends AppController {
	var $name = 'Sections';

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allowedActions = array('index', 'view');
	}

	function view($slug = null) {
		if(!$slug) {
			//$this->Session->setFlash('Invalid id for Section.');
			$this->redirect('/');
		}
		if (!$this->Lenore->actionIsAdmin()) {
			$this->Section->bindModel(array('hasMany'=>array('Article' => array('conditions' => array('Article.draft'=>false)))));
		}
		if($slug=='news')
			$this->Section->bindModel(array('hasMany'=>array('Article'=>array('className'=>'Article','conditions'=>'draft=0','order'=>'modified DESC'))));
		if($slug=='blog')
			$this->Section->bindModel(array('hasMany'=>array('Article'=>array('className'=>'Article','conditions'=>'draft=0','order'=>'created DESC','limit'=>'15'))));
		$section = $this->Section->findBySlug($slug);
		if(!empty($section)) {
			$this->set('title_for_layout',$section['Section']['title']);
			$this->set('section', $section);
			$this->set('current_page',$slug);
			$this->set('mod_date_for_layout',
				$this->Section->Article->field('modified',array('Article.draft'=>0,'Article.section_id'=>$section['Section']['id']),'Article.modified DESC'));
			if(!empty($section['Section']['meta_description']) || !empty($section['Section']['meta_keywords']))
				$this->set('metadata_for_layout',array('description'=>$section['Section']['meta_description'],'keywords'=>$section['Section']['meta_keywords']));
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}


	function admin_index() {
		$sections = $this->Section->find('all',array(
			'order' => 'Section.order_by ASC',
			'recursive' => 1
		));
		$this->set('sections',$sections);
	}

	function admin_view($id=null,$page=1) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Section.','flash/default',array('class'=>'success'));
			$this->redirect($this->referer('/admin/sections/'));
		} else {
			$this->Section->recursive = 2;
			$section = $this->Section->read(null,$id);
			$this->data = $section;
			if($section) {
				$this->set('section', $section);
				$this->set('id',$id);
				$this->set('inline_media',array(
					'balance' => count($section['Resource']) - $section['Section']['inline_count'],
					'count' => $section['Section']['inline_count']
				));
			} else {
				$this->viewPath = 'errors';
				$this->render('not_found');
			}
		}
	}

	function admin_add() {
		if(!empty($this->data)) {
			if($this->Section->save($this->data)) {
				$this->Session->setFlash("This item has been saved.",'flash/default',array('class'=>'success'));
				$this->redirect(sprintf('/admin/sections/view/%s',$this->Section->id));
			} else {
				$this->Session->setFlash('Please correct errors below.','flash/default',array('class'=>'error'));
			}
		}
	}

	function admin_edit($id=null) {
		if($id!=null) {
			if(!empty($this->data)) {
				if($this->Section->save($this->data)) {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash("This item has been saved.",'flash/default',array('class'=>'success'));
						$this->redirect(sprintf('/admin/sections/view/%s',$this->data['Section']['id']));
					} else {
						$this->generalAjax($this->Section->ajaxFlagArray($id,'success'));
					}
				} else {
					if(!$this->RequestHandler->isAjax()) {
						$this->set('title_for_layout',sprintf('Edit %s: %s',Configure::read('Section.alias'),$this->data['Section']['title']));
						$this->Session->setFlash('Please correct errors below.','flash/default',array('class'=>'error'));
					} else {
						$this->generalAjax(array('status'=>'fail'));
					}
				}
			} else {
				$this->data = $this->Section->read(null, $id);
				$this->set('title_for_layout',sprintf('Edit %s: %s',Configure::read('Section.alias'),$this->data['Section']['title']));
			}
		} else {
				$this->viewPath = 'errors';
				$this->render('not_found');
		}
	}

	function admin_delete($id = null) {
		if(!$id) {
			$this->viewPath = 'errors';
			$this->render('not_found');
		} else {
			$this->set('id',$id);
			if(!empty($this->data['Section']['id'])) {
				if($this->Section->delete($this->data['Section']['id'])) {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash(sprintf('%s deleted',Configure::read('Section.alias')),'flash/default',array('class'=>'success'));
						$this->redirect('/admin/sections/');
					} else {
						$this->generalAjax(array('status'=>'success','model'=>'section','id'=>$this->data['Section']['id']));	
					}
				} else {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash(sprintf('There was an error deleting this %s',Configure::read('Section.alias')),'flash/default',array('class'=>'error'));
						$this->redirect('/admin/sections/');
 					} else {
						$this->generalAjax(array('status'=>'fail'));
					}
				}
			} else {
				$this->data = array('Section'=>array('id'=>$id));
			}
		}
	}

	function admin_moveup() {
		if(isset($this->data['Section']['id']) && isset($this->data['Section']['prev_id'])) {
			if($this->Section->swapFieldData($this->data['Section']['id'],$this->data['Section']['prev_id'],'order_by'))
				$this->redirect($this->referer('/sections/'));
			else { 
				$this->Session->setFlash('There was an error swapping the sections','flash/default',array('class'=>'error'));
				$this->redirect($this->referer('/sections/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid sections. Check you selected the correct section','flash/default',array('class'=>'error'));
			$this->redirect($this->referer('/sections/'));
		}
	}

	function admin_reorder() {
		$ajax_result = true;
		if(!(empty($this->data['Initial'])||empty($this->data['Final']))) {
			$new_ids = $this->data['Final'];
			$current_orders = $this->Section->find('all',array(
				'conditions' => array('Section.id'=>$this->data['Initial']),
				'recursive' => 0,
				'fields' => array('Section.id','Section.order_by'),
				'order' => 'Section.order_by ASC'
			));
			foreach($current_orders as $x=>$co) {
				$section = array('Section'=>array('id'=>$new_ids[$x],'order_by'=>$co['Section']['order_by']));
				if(!$this->Section->save($section)) $ajax_result = $ajax_result && false;
			}
		} else {
			$ajax_result = $ajax_result && false;
		}
		$this->set('ajax_result',$ajax_result?'Success':'Fail');	
	}

}
