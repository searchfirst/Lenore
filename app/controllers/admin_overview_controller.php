<?php
class AdminOverviewController extends AppController {
	var $name = 'AdminOverview';
	var $uses = array('Section','Category');
	
	function admin_index() {
		$this->set('title_for_layout','');
		$this->set('site_modules',Configure::read('Admin.active_modules'));
		$category_list = Set::combine($this->Category->find('all',array('fields'=>array('Category.id','Category.title'),'recursive'=>0)),'{n}.Category.id','{n}.Category.title');
		if(!empty($category_list)) {
			$category_info = array();
			foreach($category_list as $cat_key => $cat_val)
				$category_info[] = array(
					'title'=>$cat_val,
					'id'=>$cat_key,
					'product_count'=>$this->Category->Product->find('count',array('conditions'=>array('Category.id'=>$cat_key,'Product.draft'=>0)
				)));
			$this->set('category_info',$category_info);
		}
		$section_list = Set::combine($this->Section->find('all',array('order'=>'Section.title ASC','recursive'=>0)),'{n}.Section.id','{n}.Section.title');
		if(!empty($section_list)) {
			$section_info = array();
			foreach($section_list as $sect_key => $sect_val)
				$section_info[] = array(
					'title'=>$sect_val,
					'id'=>$sect_key,
					'article_count'=>$this->Section->Article->find('count',array('conditions'=>array('Section.id'=>$sect_key,'Article.draft'=>0))));
			$this->set('section_info',$section_info);
		}
	}
}