<?php
class ArticlesController extends AppController {
	var $name = 'Articles';
	var $uses = array('Article');

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allowedActions = array('index', 'view');
	}

	function index() {
		if( isset($this->params['alt_content']) && ($this->params['alt_content']=='Rss') &&
		($articles = $this->Article->findAll('Article.draft=0',null,'Article.modified DESC',20)) &&
		(!empty($articles)) ) {
			$this->set('articles',$articles);
		} else {		
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}

	function view($slug = null) {
		$slug = $this->params['slug'];
		if(!$slug || (isset($this->params['alt_content']) && $this->params['alt_content']=='Rss')) {
			$this->viewPath = 'errors';
			$this->render('not_found');
			return true;
		}
		$article = $this->Article->find(array('Article.draft'=>0,'Article.slug'=>$slug));
		if(!empty($article)) {
			if($article['Section']['slug']!=$this->params['section']) {
				$this->redirect("/{$article['Section']['slug']}/{$article['Article']['slug']}",301);
				return true;
			}
			$this->set('title_for_layout',$article['Article']['title']." | ".$article['Section']['title']);
			$this->set('article', $article);
			$this->set('breadcrumb',array(
				''=>'Home',$article['Section']['slug']=>$article['Section']['title']
			));
			$this->set('mod_date_for_layout', $article['Article']['modified']);
			if(!empty($article['Article']['meta_description']) || !empty($article['Article']['meta_keywords']))
				$this->set('metadata_for_layout',array('description'=>$article['Article']['meta_description'],'keywords'=>$article['Article']['meta_keywords']));
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}

	function admin_index() {
		$this->viewPath = 'errors';
		$this->render('not_found');
	}

	function admin_view($id = null) {
		if(!$id) {
			$this->redirect($this->referer('/admin/sections/'));
		} else {
			$this->Article->recursive = 2;
			$article = $this->Article->read(null,$id);
			$this->data = $article;
			if($article) {
				$this->set('article',$article);
				$this->set('inline_media',array(
					'balance' => count($article['Resource']) - $article['Article']['inline_count'],
					'count' => $article['Article']['inline_count']
				));
			} else {
				$this->viewPath = 'errors';
				$this->render('not_found');
			}
		}
	}

	function admin_add() {
		if (!$this->RequestHandler->isPost()) {
			$this->set('sections', $this->Article->Section->find('list',array('conditions'=>array('articles_enabled'=>1))));
		} else {
			if($this->Article->save($this->data)) {
				$last_id = $this->Article->getLastInsertId();
				$this->Session->setFlash("This item has been saved.",'flash/default',array('class'=>'success'));
				$this->redirect("/admin/articles/view/$last_id");
			} else {
				$this->Session->setFlash('Please correct the errors.','flash/default',array('class'=>'error'));
				$this->set('sections', $this->Article->Section->find('list',array('conditions'=>array('articles_enabled'=>1))));
			}
		}
	}

	function admin_edit($id=null) {
		if($id!=null) {
			if(!empty($this->data)) {
				if($this->Article->save($this->data)) {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash("This item has been saved.",'flash/default',array('class'=>'success'));
						$this->redirect(sprintf('/admin/articles/view/%s',$this->data['Article']['id']));
					} else {
						$this->generalAjax($this->Article->ajaxFlagArray($id,'success'));
					}
				} else {
					if(!$this->RequestHandler->isAjax()) {
						$this->set('title_for_layout',sprintf('Edit %s: %s',Configure::read('Article.alias'),$this->data['Article']['title']));
						$this->set('sections',$this->Article->Section->find('list'));
						$this->Session->setFlash('Please correct errors below.','flash/default',array('class'=>'error'));
					} else {
						$this->generalAjax(array('status'=>'fail'));
					}
				}
			} else {
				$this->data = $this->Article->read(null, $id);
				$this->set('title_for_layout',sprintf('Edit %s: %s',Configure::read('Article.alias'),$this->data['Article']['title']));
				$this->set('sections',$this->Article->Section->find('list'));
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
			if(!empty($this->data['Article']['id'])) {
				if($this->Article->delete($this->data['Article']['id'])) {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash(sprintf('%s deleted',Configure::read('Article.alias')),'flash/default',array('class'=>'success'));
						$this->redirect('/admin/sections/');
					} else {
						$this->generalAjax(array('status'=>'success','model'=>'article','id'=>$this->data['Article']['id']));	
					}
				} else {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash(sprintf('There was an error deleting this %s',Configure::read('Article.alias')),'flash/default',array('class'=>'error'));
						$this->redirect('/admin/sections/');
 					} else {
						$this->generalAjax(array('status'=>'fail'));
					}
				}
			} else {
				$this->data = array('Article'=>array('id'=>$id));
			}
		}
	}

	function admin_moveup() {
		if(isset($this->data['Article']['id']) && isset($this->data['Article']['prev_id'])) {
			if($this->Article->swapFieldData($this->data['Article']['id'],$this->data['Article']['prev_id'],'order_by'))
				$this->redirect($this->referer('/sections/'));
			else { 
				$this->Session->setFlash('There was an error swapping the articles','flash/default',array('class'=>'error'));
				$this->redirect($this->referer('/sections/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid articles. Check you selected the correct article','flash/default',array('class'=>'error'));
			$this->redirect($this->referer('/sections/'));
		}
	}

	function admin_reorder() {
		$ajax_result = true;
		if(!(empty($this->data['Initial'])||empty($this->data['Final']))) {
			$new_ids = $this->data['Final'];
			$current_orders = $this->Article->find('all',array(
				'conditions' => array('Article.id'=>$this->data['Initial']),
				'recursive' => 0,
				'fields' => array('Article.id','Article.order_by'),
				'order' => 'Article.order_by ASC'
			));
			foreach($current_orders as $x=>$co) {
				$article = array('Article'=>array('id'=>$new_ids[$x],'order_by'=>$co['Article']['order_by']));
				if(!$this->Article->save($article)) $ajax_result = $ajax_result && false;
			}
		} else {
			$ajax_result = $ajax_result && false;
		}
		$this->set('ajax_result',$ajax_result?'Success':'Fail');	
	}
}
