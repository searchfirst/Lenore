<?php
class AppController extends Controller {
	var $uses = array('Section','Category');
	var $helpers = array('Html','Form','Time','TextAssistant','MediaAssistant','Js','Javascript','Session','Menu','Minify.Minify');
	var $actionHelpers = array('Time');
	var $components = array('RequestHandler','Session','Acl','Auth','Helper','Minify.Minify','Menu');
	var $view = 'Theme';

	function beforeFilter() {
		$this->Auth->allowedActions = array('display','login','logout','index','view','add');
		$this->minifyJs();
		$this->minifyCss();
	}
	
	function beforeRender() {
		$this->setCurrentTheme();
		$this->setModDateHeader();
		$this->setAltContentViewParams();
		$this->setRequestHandlerViewVars();
		$this->customControllerViewData();
		$this->mergeGetDataWithThisData();
	}

	function mergeGetDataWithThisData() {
		if(!empty($this->params['url']['data'])) {
			if(empty($this->data)) {
				$this->data = $this->params['url']['data'];
			} else {
				$this->data = array_merge($this->params['url']['data'],$this->data);
			}
			unset($this->params['url']['data']);
		}
	}

	function build_acl() {
		if (!Configure::read('debug')) {
			return $this->_stop();
		}
		$log = array();

		$aco = &$this->Acl->Aco;
		$root = $aco->node('controllers');
		if (!$root) {
			$aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
			$root = $aco->save();
			$root['Aco']['id'] = $aco->id; 
			$log[] = 'Created Aco node for controllers';
		} else {
			$root = $root[0];
		}   

		App::import('Core', 'File');
		$Controllers = Configure::listObjects('controller');
		$appIndex = array_search('App', $Controllers);
		if ($appIndex !== false ) {
			unset($Controllers[$appIndex]);
		}
		$baseMethods = get_class_methods('Controller');
		$baseMethods[] = 'buildAcl';

		$Plugins = $this->_getPluginControllerNames();
		$Controllers = array_merge($Controllers, $Plugins);

		// look at each controller in app/controllers
		foreach ($Controllers as $ctrlName) {
			$methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

			// Do all Plugins First
			if ($this->_isPlugin($ctrlName)){
				$pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
				if (!$pluginNode) {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
					$pluginNode = $aco->save();
					$pluginNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
				}
			}
			// find / make controller node
			$controllerNode = $aco->node('controllers/'.$ctrlName);
			if (!$controllerNode) {
				if ($this->_isPlugin($ctrlName)){
					$pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
					$aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
				} else {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $ctrlName;
				}
			} else {
				$controllerNode = $controllerNode[0];
			}

			//clean the methods. to remove those in Controller and private actions.
			foreach ($methods as $k => $method) {
				if (strpos($method, '_', 0) === 0) {
					unset($methods[$k]);
					continue;
				}
				if (in_array($method, $baseMethods)) {
					unset($methods[$k]);
					continue;
				}
				$methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
				if (!$methodNode) {
					$aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
					$methodNode = $aco->save();
					$log[] = 'Created Aco node for '. $method;
				}
			}
		}
		if(count($log)>0) {
			debug($log);
		}
	}

	function _getClassMethods($ctrlName = null) {
		App::import('Controller', $ctrlName);
		if (strlen(strstr($ctrlName, '.')) > 0) {
			// plugin's controller
			$num = strpos($ctrlName, '.');
			$ctrlName = substr($ctrlName, $num+1);
		}
		$ctrlclass = $ctrlName . 'Controller';
		$methods = get_class_methods($ctrlclass);

		// Add scaffold defaults if scaffolds are being used
		$properties = get_class_vars($ctrlclass);
		if (array_key_exists('scaffold',$properties)) {
			if($properties['scaffold'] == 'admin') {
				$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
			} else {
				$methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
			}
		}
		return $methods;
	}

	function _isPlugin($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) > 1) {
			return true;
		} else {
			return false;
		}
	}

	function _getPluginControllerPath($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0] . '.' . $arr[1];
		} else {
			return $arr[0];
		}
	}

	function _getPluginName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0];
		} else {
			return false;
		}
	}

	function _getPluginControllerName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[1];
		} else {
			return false;
		}
	}

