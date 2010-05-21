<?php
class CartsController extends AppController {

	var $name = 'Carts';
	var $uses = array('Cart','Checkout','Product','Category');
	var $pageTitle = 'Shopping Cart';
	var $components = array('Email');
	
	function index() {$this->redirect(array('controller'=>'Carts','action'=>'view'));}
	
	function view() {
		$cart = $this->Cart->getContents($this->Session->read('Cart.contents'));
		$subtotal = 0;
		if(!empty($cart)) {
			foreach($cart as $i=>$item) {
				$cart[$i] = array_merge($cart[$i],$this->Product->find(array('Product.id'=>$item['product_id'])));
				$subtotal += $cart[$i]['Product']['price'] * $cart[$i]['quantity'];
			}
		}
		if(defined('MOONLIGHT_PRODUCTS_SALES_DELIVERY') && MOONLIGHT_PRODUCTS_SALES_DELIVERY && ($subtotal < 30))
			$delivery = number_format(MOONLIGHT_PRODUCTS_SALES_DELIVERY,2);
		else 
			$delivery = 0;
		if(defined('MOONLIGHT_PRODUCTS_SALES_ADDVAT') && MOONLIGHT_PRODUCTS_SALES_ADDVAT)
			$vat = $subtotal * MOONLIGHT_PRODUCTS_SALES_ADDVAT;
		else
			$vat = 0;
		$total = $subtotal + $delivery + $vat;
		$this->set('total',number_format($total,2));
		$this->set('subtotal',number_format($subtotal,2));
		$this->set('delivery',number_format($delivery,2));
		$this->set('vat',number_format($vat,2));
		$this->set('cart',$cart);
	}

	function checkout() {
		$cart = $this->Cart->getContents($this->Session->read('Cart.contents'));
		$this->set('cardTypes',array_combine($this->Checkout->cardTypes,$this->Checkout->cardTypes));
		$subtotal = 0;
		if(!empty($cart)) {
			foreach($cart as $i=>$item) {
				$cart[$i] = array_merge($cart[$i],$this->Product->find(array('Product.id'=>$item['product_id'])));
				$subtotal += $cart[$i]['Product']['price'] * $cart[$i]['quantity'];
			}
		}
		if(defined('MOONLIGHT_PRODUCTS_SALES_DELIVERY') && MOONLIGHT_PRODUCTS_SALES_DELIVERY && ($subtotal < 30))
			$delivery = MOONLIGHT_PRODUCTS_SALES_DELIVERY;
		else 
			$delivery = 0;
		if(defined('MOONLIGHT_PRODUCTS_SALES_ADDVAT') && MOONLIGHT_PRODUCTS_SALES_ADDVAT)
			$vat = $subtotal * MOONLIGHT_PRODUCTS_SALES_ADDVAT;
		else
			$vat = 0;
		$total = $subtotal + $delivery + $vat;
		$this->set('subtotal',number_format($subtotal,2));
		$this->set('delivery',number_format($delivery,2));
		$this->set('total',number_format($total,2));
		$this->set('cart',$cart);
		if(isset($this->data['Checkout'])) {
			if($this->Checkout->validates($this->data)) {
				$this->cleanUpFields();
				$email_data = $this->data;
				$this->set('email_data',$email_data);
				$this->Email->to = MOONLIGHT_WEBMASTER_EMAIL;
				$this->Email->subject = "Order form: {$_SERVER['HTTP_HOST']}";
				$this->Email->from = "{$email_data['Checkout']['name']} <{$email_data['Checkout']['email']}>";
				$this->Email->template = 'checkout';
				$this->Email->sendAs = 'both';
				if($this->Email->send()) {
					$this->Email->to = $email_data['Checkout']['email'];
					$this->Email->subject = "Order form receipt: {$_SERVER['HTTP_HOST']}";
					$this->Email->from = MOONLIGHT_WEBMASTER_NAME." <".MOONLIGHT_WEBMASTER_EMAIL.">";
					$this->Email->template = 'receipt';
					$this->Email->send();
					$email_status = 'The order completed successfully';
					$this->Session->write('Cart.contents',array());
				}
				else $email_status = 'There was an error in sending the order to process. Please try again later.';		
				$this->Session->setFlash($email_status);
				$this->redirect('/cart/message');

			} else $this->Session->setFlash('Please correct the errors below');
		}
		
	}

	function update() {
		if($this->Session->check('Cart.contents')) {
			$cart = $this->Session->read('Cart.contents');
			if(isset($this->data['typehash']) && isset($cart[$this->data['typehash']]) && isset($this->data['quantity'])) {
				$cart[$this->data['typehash']]['quantity'] = $this->data['quantity'];
				$this->Session->write('Cart.contents',$cart);
				$this->Session->setFlash('Cart updated successfully','success',array());
			} else {
				$this->Session->setFlash('Error updating cart.');
			}
		} else {
			$this->Session->setFlash('Error updating cart.');			
		}
		$this->redirect($this->referer('/'),null,true);
	}

	function add() {
		if(!empty($this->data['Cart'])) {
			if($this->Cart->set($this->data)) {
				$product_name = $this->data['Cart']['name'];
				$product_quantity = $this->data['Cart']['quantity'];
				$contents = $this->Session->read('Cart.contents');
				$this->Session->write('Cart.contents',$this->Cart->mergeContents($this->data,$contents));
				$this->Session->setFlash("$product_quantity x $product_name added to your shopping cart",'success',array());
			} else {
				$this->Session->setFlash('Error adding to cart');
			}
			$this->redirect($this->referer('/'),null,true);
		} else {
			$this->Session->setFlash('Error adding to cart');
			$this->redirect($this->referer('/'),null,true);
		}
	}
	
	function clear() {
		if($this->Session->check('Cart.contents')) {
			$this->Session->write('Cart.contents',array());
			$this->Session->setFlash('Cart emptied successfully','success',array());
		} else {
			$this->Session->setFlash('Cart already empty','success',array());
		}
		$this->redirect($this->referer('/'),null,true);
	}
	
	function remove() {
		$cart = $this->Session->read('Cart.contents');
		if(isset($this->data['typehash']) && isset($cart[$this->data['typehash']])) {
			unset($cart[$this->data['typehash']]);
			$this->Session->write('Cart.contents',$cart);
			$this->Session->setFlash('Item removed successfully','success',array());
		} else {
			$this->Session->setFlash('Error removing item');
		}
		$this->redirect($this->referer('/'),null,true);
	}
	
	function message() {}

}
?>