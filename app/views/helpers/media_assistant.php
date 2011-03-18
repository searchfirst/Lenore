<?php
App::import('Vendor','phpthumb/phpthumb_config');
class MediaAssistantHelper extends Helper {
	var $helpers = array('Html');
	
	function __construct($settings=array()) {
		parent::__construct();
		$accept_types = $this->_generateAcceptTypesArray();
	}
	
	function downloadResourceLink($data,$htmlAttributes=null,$linkAttributes=null,$model=null) {
		if(!$model) $model = Inflector::underscore($this->params['models'][0]);
		else $model = Inflector::underscore($model);
		
		$media_string = "";
		switch($data['mime_type']) {
			case 'image/jpeg':
			case 'image/gif':
			case 'image/png':
				$media_string .= "<span class=\"download image\">";
				break;
			case 'application/pdf':
			case 'application/x-pdf':
			case 'image/pdf':
			case 'text/pdf':
				$media_string .= "<span class=\"download pdf\">";
				break;
			case 'application/msword':
			case 'application/word':
				$media_string .= "<span class=\"download word\">";
			case 'video/x-flv':
			case 'video/mpeg':
			case 'video/mp4':
			case 'video/mov':
				$media_string .= "<span class=\"download video\">";
				break;
			case 'application/zip':
			case 'application/x-compressed':
			case 'application/x-tar':
				$media_string .= "<span class=\"download archive\">";
				break;
			default:
				$media_string .= "<span class=\"download unknown\">";
				break;
		}
		$media_string .= $this->link($data,null,$model).' '.$data['description'];
		$media_string .= "</span>";
		return $media_string;
	}
	
	function link($data,$htmlAttributes=null,$model=null) {
		$model = ($model)?strtolower($model):strtolower($this->params['models'][0]);
		$media_filename = $data['slug'].'.'.$data['extension'];
		$attributes = ($htmlAttributes)?$this->generateHTMLAttributes($htmlAttributes):'';
		$media_string = sprintf('<a href="%s/%s/%s" %s>%s</a>',$this->webroot.Configure::read('Resource.web_root'),$model,$media_filename,$attributes,$data['title']);
		return $media_string;
	}

	function media($options=array()) {
		if(is_array($options) && !empty($options) && !empty($options['data'])) {
			$default_options = array(
				'html_attributes'=>array(),
				'conversion_parameter'=>null,
				'link'=>false,
				'link_attributes'=>array(),
				'model'=>null
			);
			$options = array_merge($default_options,$options);
			if (empty($options['html_attributes']['class']) && !empty($options['conversion_parameter'])) {
				$options['html_attributes']['class'] = $options['conversion_parameter'];
			}
			$options['model'] = Inflector::underscore($options['model']);
			if(Configure::read('Moonlight.use_html')) {
				$trailing_slash = ' /';
			} else {
				$trailing_slash = '';
			}
			$filename = sprintf('%s.%s',$options['data']['slug'],$options['data']['extension']);
			if($options['conversion_parameter'] && preg_match('/^image/',$options['data']['mime_type']) && in_array($options['conversion_parameter'],array_keys(Configure::read('Thumb.media_parameters')))) {
				$base_media_path = sprintf('/thumbs/%s/%s/',$options['conversion_parameter'],$options['model']);
			} else {
				$base_media_path = sprintf('%s/%s/',$this->webroot.Configure::read('Resource.web_root'),$options['model']);
			}
			switch($options['data']['mime_type']) {
				case 'image/jpeg':
				case 'image/gif':
				case 'image/png':
					//Check for ALT
					if(!isset($options['html_attributes']['alt'])) $options['html_attributes']['alt'] = ''; 
					$attributes = $this->generateHTMLAttributes($options['html_attributes'],$options['data']);
					$media_string = sprintf('<img src="%s%s" %s%s>',$base_media_path,$filename,$attributes,$trailing_slash);
					if($options['link'])
						$media_string = sprintf('<a href="%s/%s/%s" %s>%s</a>',$this->webroot.Configure::read('Resource.web_root'),$options['model'],$filename,$this->generateHTMLAttributes($options['link_attributes'],$options['data']),$media_string);
				break;
				case 'video/quicktime':
				case 'video/mpeg':
				case 'video/mp4':
				case 'video/x-ms-wmv':
					$media_string = sprintf('<video src="%s" controls preload></video>',$filename);
				break;
		}
		return $media_string;
		} elseif(!empty($options['data'])) { trigger_error('Invalid Argument provided to MediaAssistant::media() - Parameter was not an array or resource data was absent'); }
	}

	function generateHTMLAttributes($htmlAttributes,$data=false) {
		if($htmlAttributes==null) $htmlAttributes=array();
		$attribute_string = '';
		if(isset($data['title'])) $htmlAttributes['title'] = $data['title'];
		foreach($htmlAttributes as $attribute=>$value)
			if(!is_array($value))
				$attribute_string .= " $attribute=\"$value\"";
			else
				foreach($value as $sub_attribute=>$subvalue)
					$attribute_string .= " $sub_attribute=\"$sub_value\"";
		return $attribute_string;
	}
	
	function _generateAcceptTypesArray() {
		$accept_types_array = array();
		$types_pre_array = Configure::read('Resource.accept_types');
		foreach($types_pre_array as $type_item) {
			list($general,$specific) = explode('/',$type_item);
			$accept_types_array[$general][] = $specific;}
		return $accept_types_array;
	}
	
	function generateMediaLinkAttributes($media,$global_attributes=null) {
		if(empty($global_attributes)) $global_attributes = array();
		if(!empty($media)) {
			foreach($media as $media_item)
				$media_link_attr[] = array_merge($global_attributes,array("title"=>$media_item['title']));
			return $media_link_attr;
		} else return null;
	}
	
	function object($url) {
		if(Configure::read('Moonlight.use_html')) $close_tag = ">";
		else $close_tag = " />";
		$media_string = <<<MEDIA_STRING
<object type="application/x-shockwave-flash" data="$url">
<param name="movie" value="$url"$close_tag
<param name="wmode" value="transparent"$close_tag
</object>
MEDIA_STRING;
		return $media_string;
	}
}
?>