/**
 * Get the names of the plugin controllers ...
 * 
 * This function will get an array of the plugin controller names, and
 * also makes sure the controllers are available for us to get the 
 * method names by doing an App::import for each plugin controller.
 *
 * @return array of plugin names.
 *
 */
	function _getPluginControllerNames() {
		App::import('Core', 'File', 'Folder');
		$paths = Configure::getInstance();
		$folder =& new Folder();
		$folder->cd(APP . 'plugins');

		// Get the list of plugins
		$Plugins = $folder->read();
		$Plugins = $Plugins[0];
		$arr = array();

		// Loop through the plugins
		foreach($Plugins as $pluginName) {
			// Change directory to the plugin
			$didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
			// Get a list of the files that have a file name that ends
			// with controller.php
			$files = $folder->findRecursive('.*_controller\.php');

			// Loop through the controllers we found in the plugins directory
			foreach($files as $fileName) {
				// Get the base file name
				$file = basename($fileName);

				// Get the controller name
				$file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
				if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
					if (!App::import('Controller', $pluginName.'.'.$file)) {
						debug('Error importing '.$file.' for plugin '.$pluginName);
					} else {
						/// Now prepend the Plugin name ...
						// This is required to allow us to fetch the method names.
						$arr[] = Inflector::humanize($pluginName) . "/" . $file;
					}
				}
			}
		}
		return $arr;
	}

	function initDB() {
	    $group =& $this->User->Group;
	    //Allow admins to everything
	    $group->id = 1;
		$this->Acl->allow(array( 'model' => 'Group', 'foreign_key' => 1), 'controllers');
//	    $this->Acl->allow($group, 'controllers');
 
	    //allow managers to posts and widgets
/*	    $group->id = 2;
	    $this->Acl->deny($group, 'controllers');
	    $this->Acl->allow($group, 'controllers/Posts');
	    $this->Acl->allow($group, 'controllers/Widgets');*/
 
	    //allow users to only add and edit on posts and widgets
/*	    $group->id = 3;
	    $this->Acl->deny($group, 'controllers');        
	    $this->Acl->allow($group, 'controllers/Posts/add');
	    $this->Acl->allow($group, 'controllers/Posts/edit');        
	    $this->Acl->allow($group, 'controllers/Widgets/add');
	    $this->Acl->allow($group, 'controllers/Widgets/edit');*/
	}
	
	function actionIsAdmin() {return !empty($this->params['prefix']) && $this->params['prefix'] == 'admin';}
	
	function generalAjax($json_object=array()) {
		Configure::write('debug', 0);
		header('Content-Type: application/json;charset=UTF-8');
		$this->set('json_object',$json_object);
		$this->viewPath = 'ajax';
		$this->render('general_ajax');
	}

	function customControllerViewData() {
		$this_model = Inflector::singularize($this->name);
		$this_action = $this->action;
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
				if($this->loadModel($model)) {
					$this->set($set,$this->{$model}->find($type,$query));
				}
			}
		}
	}

	function minifyCss() {
		if($this->actionIsAdmin()) {
			$this->set('minify_css',$this->Minify->css(array(
				'css/admin/reset.css','css/admin/typefaces.css','css/admin/lenore.css','css/admin/widgets/hook_menu.css',
				'css/admin/widgets/sortable.css','css/admin/widgets/editable_text.css','css/admin/widgets/flag_toggle.css',
				'css/admin/widgets/dialog.css','css/admin/widgets/flash_messages.css','css/admin/handheld_large.css',
				'css/admin/tablet_netbooks.css','css/admin/desktop.css'
			)));
		}
	}

	function minifyJs() {
		if($this->actionIsAdmin()) {
			$this->set('min_js_head',$this->Minify->js(array(
				'js/lib/modernizr.js','js/lib/selectivizr.js','js/lib/flexie.js','js/lib/mediaqueries.js','js/jquery/jquery.js','js/jquery/ui/core.js',
				'js/jquery/ui/widget.js','js/jquery/ui/mouse.js', 'js/jquery/ui/bgiframe.js','js/jquery/ui/sortable.js','js/jquery/ui/dialog.js',
				'js/jquery/ui/position.js','js/jquery/lib/iphoneui.js','js/jquery/lib/editable_text.js','js/jquery/hook_menu.js','js/jquery/dux_tabs.js',
				'js/jquery/lib/flag_toggle.js','js/lib/ckeditor/lenore_load.js','dontpack1'=>'js/lib/ckeditor/ckeditor.js','dontpack2'=>'js/lib/ckeditor/adapters/jquery.js','js/admin/load_config.js'
			)));
		}
	}

	function setAltContentViewParams() {
		if(isset($this->params['alt_content']) && $this->params['alt_content']=='Rss') {
			$this->RequestHandler->renderAs($this,'rss');
			$this->RequestHandler->respondAs('Content-type: application/rss+xml;charset=UTF-8');
		} elseif(isset($this->params['alt_content']) && $this->params['alt_content']=='Xml') {
			$this->RequestHandler->renderAs($this,'xml');
			$this->RequestHandler->respondAs('xml');
		}
	}

	function setRequestHandlerViewVars() {
		if($this->RequestHandler->isAjax())
			$this->set('is_ajax',true);
		if($this->RequestHandler->isMobile())
			$this->set('is_mobile',true);
		else
			$this->set('is_mobile',false);
	}

	function setModDateHeader() {
		if(!empty($this->viewVars['mod_date_for_layout'])) $this->header('Last-Modified: '.$this->Time->toRSS($this->viewVars['mod_date_for_layout']));
	}

	function setCurrentTheme() {
		if($this->actionIsAdmin()) {
			$this->theme = 'admin';
		} elseif($template_theme = Configure::read('Moonlight.template_theme')) {
			$this->theme = $template_theme;
			if($custom_layout = Configure::read(Inflector::singularize($this->name).'custom_layout')) {
				$this->layout = $custom_layout;
			}
		}
	}
}
?>
