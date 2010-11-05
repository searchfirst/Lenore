<?php
App::import('Vendor','phpthumb/phpthumb_class'); 
class ThumbsController extends AppController {
	var $name = 'Thumbs';
	var $components = null;
	var $actionHelpers = null;
	var $helpers = null;
	var $uses = null;
	var $layout = null;

	function beforeRender() {}
	
	function index() {
		if(count($this->params['pass'])) {
			$src = implode('/',array_slice($this->params['pass'],1));
			$action = $this->params['pass'][0];
			$phpThumb = new phpthumb();
			$sourceFilename = Configure::read('Resource.media_path').DS.$src;
			$cacheFilename = md5("$action$src");
			$phpThumb->src = $sourceFilename;
			$phpThumb->config_cache_directory = Configure::read('Thumb.cache_path').DS;
			$phpThumb->config_imagemagick_path = Configure::read('Thumb.imagemagick_path');
			$phpThumb->config_prefer_imagemagick = true;
			$phpThumb->config_output_format = 'jpeg';
			$phpThumb->config_error_die_on_error = false;
			$phpThumb->config_document_root = '';
			$phpThumb->config_cache_prefix = '';
			$phpThumb->config_cache_source_enabled = 1;
			$phpThumb->cache_filename = $phpThumb->config_cache_directory.$cacheFilename;
			$phpThumb->config_max_source_pixels = 5000000;
			$phpThumb->sia = implode('_',array_slice($this->params['pass'],1));
			$thumb_parameters = Configure::read('Thumb.media_parameters');
			
			if(in_array($action,array_keys($thumb_parameters))) {
				$thumb_vars = $thumb_parameters[$action];
				$phpThumb->cache_filename = $phpThumb->config_cache_directory.$cacheFilename;
				
				foreach ($thumb_vars as $ti=>$thumb_var) {
					$phpThumb->setParameter($ti,$thumb_var);
				}
				
				if(	file_exists($phpThumb->cache_filename) && (filemtime($phpThumb->src) < filemtime($phpThumb->cache_filename)) ) {
					$phpThumb->useRawIMoutput = true;
					$phpThumb->IMresizedData = file_get_contents($phpThumb->cache_filename);
					$this->set('phpthumb',$phpThumb);
				} elseif($phpThumb->GenerateThumbnail()) {
					$phpThumb->RenderToFile($phpThumb->cache_filename);
					$this->set('phpthumb',$phpThumb);
				} else {
					die('Error caching thumbnail <pre>'.print_r($phpThumb,true).'</pre>');
				}
			} else {
				$this->redirect(Configure::read('Resource.media_path')."/$src");
			}
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}
}
?>