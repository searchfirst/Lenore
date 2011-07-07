<?php
class LenoreComponent extends Object {
	var $Controller;

	function initialize(&$controller) {
		$this->Controller =& $controller;
		$this->Controller->Auth->allowedActions = array('display','login','logout','index','view','add');
		$this->minifyJs();
		$this->minifyCss();
		if (!$this->actionIsAdmin()) {
			$this->rebindModels();
		}
	}

	function startup(&$controller, $settings=array()) {
		$this->setCurrentTheme();
		$this->setModDateHeader();
		$this->setAltContentViewParams();
		$this->setRequestHandlerViewVars();
		$this->customControllerViewData();
		$this->mergeGetDataWithThisData();
		$this->setSnippets();
		$this->getInboxMessages();
		$this->aggregateFeeds();
	}

	function mergeGetDataWithThisData() {
		if(!empty($this->Controller->params['url']['data'])) {
			if(empty($this->Controller->data)) {
				$this->Controller->data = $this->Controller->params['url']['data'];
			} else {
				$this->Controller->data = array_merge($this->Controller->params['url']['data'],$this->Controller->data);
			}
			unset($this->Controller->params['url']['data']);
		}
	}

	function minifyCss() {
		if($this->actionIsAdmin()) {
			$this->Controller->set('minify_css',$this->Controller->Minify->css(array(
				'css/admin/reset.css','css/admin/typefaces.css','css/admin/lenore.css','css/admin/widgets/hook_menu.css',
				'css/admin/widgets/sortable.css','css/admin/widgets/editable_text.css','css/admin/widgets/flag_toggle.css',
				'css/admin/widgets/dialog.css','css/admin/widgets/flash_messages.css','css/admin/widgets/resource_list.css',
				'css/admin/widgets/paginate.css','css/admin/handheld_large.css','css/admin/tablet_netbooks.css','css/admin/desktop.css'
			)));
		} elseif($css_paths = Configure::read('Minify.public.css')) {
			$pub_minify_css = array();
			foreach($css_paths as $t=>$css) {
				$pub_minify_css[$t] = $this->Controller->Minify->css($css);
			}
			$this->Controller->set('pub_minify_css',$pub_minify_css);
		}
	}

	function minifyJs() {
		if($this->actionIsAdmin()) {
			$this->Controller->set('min_js_head',$this->Controller->Minify->js(array(
				'js/lib/modernizr.min.js','js/lib/flexie.js','js/jquery/ui/core.js',
				'js/jquery/ui/widget.js','js/jquery/ui/mouse.js', 'js/jquery/ui/bgiframe.js','js/jquery/ui/sortable.js','js/jquery/ui/dialog.js',
				'js/jquery/ui/position.js','js/jquery/lib/iphoneui.js','js/jquery/lib/editable_text.js','js/jquery/hook_menu.js','js/jquery/dux_tabs.js',
				'js/jquery/lib/flag_toggle.js','js/lib/ckeditor/lenore_load.js','dontpack1'=>'js/lib/ckeditor/ckeditor.js','dontpack2'=>'js/lib/ckeditor/adapters/jquery.js','js/admin/load_config.js',
				'js/lib/underscore.js','js/lib/backbone.js','js/lib/paginatedcollection.backbone.js',
				'js/admin/app.js','js/admin/models/message.js','js/admin/collections/message_collection.js','js/admin/views/message_view.js'
			)));
			$this->Controller->set('min_js_ltie9',$this->Controller->Minify->js(array(
				'js/lib/selectivizr.min.js','js/lib/mediaqueries.min.js'
			)));
		} elseif($js_paths = Configure::read('Minify.public.js')) {
			$pub_minify_js = array();
			foreach($js_paths as $t=>$js) {
				$pub_minify_js[$t] = $this->Controller->Minify->js($js);
			}
			$this->Controller->set('pub_minify_js',$pub_minify_js);
		}
	}

	function customControllerViewData() {
		$this_model = Inflector::singularize($this->Controller->name);
		$this_action = $this->Controller->action;
		if(($action_data=Configure::read("$this_model.action_data.$this_action")) && is_array($action_data)) {
			foreach($action_data as $key=>$action) {
				$set = $key;
				$model = $action['model'];
				$type = $action['type'];
				$query['conditions'] = $action['conditions'];
				if(!empty($action['order'])) $query['order'] = $action['order'];
				if(!empty($action['limit'])) $query['limit'] = $action['limit'];
				if(!empty($action['fields'])) $query['fields'] = $action['fields'];
				if(!empty($action['recursive'])) $query['recursive'] = $action['recursive'];
				if($this->Controller->loadModel($model)) {
					$this->Controller->set($set,$this->Controller->{$model}->find($type,$query));
				}
			}
		}
	}

