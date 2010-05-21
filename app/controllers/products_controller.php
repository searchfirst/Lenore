<?php
class ProductsController extends AppController
{
	var $name = 'Products';
	var $helpers = array('Html','Form','Time','TextAssistant','MediaAssistant','Javascript','ProductOption');

	function index() {
		if(isset($this->params['alt_content']) && $this->params['alt_content']!='Rss') {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
		$this->set('products',$this->Product->findall("Product.draft=0",null,'Product.modified DESC',20));
		$this->pageTitle = MOONLIGHT_CATEGORIES_TITLE;
	}
	
	function view($slug=null) {
		$slug = $this->params['slug'];
		
		$product = $this->Product->find(array('Product.draft'=>0,'Product.slug'=>$slug));
		if(!empty($product) && (!isset($this->params['alt_content']) || $this->params['alt_content']!='Rss')) {
			if($product['Category']['slug']!=$this->params['category']) {
				$this->redirect("/products/{$product['Category']['slug']}/{$product['Product']['slug']}",301);
				return true;
			}
			$this->pageTitle = $product['Product']['title'].': '.$product['Category']['title'];
			$this->set('current_parent_section','category-'.$product['Category']['slug']);
			$this->set('product', $product);
			$this->set('current_page',$product['Category']['slug']);
			//if(!$this->Session->check('Message.flash') && !empty($this->Session->read('Message.flash'))) $this->set('mod_date_for_layout', $product['Product']['modified']);
			if(!empty($product['Product']['meta_description']) || !empty($product['Product']['meta_keywords']))
				$this->set('metadata_for_layout',array('description'=>$product['Product']['meta_description'],'keywords'=>$product['Product']['meta_keywords']));
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}


	function admin_index() {
		$this->Product->recursive = 0;
		$this->set('products', $this->Product->findAll());
	}

	function admin_add() {
		if(empty($this->data) || isset($this->data['Referrer']['category_id'])) {
			$this->data['Product']['category_id'] = $this->data['Referrer']['category_id'];
			$this->set('category', $this->Product->Category->generateList());
		} else {
			$this->cleanUpFields();
			if($this->Product->save($this->data)) {
				if(isset($GLOBALS['moonlight_inline_count_set'])) {
					$this->Session->setFlash("This item has been saved. You need to upload media for this item");
					$this->redirect('/'.strtolower($this->name).'/manageinline/'.$this->Product->getLastInsertId());
				} else {
					$this->Session->setFlash("This item has been saved.");
					$this->redirect('/'.strtolower($this->name).'/view/'.$this->Product->getLastInsertId());
				}
			} else {
				$this->Session->setFlash('Please correct the errors below');
				$this->data['Referral']['category_id'] = $this->data['Product']['category_id'];
				$this->set('category', $this->Product->Category->generateList());
			}
		}
	}

	function admin_edit($id) {
		if( (isset($this->data['Product']['submit'])) || (empty($this->data)) ) {
			if(!$id) {
				$this->Session->setFlash('Invalid id for Article');
				$this->redirect('/articles/');
			}
			$this->data = $this->Product->read(null, $id);
			$this->set('product',$this->data);
			$this->set('categories', $this->Product->Category->generateList());
		} else {
			$this->cleanUpFields();
			if($this->Product->save($this->data)) {
				if(isset($GLOBALS['moonlight_inline_count_set'])) {
					$this->Session->setFlash("This item has been saved. You now need to upload any media for this item");
					$this->redirect("/".Inflector::underscore($this->name)."/manageinline/$id");
				} else {
					$this->Session->setFlash("This item has been saved. You now need to upload any media for this item");
					$this->redirect("/".Inflector::underscore($this->name)."/view/$id");
				}
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('product',$this->data);
				$this->set('categories', $this->Product->Category->generateList());
			}
		}
	}

	function admin_view($id) {
		$this->set('product', $this->Product->read(null, $id));
	}

	function admin_delete($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Product');
			$this->redirect($this->referer('/categories/'));
		}
		if( ($this->data['Product']['id']==$id) && ($this->Product->del($id)) ) {
			$this->Session->setFlash('Product deleted: id '.$id.'.');
			$category_id = $this->Product->Category->findByProduct($id);
			$this->redirect($this->referer('/categories/'));
		} else {
			$this->set('id',$id);
		}
	}

	function admin_moveup() {
		if(isset($this->data['Product']['id']) && isset($this->data['Product']['prev_id'])) {
			if($this->Product->swapFieldData($this->data['Product']['id'],$this->data['Product']['prev_id'],'order_by'))
				$this->redirect($this->referer('/categories/'));
			else { 
				$this->Session->setFlash('There was an error swapping the products');
				$this->redirect($this->referer('/categories/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid products. Check you selected the correct product');
			$this->redirect($this->referer('/categories/'));
		}
	}
	
	function admin_manageinline($id=null) {
		if($id && ($this->data = $this->Product->read(null, $id))) {
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