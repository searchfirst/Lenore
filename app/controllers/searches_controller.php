<?php
class SearchesController extends AppController
{
	var $name = 'Searches';
	var $uses = array('Product');
	var $helpers = array('Html','Form','Time','TextAssistant','MediaAssistant','Javascript','ProductOption');

	function index() {
		if(isset($this->params['form']['search_q'])) {
			$search_q = $this->params['form']['search_q'];
			if(isset($this->params['alt_content']) && $this->params['alt_content']!='Rss') {
				$this->viewPath = 'errors';
				$this->render('not_found');
			}
			$results = $this->Product->fulltext($search_q,array('title','description'));
			if(count($results)==1) {
				$this->redirect("/products/{$results[0]['Category']['slug']}/{$results[0]['Product']['slug']}");
			} else {
				$this->set('results',$results);
				$this->set('search_query',$search_q);
			}
		} else {
			$this->Session->setFlash('No search term specified');
			$this->redirect($this->referer('/'));
		}
	}
	
}
?>