<?php
class CategoriesController extends AppController {

	var $name = 'Categories';

/*	function beforeRender() {
		AppController::beforeRender();
		if(!empty($this->viewVars['category']['Category']))
			$this->set('subcategory_list', $this->Category->findAll("Category.category_id={$this->viewVars['category']['Category']['id']}"));
	}
*/	
	function index() {
		if(isset($this->params['alt_content']) && $this->params['alt_content']=='Rss') {
			$this->viewPath = 'errors';
			$this->render('not_found');}
		$this->pageTitle = MOONLIGHT_CATEGORIES_TITLE;
		$this->set('categories',$this->Category->findAll());
	}
	
	function view($slug = null) {
		if(!$slug) {
			$this->Session->setFlash('Invalid id for Category.');
			$this->render('error');}
		$get_category_from_db = $this->Category->findBySlug($slug);
		if(!empty($get_category_from_db)) {
			$this->pageTitle = $get_category_from_db['Category']['title'];
			$this->set('current_page',$slug);
			$this->set('current_parent_section','category-'.$get_category_from_db['Category']['slug']);
			$this->set('category', $get_category_from_db);
			$this->set('mod_date_for_layout', $this->Category->Product->field('modified',"Product.draft=0 AND Product.category_id={$get_category_from_db['Category']['id']}",'Product.modified DESC'));
			if(!empty($get_category_from_db['Category']['meta_description']) || !empty($get_category_from_db['Category']['meta_keywords']))
				$this->set('metadata_for_layout',array('description'=>$get_category_from_db['Category']['meta_description'],'keywords'=>$get_category_from_db['Category']['meta_keywords']));
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}


	function admin_index() {
		$this->Category->recursive = 0;
		$this->set('categories', $this->Category->findAll("Category.category_id IS NULL OR Category.category_id = 0",null,"Category.order_by ASC",null,null,2));
	}

	function admin_view($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid Category.');
			$this->redirect('/categories/');
		}
		$this->set('category', $this->Category->find(array('Category.id'=>$id),null,'Category.id ASC'));
	}

	function admin_add() {
		$this->set('category_list', $this->Category->generateList('Category.category_id IS NULL or Category.category_id = 0'));
		if(empty($this->data)) {
			$this->set('parents', $this->Category->Parent->generateList());
		} else {
			$this->cleanUpFields();
			if($this->Category->save($this->data)) {
				if(isset($GLOBALS['moonlight_inline_count_set'])) {
					$this->Session->setFlash("This item has been saved. You need to upload media for this item");
					$this->redirect('/'.strtolower($this->name).'/manageinline/'.$this->Category->getLastInsertId());
				} else {
					$this->Session->setFlash("This item has been saved.");
					$this->redirect('/'.strtolower($this->name).'/view/'.$this->Category->getLastInsertId());
				}
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('parents', $this->Category->Parent->generateList());
			}
		}
	}

	function admin_edit($id = null) {
		$this->set('category_list',
			$this->Category->generateList(array("OR"=>array('Category.category_id'=>'IS NULL','Category.category_id'=>0)),null,null,'{n}.Category.id','{n}.Category.title'));
		if( (isset($this->data['Category']['submit'])) || (empty($this->data)) ) {
			if(!$id) {
				$this->Session->setFlash('Invalid Category');
				$this->redirect('/categories/');
			}
			$this->data = $this->Category->find(array('Category.id'=>$id),null,'Category.id ASC');
			$this->set('category', $this->data);
			$this->set('parents', $this->Category->Parent->generateList());
		} else {
			$this->cleanUpFields();
			if($this->Category->save($this->data)) {
				if(isset($GLOBALS['moonlight_inline_count_set'])) {
					$this->Session->setFlash("This item has been saved. You may need to upload media for this item");
					$this->redirect("/".strtolower($this->name)."/manageinline/$id");
				} else {
					$this->Session->setFlash("This item has been saved.");
					$this->redirect("/".strtolower($this->name)."/view/$id");
				}
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('category', $this->Category->read(null, $id));
				$this->set('parents', $this->Category->Parent->generateList());
			}
		}
	}

	function admin_delete($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Category');
			$this->redirect('/categories/');
		}
		if( ($this->data['Category']['id']==$id) && ($this->Category->del($id)) ) {
			$this->Session->setFlash('Category successfully deleted');
			$this->redirect('/categories/');
		} else {
			$this->set('id',$id);
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
	
	function admin_manageinline($id=null) {
		if($id && ($this->data = $this->Category->find(array('Category.id'=>$id),null,'Category.id ASC'))) {
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


}
?>