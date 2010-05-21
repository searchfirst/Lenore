<?php
class ProductOptionHelper extends Helper {
	function getOptionsArray($options) {
		$options_array = array();
		if(!empty($options)) {
			$options_lines = explode("\n",$options);
			foreach($options_lines as $option_line) {
				$vars = explode(',',$option_line);				
				foreach($vars as $i=>$var) {
					if($i==0){
						$options_array[$var] = array();
						$title = $var;
					} else $options_array[$title][] = $var;
				}
			}
		}
		return $options_array;
	}
}
?>