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
			$this->set('title_for_layout',$article['Article']['title']." – ".$article['Section']['title']);
			$this->set('article', $article);
			$this->set('current_page',$article['Section']['slug']);
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
			$article = $this->Article->find('first',array('conditions' => array('Article.id'=>$id),'recursive'=>2));
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
		if(empty($this->data)) {
			$this->retrieveGetIdsToData();
			$this->set('sections', $this->Article->Section->find('list',array('conditions'=>array('articles_enabled'=>1))));
		} else {
			if($this->Article->save($this->data)) {
				$last_id = $this->Article->getLastInsertId();
				if(isset($GLOBALS['moonlight_inline_count_set'])) {
					$this->Session->setFlash("This item has been saved. You need to upload media for this item");
					$this->redirect("/admin/articles/manageinline/$last_id");
				} else {
					$this->Session->setFlash("This item has been saved.");
					$this->redirect("/admin/articles/view/$last_id");
				}
			} else {
				$this->Session->setFlash('Please correct the errors.');
				$this->set('sections', $this->Article->Section->find('list',array('conditions'=>array('articles_enabled'=>1))));
			}
		}
	}

	function admin_edit($id=null) {
		if($id!=null) {
			if(!empty($this->data)) {
				if($this->Article->save($this->data)) {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash("This item has been saved.");
						$this->redirect(sprintf('/admin/articles/view/%s',$this->data['Article']['id']));
					} else {
						$this->set('json_object',array('status'=>'success'));
						$this->generalAjax();
					}
				} else {
					if(!$this->RequestHandler->isAjax()) {
						$this->set('title_for_layout',sprintf('Edit %s: %s',Configure::read('Article.alias'),$this->data['Article']['title']));
						$this->set('sections',$this->Article->Section->find('list'));
						$this->Session->setFlash('Please correct errors below.');
					} else {
						$this->set('json_object',array('status'=>'fail'));
						$this->generalAjax();
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
		}
		if(!empty($this->data['Article']['id']) && ($this->data['Article']['id']==$id) && ($this->Article->del($id)) ) {
			$this->Session->setFlash('Article successfully deleted');
			$this->redirect($this->referer('/admin/'));
		} else {
			$this->set('id',$id);
		}
	}
	
	function admin_moveup() {
		if(isset($this->data['Article']['id']) && isset($this->data['Article']['prev_id'])) {
			if($this->Article->swapFieldData($this->data['Article']['id'],$this->data['Article']['prev_id'],'order_by'))
				$this->redirect($this->referer('/sections/'));
			else { 
				$this->Session->setFlash('There was an error swapping the articles');
				$this->redirect($this->referer('/sections/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid articles. Check you selected the correct article');
			$this->redirect($this->referer('/sections/'));
		}
	}
	
	function admin_manageinline($id=null) {
		if($id && ($this->data = $this->Article->read(null, $id))) {
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
?>