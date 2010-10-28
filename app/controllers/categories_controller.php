<?php
class CategoriesController extends AppController {

	var $name = 'Categories';

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allowedActions = array('index', 'view');
	}

	function index() {
		if(isset($this->params['alt_content']) && $this->params['alt_content']=='Rss') {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
		$this->set('title_for_layout',Configure::read('Category.alias'));
		$this->set('categories',$this->Category->findAll());
	}
	
	function view($slug = null) {
		if(!$slug) {
			$this->Session->setFlash('Invalid id for Category.');
			$this->render('error');}
		$category = $this->Category->find('first',array(
			'conditions'=>array('Category.slug'=>$slug),
			'recursive'=>2
		));
		if(!empty($category)) {
			$this->set('title_for_layout',$category['Category']['title']);
			$this->set('current_page',$slug);
			$this->set('current_parent_section','category-'.$category['Category']['slug']);
			$this->set('category', $category);
			$this->set('mod_date_for_layout', $this->Category->Product->field('modified',"Product.draft=0 AND Product.category_id={$category['Category']['id']}",'Product.modified DESC'));
			if(!empty($category['Category']['meta_description']) || !empty($category['Category']['meta_keywords']))
				$this->set('metadata_for_layout',array('description'=>$category['Category']['meta_description'],'keywords'=>$category['Category']['meta_keywords']));
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}


	function admin_index() {
		$this->set('categories', $this->Category->find('all',array('conditions'=>array('Category.category_id'=>null),'order'=>'Category.order_by ASC','recursive'=>1)));
	}

	function admin_view($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid Category.');
			$this->redirect('/admin/categories/');
		} else {
			$this->Category->recursive = 2;
			$category = $this->Category->read(null,$id);
			$this->data = $category;
			if($category) {
				$this->set('category', $category);
				$this->set('inline_media',array(
					'balance' => count($category['Resource']) - $category['Category']['inline_count'],
					'count' => $category['Category']['inline_count']
				));
			} else {
				$this->viewPath = 'errors';
				$this->render('not_found');
			}
		}
	}

	function admin_add() {
		$this->set('categories',$this->Category->find('list',array(
			'conditions' => array('Category.category_id'=>null),
			'order' => 'Category.title'
		)));
		if(!empty($this->data)) {
			if($this->Category->save($this->data)) {
				if(isset($GLOBALS['moonlight_inline_count_set'])) {
					$this->Session->setFlash("This item has been saved. You need to upload media for this item");
					$this->redirect('/'.strtolower($this->name).'/manageinline/'.$this->Category->getLastInsertId());
				} else {
					$this->Session->setFlash("This item has been saved.");
					$this->redirect(sprintf("/admin/%s/view/%s",'categories',$this->Category->getLastInsertId()));
				}
			} else {
				$this->Session->setFlash('Please correct errors below.');
			}
		}
	}

	function admin_edit($id = null) {
		if($id!=null) {
			if(!empty($this->data)) {
				if($this->Category->save($this->data)) {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash("This item has been saved.");
						$this->redirect(sprintf('/admin/categories/view/%s',$this->data['Category']['id']));
					} else {
						$this->generalAjax($this->Category->ajaxFlagArray($id,'success'));
					}
				} else {
					if(!$this->RequestHandler->isAjax()) {
						$this->set('title_for_layout',sprintf('Edit %s: %s',Configure::read('Category.alias'),$this->data['Category']['title']));
						$this->set('categories', $this->Category->find('list',array(
							'conditions'=>array('Category.category_id'=>null,'NOT'=>array('Category.id'=>$id)),
							'order'=>'Category.title ASC',
							'recursive'=>0
						)));
						$this->Session->setFlash('Please correct errors below.');
					} else {
						$this->generalAjax(array('status'=>'fail'));
					}
				}
			} else {
				$this->data = $this->Category->read(null, $id);
				$this->set('title_for_layout',sprintf('Edit %s: %s',Configure::read('Category.alias'),$this->data['Category']['title']));
				$this->set('categories', $this->Category->find('list',array(
					'conditions'=>array('Category.category_id'=>null,'NOT'=>array('Category.id'=>$id)),
					'order'=>'Category.title ASC',
					'recursive'=>0
				)));
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
			if(!empty($this->data['Category']['id'])) {
				if($this->Category->delete($this->data['Category']['id'])) {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash(sprintf('%s deleted',Configure::read('Category.alias')));
						$this->redirect('/admin/categories/');
					} else {
						$this->generalAjax(array('status'=>'success','model'=>'category','id'=>$this->data['Category']['id']));	
					}
				} else {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash(sprintf('There was an error deleting this %s',Configure::read('Category.alias')));
						$this->redirect('/admin/categories/');
 					} else {
						$this->generalAjax(array('status'=>'fail'));
					}
				}
			}
		}
	}

	function admin_moveup() {
		if(isset($this->data['Category']['id']) && isset($this->data['Category']['prev_id'])) {
			if($this->Category->swapFieldData($this->data['Category']['id'],$this->data['Category']['prev_id'],'order_by'))
				$this->redirect($this->referer('/categories/'));
			else { 
				$this->Session->setFlash('There was an error swapping the categories');
				$this->redirect($this->referer('/categories/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid categories. Check you selected the correct category');
			$this->redirect($this->referer('/categories/'));
		}
	}

	function admin_reorder() {
		$ajax_result = true;
		if(!(empty($this->data['Initial'])||empty($this->data['Final']))) {
			$new_ids = $this->data['Final'];
			$current_orders = $this->Category->find('all',array(
				'conditions' => array('Category.id'=>$this->data['Initial']),
				'recursive' => 0,
				'fields' => array('Category.id','Category.order_by'),
				'order' => 'Category.order_by ASC'
			));
			foreach($current_orders as $x=>$co) {
				$category = array('Category'=>array('id'=>$new_ids[$x],'order_by'=>$co['Category']['order_by']));
				if(!$this->Category->save($category)) $ajax_result = $ajax_result && false;
			}
		} else {
			$ajax_result = $ajax_result && false;
		}
		$this->set('ajax_result',$ajax_result?'Success':'Fail');	
	}


}
?>