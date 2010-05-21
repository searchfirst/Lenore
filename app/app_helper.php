<?php
class AppHelper extends Helper {
	function __construct() {
		if(Configure::read('Moonlight.use_html'))
			foreach ($this->tags as $tag => $html)
//				$this->tags[$tag] = preg_replace('#\s*\%s/>#','>',$html);
				$this->tags[$tag] = r('/>', '>', $html);
	}
}
?>