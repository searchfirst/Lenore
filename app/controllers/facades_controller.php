<?php
class FacadesController extends AppController
{
	var $name = 'Facades';
	var $pageTitle = 'Home';
	var $layout = 'home';
	function index() {
		$section = $this->Section->find('first',array('conditions'=>array('Section.slug'=>'home')));
//		$this->set('current_page','home');
		$this->set('home_section',$section);
		$this->set('home_articles',$section['Article']);
		if(!empty($section['Section']['meta_description']) || !empty($section['Section']['meta_keywords']))
			$this->set('metadata_for_layout',array('description'=>$section['Section']['meta_description'],'keywords'=>$section['Section']['meta_keywords']));
	}
}
?>