	function setAltContentViewParams() {
		if(isset($this->Controller->params['alt_content']) && $this->Controller->params['alt_content']=='Rss') {
			$this->Controller->RequestHandler->renderAs($this,'rss');
			$this->Controller->RequestHandler->respondAs('Content-type: application/rss+xml;charset=UTF-8');
		} elseif(isset($this->Controller->params['alt_content']) && $this->Controller->params['alt_content']=='Xml') {
			$this->Controller->RequestHandler->renderAs($this,'xml');
			$this->Controller->RequestHandler->respondAs('xml');
		}
	}

	function setRequestHandlerViewVars() {
		$this->Controller->set('isAjax',$this->Controller->RequestHandler->isAjax());
		$this->Controller->set('is_ajax',$this->Controller->RequestHandler->isAjax());
		$this->Controller->set('isMobile',$this->Controller->RequestHandler->isMobile());
		$this->Controller->set('is_mobile',$this->Controller->RequestHandler->isMobile());
	}

	function setModDateHeader() {
		if(!empty($this->Controller->viewVars['mod_date_for_layout'])) $this->Controller->header('Last-Modified: '.$this->Controller->Time->toRSS($this->Controller->viewVars['mod_date_for_layout']));
	}

	function setCurrentTheme() {
		if($this->actionIsAdmin()) {
			$this->Controller->theme = 'admin';
		} elseif($template_theme = Configure::read('Moonlight.template_theme')) {
			$this->Controller->theme = $template_theme;
			if($custom_layout = Configure::read(Inflector::singularize($this->Controller->name).'.custom_layout')) {
				$this->Controller->layout = $custom_layout;
			}
		}
	}

	function setSnippets() {
		if(!$this->actionIsAdmin()) {
			if(false===($snippets=Cache::read('snippets_f','lenore'))) {
				$snippets = $this->Controller->Snippet->find('list',array('fields'=>array('Snippet.slug','Snippet.content')));
				Cache::write('snippets_f',$snippets,'lenore');
			}
			$this->Controller->set('snippets',$snippets);
		}
	}
	
	function actionIsAdmin() {return !empty($this->Controller->params['prefix']) && $this->Controller->params['prefix'] == 'admin';}
	
	function getInboxMessages() {
		if($this->actionIsAdmin()) {
			if(false===($msg_cache=Cache::read('messages_a','lenore'))) {
				$messages = $this->Controller->paginate('Message');
				$msg_cache = array('results' => $messages,'paging' => $this->Controller->params['paging']['Message']);
				Cache::write('messages_a',$msg_cache,'lenore');
			} else {
				$messages = $msg_cache['results'];
				$this->Controller->params['paging']['Message'] = $msg_cache['paging'];
			}
			$this->Controller->set('messages_a',$messages);
		}
	}

	function aggregateFeeds() {
		$feedList = Configure::read('Aggregator.feeds');
		$feeds = array();
		if (!empty($feedList)) {
			$feeds = $this->Controller->Aggregator->find('all',array('conditions'=>$feedList,'feed'=>array( 'cache'=>'default' )));
		}
		$this->Controller->set('aggregatorFeeds',$feeds);
	}

	function rebindModels() {
		if (($catRebind = Configure::read('Category.rebind')) && in_array('Category',$this->Controller->uses)) {
			$this->Controller->Category->rebindModel($catRebind);
		}
		if (($prodRebind = Configure::read('Product.rebind')) && in_array('Product',$this->Controller->uses)) {
			$this->Controller->Product->rebindModel($prodRebind);
		}
		if (($sectRebind = Configure::read('Section.rebind')) && in_array('Section',$this->Controller->uses)) {
			$this->Controller->Section->rebindModel($sectRebind);
		}
		if (($artRebind = Configure::read('Article.rebind')) && in_array('Article',$this->Controller->uses)) {
			$this->Controller->Article->rebindModel($artRebind);
		}
	}
}
