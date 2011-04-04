<?php
class MenuComponent extends Object {

	var $Controller;

	function startup(&$controller) {
		if(empty($controller->plugin)) {
			$this->Controller = $controller;
			if($this->Controller->Lenore->actionIsAdmin()) {
				$this->setAdminMenu();
			} else {
				$this->setMenus();
			}
		}
	}

	function setAdminMenu() {
		$admin_menu = array('admin' => 'Dashboard');
		$enabled_modules = Configure::read('Admin.active_modules');
		foreach($enabled_modules as $x=>$module) $admin_menu['admin/'.Inflector::tableize($x)] = Inflector::pluralize($module['alias']);
		$admin_menu['admin/snippets'] = 'Snippets';
		$admin_menu['http://moonlight-project.co.uk/help'] = 'Help';
		$this->Controller->set('admin_menu',$admin_menu);
	}

	function setMenus() {
		if(!$menu_prefix=Configure::read('Menu.prefix')) $menu_prefix = array();
		if(!$menu_suffix=Configure::read('Menu.suffix')) $menu_suffix = array();
		if(!$menu_omissions=Configure::read('Menu.omissions')) $menu_omissions = array();
		$this->Controller->set('menu_prefix',$menu_prefix);
		$this->Controller->set('menu_suffix',$menu_suffix);
		$this->Controller->set('menu_omissions',$menu_omissions);
		$this->Controller->set('moonlight_website_menu',$this->Controller->Section->find('list',array(
			'conditions'=>array('Section.draft'=>false),
			'fields'=>array('Section.slug','Section.title'),
			'order'=>'Section.order_by ASC'
		)));
		$products = $this->Controller->Category->find('list',array(
			'fields'=>array('Category.slug','Category.title'),
			'conditions'=>array('Category.category_id'=>null,'Category.draft'=>false),
			'order'=>'Category.order_by ASC'
		));
		$product_list = array();
		foreach ($products as $x=>$p) { $product_list[sprintf('%s/%s',Inflector::tableize(Configure::read('Category.alias')),$x)] = $p; }
		$this->Controller->set('moonlight_product_list', $product_list);
	}
}
