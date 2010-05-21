<?php
class ProtectedItemsController extends AppController {

	var $name = 'ProtectedItems';
	
	function index() {
		$this->viewPath = 'errors';
		$this->render('not_found');
	}
	
	function view($slug = null) {
		if(!$slug || (isset($this->params['alt_content']) && $this->params['alt_content']=='Rss')) {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
		$article = $this->Article->find(array('Article.draft'=>0,'Article.slug'=>$slug));
		if(!empty($article)) {
			$this->pageTitle= $article['Article']['title']." – ".$article['Section']['title'];
			$this->set('article', $article);
			$this->set('mod_date_for_layout', $article['Article']['modified']);
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}


	function admin_index() {
		$this->ProtectedItem->recursive = 0;
		$this->set('protectedItems', $this->ProtectedItem->findAll());
	}

	function admin_view($id) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Article.');
			$this->redirect($this->referer('/protected_sections/'));
		}
		$this->set('protectedItem', $this->ProtectedItem->read(null, $id));
	}

	function admin_add() {
		if(empty($this->data) or isset($this->data['Referrer']['protected_section_id']) ) {
			$this->data['ProtectedItem']['protected_section_id'] = $this->data['Referrer']['protected_section_id'];
			$this->set('protectedSections', $this->ProtectedItem->ProtectedSection->generateList());
		} else {
			$this->cleanUpFields();
			if($this->ProtectedItem->save($this->data)) {
				if(isset($GLOBALS['moonlight_inline_count_set'])) {
					$this->Session->setFlash("This item has been saved. You need to upload media for this item");
					$this->redirect('/'.Inflector::underscore($this->name).'/manageinline/'.$this->ProtectedItem->getLastInsertId());
				} else {
					$this->Session->setFlash("This item has been saved.");
					$this->redirect('/'.Inflector::underscore($this->name).'/view/'.$this->ProtectedItem->getLastInsertId());
				}
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('protectedSections', $this->ProtectedItem->ProtectedSection->generateList());
			}
		}
	}

	function admin_edit($id = null) {
		if( (isset($this->data['ProtectedItem']['submit'])) || (empty($this->data)) ) {
			if(!$id) {
				$this->Session->setFlash('Invalid id for Protected Item');
				$this->redirect('/protected_items/');
			}
			$this->data = $this->ProtectedItem->read(null, $id);
			$this->set('protectedItem', $this->data);
			$this->set('protectedSections', $this->ProtectedItem->ProtectedSection->generateList());
		} else {
			$this->cleanUpFields();
			if($this->ProtectedItem->save($this->data)) {
				if(isset($GLOBALS['moonlight_inline_count_set'])) {
					$this->Session->setFlash("This item has been saved. You may need to upload media for this item");
					$this->redirect("/".Inflector::underscore($this->name)."/manageinline/$id");
				} else {
					$this->Session->setFlash("This item has been saved.");
					$this->redirect("/".Inflector::underscore($this->name)."/view/$id");
				}
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('article', $this->ProtectedItem->read(null, $id));
				$this->set('sections', $this->ProtectedItem->ProtectedSection->generateList());
			}
		}
	}

	function admin_delete($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Protected Item');
			$this->redirect($this->referer('/protected_sections/'));
		}
		if( ($this->data['ProtectedItem']['id']==$id) && ($this->ProtectedItem->del($id)) ) {
			$this->Session->setFlash('Item successfully deleted');
			$this->redirect($this->referer('/protected_sections/'));
		} else {
			$this->set('id',$id);
		}
	}
	
	function admin_moveup() {
		if(isset($this->data['ProtectedItem']['id']) && isset($this->data['ProtectedItem']['prev_id'])) {
			if($this->ProtectedItem->swapFieldData($this->data['ProtectedItem']['id'],$this->data['ProtectedItem']['prev_id'],'order_by'))
				$this->redirect($this->referer('/protected_sections/'));
			else { 
				$this->Session->setFlash('There was an error swapping the items');
				$this->redirect($this->referer('/protected_sections/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid articles. Check you selected the correct article');
			$this->redirect($this->referer('/protected_sections/'));
		}
	}
	
	function admin_manageinline($id=null) {
		if($id && ($this->data = $this->ProtectedItem->read(null, $id))) {
			$this->set('protectedItem',$this->data);
			$this->set('media_data',$this->data['Resource']);
			$db_inline_count = (int) $this->data[$this->modelClass]['inline_count'];
			$actual_inline_count = count($this->data['Resource']);
			if(preg_match('/'.Inflector::underscore($this->name)."\\/(add|edit)/",$this->referer()) && ($db_inline_count == $actual_inline_count))
				$this->redirect('/'.Inflector::underscore($this->name).'/view/'.$id);
			$this->set('inline_data', array('db_count'=>$db_inline_count,'actual_count'=>$actual_inline_count));
			if(!isset($this->data['Resource'])) {
				$this->Session->setFlash('No inline media in '.$this->modelClass);
				$this->redirect('/'.Inflector::underscore($this->name).'/view/'.$id);
			}
		} else {
			$this->Session->setFlash('Invalid '.$this->modelClass.'.');
			$this->redirect('/'.Inflector::underscore($this->name).'/');
		}
	}



}
?>