<?php
class MenuHelper extends Helper {
	var $helpers = array('TextAssistant');

	function __construct() {
	}

	function isCurrent($slug,$cp) {
		return ($slug==$cp);
	}

	private function sluggify($string) {
		$string = str_replace(array('/','_'),'-',preg_replace('/^\/(.*)/','$1',$string));
		return $string;
	}

	function makeMenu($menu_array,$options=Array()) {
		if(is_array($menu_array)) {
			$d_options = array(
				'omissions'=>array(),
				'current_page'=>$this->sluggify($this->params['url']['url']),
				'slug_prefix'=>''
			);
			$options = array_merge($d_options,$options);
			$output_string = "<ul>\n";
			foreach($menu_array as $slug=>$title) {
				$full_slug = "{$options['slug_prefix']}/{$slug}";
				$c_slug = $this->sluggify($full_slug);
				if(!in_array($slug,$options['omissions'])) {
					$output_string .= "<li";
					if($this->isCurrent($c_slug,$options['current_page'])) $output_string .= " class=\"current\"";
					$output_string .= ">".$this->TextAssistant->link($title,"/$full_slug")."</li>\n";
				}
			}
			$output_string .= "</ul>";
			return $output_string;
		} else {
			trigger_error('MenuHelper::makemenu() called with a non array for $menu_array');
			return false;
		}
	}
}
?>