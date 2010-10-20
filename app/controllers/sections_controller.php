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
			$this->Session->setFlash('Invalid id for Section.');
			$this->redirect($this->referer('/admin/sections/'));
		} else {
			$section = $this->Section->find('first', array('conditions'=>array('Section.id'=>$id),'recursive'=>2));
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
				$this->Session->setFlash("This item has been saved.");
				$this->redirect(sprintf('/admin/sections/view/%s',$this->Section->id));
			} else {
				$this->Session->setFlash('Please correct errors below.');
			}
		}
	}

	function admin_edit($id=null) {
		if($id!=null) {
			if(!empty($this->data)) {
				if($this->Section->save($this->data)) {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash("This item has been saved.");
						$this->redirect(sprintf('/admin/sections/view/%s',$this->data['Section']['id']));
					} else {
						$this->set('json_object',array('status'=>'success'));
						$this->generalAjax();
					}
				} else {
					if(!$this->RequestHandler->isAjax()) {
						$this->set('title_for_layout',sprintf('Edit %s: %s',Configure::read('Section.alias'),$this->data['Section']['title']));
						$this->Session->setFlash('Please correct errors below.');
					} else {
						$this->set('json_object',array('status'=>'fail'));
						$this->generalAjax();
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
		$this->set('id',$id);
		if(!$id) {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
		if(!empty($this->data['Section']['id'])) {
			if($this->Section->delete($this->data['Section']['id'])) {
				$this->Session->setFlash(sprintf('%s deleted',Configure::read('Section.alias')));
			} else {
				$this->Session->setFlash(sprintf('There was an error deleting this %s',Configure::read('Section.alias')));
			}
			$this->redirect('/admin/sections/');			
		}
	}

	function admin_moveup() {
		if(isset($this->data['Section']['id']) && isset($this->data['Section']['prev_id'])) {
			if($this->Section->swapFieldData($this->data['Section']['id'],$this->data['Section']['prev_id'],'order_by'))
				$this->redirect($this->referer('/sections/'));
			else { 
				$this->Session->setFlash('There was an error swapping the sections');
				$this->redirect($this->referer('/sections/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid sections. Check you selected the correct section');
			$this->redirect($this->referer('/sections/'));
		}
	}
	
	function admin_manageinline($id=null) {
		if($id && ($this->data = $this->Section->read(null, $id))) {
			$this->set(strtolower($this->modelClass),$this->data);
			$this->set('media_data',$this->data['Resource']);
			$db_inline_count = (int) $this->data[$this->modelClass]['inline_count'];
			$actual_inline_count = count($this->data['Resource']);
			if(preg_match('/'.strtolower($this->name)."\\/(add|edit)/",$this->referer()) && ($db_inline_count == $actual_inline_count))
				$this->redirect('/'.strtolower($this->name).'/view/'.$id);
			$this->set('inline_data', array('db_count'=>$db_inline_count,'actual_count'=>$actual_inline_count));
			if(!isset($this->data['Resource'])) {
				$this->Session->setFlash('No inline media in '.$this->modelClass);
				$this->redirect('/'.strtolower($this->name).'/view/'.$id);
			}
		} else {
			$this->Session->setFlash('Invalid '.$this->modelClass.'.');
			$this->redirect('/'.strtolower($this->name).'/');
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
?>