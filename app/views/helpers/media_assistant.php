<?php
App::import('Vendor','phpthumb/phpthumb_config');
class MediaAssistantHelper extends Helper {
	var $helpers = array('Html');
	
	function MediaAssistantHelper() {
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
				$media_string .= "<span class=\"resource_download resource_image\">";
				break;
			case 'application/pdf':
			case 'application/x-pdf':
			case 'image/pdf':
			case 'text/pdf':
				$media_string .= "<span class=\"resource_download resource_pdf\">";
				break;
			case 'application/msword':
			case 'application/word':
				$media_string .= "<span class=\"resource_download resource_word\">";
			case 'video/x-flv':
			case 'video/mpeg':
			case 'video/mp4':
			case 'video/mov':
				$media_string .= "<span class=\"resource_download resource_video\">";
				break;
			case 'application/zip':
			case 'application/x-compressed':
			case 'application/x-tar':
				$media_string .= "<span class=\"resource_download resource_archive\">";
				break;
			default:
				$media_string .= "<span class=\"resource_download resource_unknown\">";
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
		$media_string = "<a href=\"".Configure::read('Resource.web_root')."/$model/$media_filename\" $htmlAttributes>{$data['title']}</a>";
		return $media_string;
	}

	function mediaLink($data,$htmlAttributes=null,$conversionParameters=null,$link=false,$linkAttributes=null,$model=null) {
		if(Configure::read('Moonlight.use_html')) {
			$close_tag = ">";
		} else {
			$close_tag = " />";
		}
		$model = ($model)?strtolower($model):strtolower($this->params['models'][0]);
		if($linkAttributes==null) $linkAttributes = array();
		//conversion params is an optional string/integer to specify thumbs controller actions
		$media_filename = $data['slug'].'.'.$data['extension'];
		
		if(in_array($conversionParameters,array_keys(Configure::read('Thumb.media_parameters'))))
			$base_media_path = "/thumbs/$conversionParameters/$model/";
		else
			$base_media_path = Configure::read('Resource.web_root')."/$model/";

		switch($data['mime_type']) {
			case 'image/jpeg':
			case 'image/gif':
			case 'image/png':
				//Check for ALT
				if(!isset($htmlAttributes['alt'])) $htmlAttributes['alt'] = ''; 
					$attributes = $this->generateHTMLAttributes($htmlAttributes,$data);
/*				if(MOONLIGHT_IMAGE_USE_THICKBOX) {
					if(isset($linkAttributes['class']) && !empty($linkAttributes['class']))
						$linkAttributes['class'] = implode(' ',array($linkAttributes['class'],'thickbox'));
					else
						$linkAttributes['class'] = 'thickbox';}*/
				$media_string = "<img src=\"$base_media_path$media_filename\" $attributes$close_tag";
				if($link)
					$media_string = "<a href=\"".Configure::read('Resource.media_path')."/$model/$media_filename\" ".$this->generateHTMLAttributes($linkAttributes,$data).">".$media_string."</a>";
				break;
			case 'video/x-flv':
				$media_file_name = Configure::read('Resource.web_root')."/$model/{$data['slug']}.{$data['extension']}";
				$media_string = <<<MEDIA_STRING
<object type="application/x-shockwave-flash" data="/js/flowplayer/FlowPlayerWhite.swf" class="flowplayer">
<param name="allowScriptAccess" value="sameDomain"$close_tag
<param name="movie" value="/js/flowplayer/FlowPlayerWhite.swf"$close_tag
<param name="quality" value="high"$close_tag
<param name="scale" value="noScale"$close_tag
<param name="wmode" value="transparent"$close_tag
<param name="flashvars" value="config={configFileName: '/js/flowplayer/flowplayer.default.js', videoFile: '$media_file_name'}"$close_tag
</object>
MEDIA_STRING;
				break;
			case 'video/quicktime':
			case 'video/mpeg':
			case 'video/mp4':
			case 'video/x-ms-wmv':
				$media_file_name = Configure::read('Resource.web_root')."/$model/{$data['slug']}.{$data['extension']}";
				$media_string = <<<MEDIA_STRING
<object type="{$data['mime_type']}" data="$media_file_name">
<param name="src" value="$media_file_name"$close_tag
<param name="autostart" value="true"$close_tag
<param name="controller" value="true"$close_tag
<param name="scale" value="aspect"$close_tag
</object>
MEDIA_STRING;
				break;
		}
		return $media_string;		
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