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
		$this->pageTitle = Inflector::pluralize(Configure::read('Product.alias'));
	}
	
	function view($slug=null) {
		$product = $this->Product->find('first',array(
			'conditions'=>array('Product.draft'=>0,'Product.slug'=>$slug)
		));
		if(!empty($product) && (!isset($this->params['alt_content']) || $this->params['alt_content']!='Rss')) {
			if($product['Category']['slug']!=$this->params['category']) {
				$this->redirect(sprintf('/%s/%s/%s',Inflector::tableize(Configure::read('Product.alias'))),$product['Category']['slug'],$product['Product']['slug'],301);
				return true;
			}
			$this->set('title_for_layout',sprintf('%s | %s',$product['Product']['title'],$product['Category']['title']));
			//$this->set('current_parent_section','category-'.$product['Category']['slug']);
			$this->set('product', $product);
			//$this->set('current_page',$product['Category']['slug']);
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
		if(empty($this->data)) {
			$this->set('categories',$this->Product->Category->find('list'));
		} else {
			if($this->Product->save($this->data)) {
				if(isset($GLOBALS['moonlight_inline_count_set'])) {
					$this->Session->setFlash("This item has been saved. You need to upload media for this item");
					$this->redirect('/admin/products/manageinline/'.$this->Product->getLastInsertId());
				} else {
					$this->Session->setFlash("This item has been saved.");
					$this->redirect('/admin/products/view/'.$this->Product->getLastInsertId());
				}
			} else {
				$this->Session->setFlash('Please correct the errors below');
				//$this->data['Referral']['category_id'] = $this->data['Product']['category_id'];
				$this->set('categories',$this->Product->Category->find('list'));
			}
		}
	}

	function admin_edit($id) {
		if( (isset($this->data['Product']['submit'])) || (empty($this->data)) ) {
			if(!$id) {
				$this->viewPath = 'errors';
				$this->render('not_found');
			}
			$this->data = $this->Product->find('first', array('conditions'=>array('Product.id'=>$id)));
			$this->set('categories', $this->Product->Category->find('list',array('recursive'=>0)));
		} else {
			if($this->Product->save($this->data)) {
				if(isset($GLOBALS['moonlight_inline_count_set'])) {
					$this->Session->setFlash("This item has been saved. You now need to upload any media for this item");
					$this->redirect("/".Inflector::underscore($this->name)."/manageinline/$id");
				} else {
					$this->Session->setFlash("This item has been saved. You now need to upload any media for this item");
					$this->redirect("/admin/products/view/$id");
				}
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('product',$this->data);
				$this->set('categories', $this->Product->Category->find('list',array('recursive'=>0)));
			}
		}
	}

	function admin_view($id) {
		if(!$id) {
			$this->redirect($this->referer('/admin/categories/'));
		} else {
			$product = $this->Product->find('first', array('conditions'=>array('Product.id'=>$id)));
			if($product) {
				$this->set('product',$product);
				$this->set('inline_media',array(
					'balance' => count($product['Resource']) - $product['Product']['inline_count'],
					'count' => $product['Product']['inline_count']
				));
			} else {
				$this->viewPath = 'errors';
				$this->render('not_found');
			}
		}
	}

	function admin_delete($id=null) {
		if(!$id) {
			$this->redirect($this->referer('/admin/categories/'));
		} else {
			if(!empty($this->data) && $this->data['Product']['id']==$id && $this->Product->delete($id)) {
				$this->Session->setFlash('Product deleted');
				$this->redirect($this->referer('/categories/'));
			} else {
				$this->set('id',$id);
			}
		}
	}

	function admin_moveup() {
		if(isset($this->data['Product']['id']) && isset($this->data['Product']['prev_id'])) {
			if($this->Product->swapFieldData($this->data['Product']['id'],$this->data['Product']['prev_id'],'order_by'))
				$this->redirect($this->referer('/admin/categories/'));
			else { 
				$this->Session->setFlash('There was an error swapping the products');
				$this->redirect($this->referer('/admin/categories/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid products. Check you selected the correct product');
			$this->redirect($this->referer('/admin/categories/'));
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

	function admin_reorder() {
		$ajax_result = true;
		if(!(empty($this->data['Initial'])||empty($this->data['Final']))) {
			$new_ids = $this->data['Final'];
			$current_orders = $this->Product->find('all',array(
				'conditions' => array('Product.id'=>$this->data['Initial']),
				'recursive' => 0,
				'fields' => array('Product.id','Product.order_by'),
				'order' => 'Product.order_by ASC'
			));
			foreach($current_orders as $x=>$co) {
				$product = array('Product'=>array('id'=>$new_ids[$x],'order_by'=>$co['Product']['order_by']));
				if(!$this->Product->save($product)) $ajax_result = $ajax_result && false;
			}
		} else {
			$ajax_result = $ajax_result && false;
		}
		$this->set('ajax_result',$ajax_result?'Success':'Fail');	
	}


}
?>