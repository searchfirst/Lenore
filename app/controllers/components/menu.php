<?php
class MenuComponent extends Object {

	var $controller;

	function startup(&$controller) {
		if(empty($controller->plugin)) {
			$this->controller = $controller;
			if($this->controller->actionIsAdmin()) {
				$this->setAdminMenu();
			} else {
				$this->setMenus();
			}
		}
	}

	function setAdminMenu() {
		
	}

	function setMenus() {
		$this->controller->set('menu_prefix',Configure::read('Menu.prefix'));
		$this->controller->set('menu_suffix',Configure::read('Menu.suffix'));
		$this->controller->set('menu_omissions',Configure::read('Menu.omissions'));
		$this->controller->set('moonlight_website_menu',Set::combine($this->controller->Section->find('all',array(
			'fields'=>array('Section.slug','Section.title'),
			'order'=>'Section.order_by ASC',
			'recursive'=>0
		)),
			'{n}.Section.slug','{n}.Section.title'
		));
		$this->controller->set('moonlight_product_list', Set::combine($this->controller->Category->find('all',array(
			'conditions'=>array('Category.category_id'=>null,'Category.draft'=>false),
			'fields'=>array('Category.slug','Category.title'),
			'order'=>'Category.order_by ASC',
			'recursive'=>0
		)),
			'{n}.Category.slug','{n}.Category.title'
		));
	}
}
?>