<?php
class ProtectedSectionsController extends AppController {

	var $name = 'ProtectedSections';
	var $pageTitle = 'Private Support Area';

	function index() {
		if(!empty($this->data)) {
			if(isset($this->data['ProtectedSection']['logout'])) {
				$this->_deleteSession();
				$this->Session->setFlash('You have successfully logged out');
				$this->redirect('/private');
				return true;
			}
			$username = $this->data['ProtectedSection']['title'];
			$hash = md5($this->data['ProtectedSection']['password']);
			if($protected_section = $this->ProtectedSection->authenticate($username,$hash))
				$this->_saveSession($username,$hash);
		} else {
			$username = $this->Session->read('ProtectedSection.title');
			$hash = $this->Session->read('ProtectedSection.hash');
			$protected_section = $this->ProtectedSection->authenticate($username,$hash);
		}
		if($protected_section) {
			$this->set('protected_section', $protected_section);
		} else {
			$this->render('login');
		}
	}	
	
	function view($slug = null) {
		if(!$slug) {
			$this->Session->setFlash('Invalid id for Section.');
			$this->redirect('/sections/');
		}
		if($slug=='news') $this->Section->bindModel(array('hasMany'=>array('Article'=>array('className'=>'Article','conditions'=>'draft=0','order'=>'modified DESC'))));
		$section = $this->Section->findBySlug($slug);
		if(!empty($get_section_from_db)) {
			$this->pageTitle= $section['Section']['title'];
			$this->set('section', $section);
			$this->set('mod_date_for_layout',
				$this->Section->Article->field('modified',"Article.draft=0 AND Article.section_id={$section['Section']['id']}",'Article.modified DESC'));
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}

	function _saveSession($username,$password_hash) {
		$this->Session->write('ProtectedSection.title',$username);
		$this->Session->write('ProtectedSection.hash',$password_hash);
	}
	
	function _deleteSession() {
		$this->Session->del('ProtectedSection.title');
		$this->Session->del('ProtectedSection.hash');
	}


	function admin_index() {
		$this->ProtectedSection->recursive = 0;
		$this->set('protectedSections', $this->ProtectedSection->findAll(null,null,"order_by ASC",null,null,1));
	}

	function admin_view($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Section.');
			$this->redirect('/protected_sections/');
		}
		$this->set('protectedSection', $this->ProtectedSection->read(null, $id));
	}

	function admin_add() {
		if(empty($this->data)) {
			$this->set('resources', $this->ProtectedSection->Resource->generateList());
		} else {
			$this->cleanUpFields();
			if($this->ProtectedSection->save($this->data)) {
				if(isset($GLOBALS['moonlight_inline_count_set'])) {
					$this->Session->setFlash("This item has been saved. You need to upload media for this item");
					$this->redirect('/'.Inflector::underscore($this->name).'/manageinline/'.$this->ProtectedSection->getLastInsertId());
				} else {
					$this->Session->setFlash("This item has been saved.");
					$this->redirect('/'.Inflector::underscore($this->name).'/view/'.$this->ProtectedSection->getLastInsertId());
				}
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('resources', $this->ProtectedSection->Resource->generateList());
			}
		}
	}

	function admin_edit($id = null) {
		if(isset($this->data['ProtectedSection']['submit']) || empty($this->data)) {
			if(!$id) {
				$this->Session->setFlash('Invalid id for Section');
				$this->redirect('/protected_sections/');
			}
			$this->data = $this->ProtectedSection->read(null, $id);
			$this->set('protectedSection',$this->data);
			$this->set('resources', $this->ProtectedSection->Resource->generateList());
		} else {
			$this->cleanUpFields();
			if($this->ProtectedSection->save($this->data)) {
				if(isset($GLOBALS['moonlight_inline_count_set'])) {
					$this->Session->setFlash("This item has been saved. You may need to upload media for this item");
					$this->redirect("/".Inflector::underscore($this->name)."/manageinline/$id");
				} else {
					$this->Session->setFlash("This item has been saved.");
					$this->redirect("/".Inflector::underscore($this->name)."/view/$id");
				}
			} else {
				$this->Session->setFlash('Please correct errors below.');
				$this->set('protectedSection', $this->ProtectedSection->read(null, $id));
				$this->set('resources', $this->ProtectedSection->Resource->generateList());
			}
		}
	}

	function admin_delete($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Section');
			$this->redirect('/protected_sections/');
		}
		if( ($this->data['ProtectedSection']['id']==$id) && ($this->ProtectedSection->del($id)) ) {
			$this->Session->setFlash('The Section deleted: id '.$id.'');
			$this->redirect('/protected_sections/');
		} else {
			$this->set('id',$id);
		}
	}

	function admin_moveup() {
		if(isset($this->data['ProtectedSection']['id']) && isset($this->data['ProtectedSection']['prev_id'])) {
			if($this->ProtectedSection->swapFieldData($this->data['ProtectedSection']['id'],$this->data['ProtectedSection']['prev_id'],'order_by'))
				$this->redirect($this->referer('/protected_sections/'));
			else { 
				$this->Session->setFlash('There was an error swapping the sections');
				$this->redirect($this->referer('/protected_sections/'));
			}
		} else {
			$this->Session->setFlash('Attempt to swap order of invalid sections. Check you selected the correct section');
			$this->redirect($this->referer('/protected_sections/'));
		}
	}
	
	function admin_manageinline($id=null) {
		if($id && ($this->data = $this->ProtectedSection->read(null, $id))) {
			$this->set(Inflector::underscore($this->modelClass),$this->data);
			$this->set('media_data',$this->data['Resource']);
			$db_inline_count = (int) $this->data[$this->modelClass]['inline_count'];
			$actual_inline_count = count($this->data['Resource']);
			if(preg_match('/'.Inflector::underscore($this->name)."\\/(add|edit)/",$this->referer()) && ($db_inline_count == $actual_inline_count))
				$this->redirect('/'.Inflector::underscore($this->name).'/view/'.$id);
			$this->set('inline_data', array('db_count'=>$db_inline_count,'actual_count'=>$actual_inline_count));
			if(!isset($this->data['Resource'])) {
				$this->Session->setFlash('No inline media in '.$this->modelClass);
				$this->redirect('/'.Inflector::underscore($this->name).'/view/'.$id);
			}
		} else {
			$this->Session->setFlash('Invalid '.$this->modelClass.'.');
			$this->redirect('/'.Inflector::underscore($this->name).'/');
		}
	}


}
?>