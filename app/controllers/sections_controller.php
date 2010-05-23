<?php
class SectionsController extends AppController {
	var $name = 'Sections';

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allowedActions = array('index', 'view');
	}

	function view($slug = null) {
		if(!$slug) {
			$this->Session->setFlash('Invalid id for Section.');
			$this->redirect('/');
		}
		if($slug=='news')
			$this->Section->bindModel(array('hasMany'=>array('Article'=>array('className'=>'Article','conditions'=>'draft=0','order'=>'modified DESC'))));
		if($slug=='blog')
			$this->Section->bindModel(array('hasMany'=>array('Article'=>array('className'=>'Article','conditions'=>'draft=0','order'=>'created DESC','limit'=>'15'))));
		$section = $this->Section->findBySlug($slug);
		if(!empty($section)) {
			$this->set('title_for_layout',$section['Section']['title']);
			$this->set('section', $section);
			$this->set('current_page',$slug);
			$this->set('mod_date_for_layout',
				$this->Section->Article->field('modified',array('Article.draft'=>0,'Article.section_id'=>$section['Section']['id']),'Article.modified DESC'));
			if(!empty($section['Section']['meta_description']) || !empty($section['Section']['meta_keywords']))
				$this->set('metadata_for_layout',array('description'=>$section['Section']['meta_description'],'keywords'=>$section['Section']['meta_keywords']));
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}


	function admin_index() {
		$sections = $this->Section->find('all',array(
			'order' => 'Section.order_by ASC',
			'recursive' => 1
		));
		$this->set('sections',$sections);
	}

	function admin_view($id=null,$page=1) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Section.');
			$this->redirect('/admin/sections/');
		}
		$slug = $this->Section->field('slug',array("Section.id"=>$id));
/*		if(preg_match('/(events|news|blog|log)/i',$slug)) {
			$this->Section->bindModel(array('hasMany'=>array(
				'Article'=>array('className'=>'Article','conditions'=>'draft=0','order'=>'modified ASC'))));
			$section_data = $this->Section->read(null,$id);
			$section_data_articles = $this->Section->Article->findAll(array("Section.id"=>$id),null,"Article.created DESC",10,$page,1);
			//Loop through results and add ['Article'] contents to array
			$section_data['Article'] = array();
			foreach($section_data_articles as $section_data_article)
				$section_data['Article'][] = $section_data_article['Article'];
			$page_data['num_pages'] = ceil($this->Section->Article->findCount(array("Section.id"=>$id))/10);
			$page_data['has_prev'] = ($page > 1);
			$page_data['has_next'] = ($page < $page_data['num_pages']);
			$page_data['current'] = $page;
			$this->set('page_data',$page_data);
			$this->set('section',$section_data);
		} else {
*/			$this->set('section', $this->Section->find('first', array('conditions'=>array('Section.id'=>$id))));
/*		}*/
	}

	function admin_add() {
		if(empty($this->data)) {
		} else {
			$this->cleanUpFields();
			if($this->Section->save($this->data)) {
				if(isset($GLOBALS['moonlight_inline_count_set'])) {
					$this->Session->setFlash("This item has been saved. You need to upload media for this item");
					$this->redirect('/'.strtolower($this->name).'/manageinline/'.$this->Section->getLastInsertId());
				} else {
					$this->Session->setFlash("This item has been saved.");
					$this->redirect('/'.strtolower($this->name).'/view/'.$this->Section->getLastInsertId());
				}
			} else {
				$this->Session->setFlash('Please correct errors below.');
			}
		}
	}

	function admin_edit($id = null) {
		if(isset($this->data['Section']['submit']) || empty($this->data)) {
			if(!$id) {
				$this->Session->setFlash('Invalid id for Section');
				$this->redirect('/sections/');
			}
			$this->data = $this->Section->read(null, $id);
			$this->set('section',$this->data);
		} else {
			$this->cleanUpFields();
			if($this->Section->save($this->data)) {
				if(isset($GLOBALS['moonlight_inline_count_set'])) {
					$this->Session->setFlash("This item has been saved. You may need to upload media for this item");
					$this->redirect("/".strtolower($this->name)."/manageinline/$id");
				} else {
					$this->Session->setFlash("This item has been saved.");
					$this->redirect("/".strtolower($this->name)."/view/$id");
				}
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('section', $this->Section->read(null, $id));
			}
		}
	}

	function admin_delete($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Section');
			$this->redirect('/sections/');
		}
		if( ($this->data['Section']['id']==$id) && ($this->Section->del($id)) ) {
			$this->Session->setFlash('The Section deleted: id '.$id.'');
			$this->redirect('/sections/');
		} else {
			$this->set('id',$id);
		}
	}

	function admin_moveup() {
		if(isset($this->data['Section']['id']) && isset($this->data['Section']['prev_id'])) {
			if($this->Section->swapFieldData($this->data['Section']['id'],$this->data['Section']['prev_id'],'order_by'))
				$this->redirect($this->referer('/sections/'));
			else { 
				$this->Session->setFlash('There was an error swapping the sections');
				$this->redirect($this->referer('/sections/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid sections. Check you selected the correct section');
			$this->redirect($this->referer('/sections/'));
		}
	}
	
	function admin_manageinline($id=null) {
		if($id && ($this->data = $this->Section->read(null, $id))) {
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
			$current_orders = $this->Section->find('all',array(
				'conditions' => array('Section.id'=>$this->data['Initial']),
				'recursive' => 0,
				'fields' => array('Section.id','Section.order_by'),
				'order' => 'Section.order_by ASC'
			));
			foreach($current_orders as $x=>$co) {
				$section = array('Section'=>array('id'=>$new_ids[$x],'order_by'=>$co['Section']['order_by']));
				if(!$this->Section->save($section)) $ajax_result = $ajax_result && false;
			}
		} else {
			$ajax_result = $ajax_result && false;
		}
		$this->set('ajax_result',$ajax_result?'Success':'Fail');	
	}

}
?>