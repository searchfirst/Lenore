<?php
class AppController extends Controller {
	var $uses = array(
		'Section','Category','Snippet','Message','Feeds.Aggregator'
	);
	var $helpers = array(
		'Html','Form','Time','TextAssistant','MediaAssistant','Js','Javascript','Session','Menu','Minify.Minify','Paginator'
	);
	var $actionHelpers = array(
		'Time'
	);
	var $components = array(
		'RequestHandler','Session','Acl','Auth','Helper','Minify.Minify','Menu','Lenore'
	);
	var $paginate = array(
		'Message' => array(
			'limit' => 5,
			'order' => array('Message.created' => 'desc')
		)
	);
	var $view = 'Mustache';

	function generalAjax($json_object=array()) {
		Configure::write('debug', 0);
		header('Content-Type: application/json;charset=UTF-8');
		$this->set('json_object',$json_object);
		$this->viewPath = 'ajax';
		$this->render('general_ajax');
	}

	function restfulPaginate($model) {
		$results = $this->paginate($model);
		$r_results = array();
		foreach($results as $result)
			if(!empty($result[$model]))
				$r_results['models'][] = $result[$model];
		if(!empty($this->params['paging'][$model])) {
			$params =& $this->params['paging'][$model];
			$options = array_merge($params['defaults'],!empty($params['options'])?$params['options']:array());
			$r_results['total'] = $params['count'];
			$r_results['per_page'] = $options['limit'];
			$r_results['page'] = $options['page'];
		}
		return $r_results;
	}
}
