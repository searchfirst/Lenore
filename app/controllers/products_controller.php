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
			'conditions'=>array('Product.draft'=>false,'Product.slug'=>$slug)
		));
		if(!empty($product) && (!isset($this->params['alt_content']) || $this->params['alt_content']!='Rss')) {
			if($product['Category']['slug']!=$this->params['category']) {
				$this->redirect(sprintf('/%s/%s/%s',Inflector::tableize(Configure::read('Product.alias'))),$product['Category']['slug'],$product['Product']['slug'],301);
				return true;
			}
			$this->set('title_for_layout',sprintf('%s | %s',$product['Product']['title'],$product['Category']['title']));
			$this->set('product', $product);
			$this->set('breadcrumb',array(
				''=>'Home',sprintf('%s/%s',Inflector::tableize(Configure::read('Category.alias')),$product['Category']['slug'])=>$product['Category']['title']
			));
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
				$this->Session->setFlash("This item has been saved.",'flash/default',array('class'=>'success'));
				$this->redirect('/admin/products/view/'.$this->Product->getLastInsertId());
			} else {
				$this->Session->setFlash('Please correct the errors below','flash/default',array('class'=>'error'));
				$this->set('categories',$this->Product->Category->find('list'));
			}
		}
	}

	function admin_edit($id) {
		if($id!=null) {
			if(!empty($this->data)) {
				if($this->Product->save($this->data)) {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash("This item has been saved.",'flash/default',array('class'=>'success'));
						$this->redirect(sprintf('/admin/products/view/%s',$this->data['Product']['id']));
					} else {
						$this->generalAjax($this->Product->ajaxFlagArray($id,'success'));
					}
				} else {
					if(!$this->RequestHandler->isAjax()) {
						$this->set('title_for_layout',sprintf('Edit %s: %s',Configure::read('Product.alias'),$this->data['Product']['title']));
						$this->set('categories', $this->Product->Category->find('list',array('order'=>'Category.title ASC','recursive'=>0)));
						$this->Session->setFlash('Please correct errors below.','flash/default',array('class'=>'error'));
					} else {
						$this->generalAjax(array('status'=>'fail'));
					}
				}
			} else {
				$this->data = $this->Product->read(null, $id);
				$this->set('title_for_layout',sprintf('Edit %s: %s',Configure::read('Product.alias'),$this->data['Product']['title']));
				$this->set('categories', $this->Product->Category->find('list',array('order'=>'Category.title ASC','recursive'=>0)));
			}
		} else {
				$this->viewPath = 'errors';
				$this->render('not_found');
		}
	}

	function admin_view($id) {
		if(!$id) {
			$this->redirect($this->referer('/admin/categories/'));
		} else {
			$this->Product->recursive = 2;
			$product = $this->Product->read(null,$id);
			$this->data = $product;
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
			$this->viewPath = 'errors';
			$this->render('not_found');
		} else {
			$this->set('id',$id);
			if(!empty($this->data['Product']['id'])) {
				if($this->Product->delete($this->data['Product']['id'])) {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash(sprintf('%s deleted',Configure::read('Product.alias')),'flash/default',array('class'=>'success'));
						$this->redirect('/admin/categories/');
					} else {
						$this->generalAjax(array('status'=>'success','model'=>'product','id'=>$this->data['Product']['id']));	
					}
				} else {
					if(!$this->RequestHandler->isAjax()) {
						$this->Session->setFlash(sprintf('There was an error deleting this %s',Configure::read('Product.alias')),'flash/default',array('class'=>'error'));
						$this->redirect('/admin/categories/');
 					} else {
						$this->generalAjax(array('status'=>'fail'));
					}
				}
			} else {
				$this->data = array('Product'=>array('id'=>$id));
			}
		}
	}

	function admin_moveup() {
		if(isset($this->data['Product']['id']) && isset($this->data['Product']['prev_id'])) {
			if($this->Product->swapFieldData($this->data['Product']['id'],$this->data['Product']['prev_id'],'order_by'))
				$this->redirect($this->referer('/admin/categories/'));
			else { 
				$this->Session->setFlash('There was an error swapping the products','flash/default',array('class'=>'error'));
				$this->redirect($this->referer('/admin/categories/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid products. Check you selected the correct product','flash/default',array('class'=>'error'));
			$this->redirect($this->referer('/admin/categories/'));
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
