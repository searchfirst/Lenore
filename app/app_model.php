<?php
class AppModel extends Model {

	function afterSave() {
		if(($order_id = $this->getLastInsertId()) && (!isset($this->already_saved))) {
			$this->already_saved = true;
			$this->saveField('order_by',$order_id);
		}
	}
	
	function afterDelete() {
		$resource = new Resource;
		if(!empty(Resource::$delete_list) && $this->name!='Resource')
			foreach(Resource::$delete_list as $res)
				$resource->del($res);
		return true;
	}
	
	function beforeDelete() {
		if($this->name!=='Resource') {
			$model = $this->findById($this->id);
			$delete_list = array();
			foreach($model['Resource'] as $resource) $delete_list[] = $resource['id'];
			foreach($model['Decorative'] as $resource) $delete_list[] = $resource['id'];
			foreach($model['Downloadable'] as $resource) $delete_list[] = $resource['id'];
			Resource::setDeleteList($delete_list);
		}
		return true;
	}
	
	function beforeValidate() {
		$this->sanitiseData();
		$this->setInlineCount();
		$this->handleFileUploads();
		return true;
	}
	
	/* Description Functions (Preparation of the description for formatting) */
	
	function sanitiseData() {
		if(!empty($this->data['description'])) {
			$this->data['description'] = trim(strip_tags($this->data['description'],Configure::read('TextAssistant.permitted_html_elements')));
		}
		if(!empty($this->data['title'])) {
			$this->data['title'] = trim(strip_tags($this->data['title']));
		}
	}
	
	function setInlineCount() {
		if(isset($this->data[$this->name]['description'])) {
			if($inline_text_count = $this->handleInlineResourceText()) {
				$this->data[$this->name]['inline_count']=count($inline_text_count);
				$GLOBALS['moonlight_inline_count_set'] = 1;
			} else $this->data[$this->name]['inline_count'] = 0;
		}
	}
	
	function handleInlineResourceText() { //returns an array of data or false if no resource text found
		if(!empty($this->data[$this->name]['description'])) {
			if(preg_match_all('/{\[(media)\]([^\[\]{}]*)}/',$this->data[$this->name]['description'],$matches)) {
				$res_count = count($matches[0]);
				for($i=0;$i<$res_count;$i++)
					$inline_resources[] = array('type'=>$matches[1][$i],'content'=>$matches[2][$i]);
				return $inline_resources;
			}
		}
		return false;
	}
	
	/* End of Description Functions */
	
	/* File Upload Functions */
	
	public function handleFileUploads() {
		if(!empty($this->data['Resource'])) {
			$resources['Resource']['Resource'] = $this->getExistingResourceIds();
			foreach($this->data['Resource'] as $x => $resource) {
				if($resource['type']==Resource::$types['Decorative'] && $this->alreadyHasDeco()) {
					$this->fileUploadError('has_deco');
				} else {
					if($resource['file'][error]==UPLOAD_ERR_OK) {
						$this->repairMimeTypes($resource['type'],$resource['name']);
						$resource['mime_type'] = $resource['file']['type'];
						$resource['extension'] = $this->getExtension($resource['file']['type'],$resource['file']['name']);
						$resource['slug'] = $this->Resource->getUniqueSlug($this->getParentTitle($x));
						$resource['path'] = Configure::read('Resource.media_path').DS.Inflector::underscore($this->name).DS;
						$resource['type'] = $resource['file']['type'];

						if($this->moveUpload($resource)) {
							unset($resource_data['file']);
							if($this->Resource->save(array('Resource'=>$resource_data))) {
								$resources['Resource']['Resource'][] = $this->Resource->getLastInsertId();
							} else {
								$this->fileUploadError('save_error');
							}
						} else {
							$this->fileUploadError('move_file');
						}

//						if($this->_moveFileuploadFile(0,$resource_data) && $this->Resource->save($resource_data)) {
//							$this->data['Resource']['Resource'][] = (string) ($last_id = $this->Resource->getLastInsertId());
						
					}
				}
			}
		}
	}
	
	function _handleFileUploads() {
		if(!empty($_FILES['Fileupload']) && !empty($_FILES['Fileupload']['tmp_name'][0])) {
			$count_uploads = $this->_countUploads(); //count the number of files uploaded
			if( ($count_uploads==1) && ($this->data['Fileupload']['type'][0]==MOONLIGHT_RESTYPE_DECO) ) {
				if($this->_alreadyHasDeco()) {
					$this->_registerFileuploadError('You are uploading a new decorative image when this item already has one');
				} else {
					$this->data['Resource']['Resource'] = $this->_prepareExistingResourceIds();
					if($resource_data = $this->_prepareResourceData(0)) {
						if($this->_moveFileuploadFile(0,$resource_data) && $this->Resource->save($resource_data)) {
							$this->data['Resource']['Resource'][] = (string) ($last_id = $this->Resource->getLastInsertId());
						} else {
							$this->_registerFileuploadError('Error moving file. Serious error. Contact your system administrator.');
						}
					} else {
						$this->_registerFileuploadError("Error with file. Check it's a valid media file of the correct size and dimensions.");
					}
				}
			} elseif($count_uploads>=1) {
				$this->data['Resource']['Resource'] = $this->_prepareExistingResourceIds();
				for($fi=0;$fi<$count_uploads;$fi++) {
					if($resource_data = $this->_prepareResourceData($fi)) {
						$new_resource = new Resource;
						if($this->_moveFileuploadFile($fi,$resource_data) && $new_resource->save($resource_data)) {
							$this->data['Resource']['Resource'][] = (string) ($last_id = $new_resource->getLastInsertId());
							$new_resource->create();
						} else {
							$this->_registerFileuploadError('Error moving file. Serious error. Contact your system administrator.');
						}						
					} else {
						$this->_registerFileuploadError("Error with file. Check it's a valid media file of the correct size and dimensions.");
					}
				}
			} else {
				$this->_registerFileuploadError('General file upload error.');
			}
		}		
	}
	
	function _prepareResourceData($fi) { //where fi is the index of the _FILES => Fileupload array
		if($_FILES['Fileupload']['error'][$fi] == UPLOAD_ERR_OK) {
			$fresh_slug_search = new Resource;
			//Repair Uknown mimetypes
			$this->_repairMimeTypes($_FILES['Fileupload']['type'][$fi],$_FILES['Fileupload']['name'][$fi]);
			$resource_data = array(
				'mime_type' => $_FILES['Fileupload']['type'][$fi],
				'extension' => $this->_getUploadExtension($_FILES['Fileupload']['type'][$fi],$_FILES['Fileupload']['name'][$fi]),
				'slug' => $this->Resource->getUniqueSlug($this->_getParentTitle($fi)),
				'path' => MOONLIGHT_MEDIA_PATH.Inflector::underscore($this->name).DS,
				'type' => $this->data['Fileupload']['type'][$fi]
			);
			if(isset($this->data['Fileupload']['title'][$fi])) {
				$resource_data['title'] = $this->data['Fileupload']['title'][$fi];
			}
			if(isset($this->data['Fileupload']['description'][$fi])) 
				$resource_data['description'] = $this->data['Fileupload']['description'][$fi];		
			if($this->_mimeTypeAcceptable($resource_data['mime_type'],$this->data['Fileupload']['type'][$fi])) //eventually do some image dimensions checking with _imageDimensionsAcceptable($fi)
				return $resource_data;
			else return false;
		}
		else return false;
	}
	
	function getExistingResourceIds() {
		$resource_ids = array();
		if(!empty($this->data[$this->name]['id'])) {
			$model = $this->find('first',array(
				'conditions'=>array("{$this->name}.id"=>$this->data[$this->name]['id']),
				'recursive'=>1
			));
			if(isset($model['Decorative']))
				foreach($model['Decorative'] as $item)
					$resource_ids[] = $item['id'];
			if(isset($model['Resource']))
				foreach($model['Resource'] as $item)
					$resource_ids[] = $item['id'];
			if(isset($model['Downloadable']))
				foreach($model['Downloadable'] as $item)
					$resource_ids[] = $item['id'];
		}
		return $resource_ids;
	}
	
	function _prepareExistingResourceIds() {
		$resource_ids = array();
		if(!empty($this->id)) {
			$model = $this->findById($this->id);
			if(isset($model['Decorative']))
				foreach($model['Decorative'] as $resource_item)
					$resource_ids[] = $resource_item['id'];
			if(isset($model['Resource']))
				foreach($model['Resource'] as $resource_item)
					$resource_ids[] = $resource_item['id'];
			if(isset($model['Downloadable']))
				foreach($model['Downloadable'] as $resource_item)
					$resource_ids[] = $resource_item['id'];
		}
		return $resource_ids;
	}
	
	public function fileUploadError($key) {
		$upload_errors = array(
			'has_deco' => 'You are uploading a new decorative image when this item already has one.',
			'move_file' => 'Error moving file. Serious error. Contact support.',
			'file_error' => 'Error with file. Check it\'s a valid media file of the correct size and dimensions.',
			'save_error' => 'Error saving a Resource.',
			'unknown' => 'General file upload error. Contact support.'
		);
		if(in_array($upload_errors,$key)) {
			Session::setFlash($upload_errors[$key]);
			return true;
		} else {
			return false;
		}
	}
	
	function _registerFileuploadError($errortype) {
		$GLOBALS['Fileupload_error'] = $errortype;
	}
	
	public function repairMimeTypes(&$fileupload_type,$filename) {
		if($fileupload_type=='image/pjpeg') {
			$fileupload_type = 'image/jpeg';
		} elseif($fileupload_type=='application/octet-stream') {
			switch(pathinfo($filename,PATHINFO_EXTENSION)) {
				case 'flv':
					$fileupload_type = 'video/x-flv';
					break;
				default:
					$fileupload_type = 'application/octet-stream';
					break;
			}
		}
	}
	/*
	function repairMimeTypes(&$fileupload_type,$filename) {
		//TODO: Parse the mimetype, determine if it is erroneous and change the mimetype
		//TODO: move the IE handling over to this function
		if($fileupload_type=='image/pjpeg') {
			$fileupload_type = 'image/jpeg';
		} elseif($fileupload_type=='application/octet-stream') {
			switch(pathinfo($filename,PATHINFO_EXTENSION)) {
				case 'flv':
					$fileupload_type = 'video/x-flv';
					break;
				default:
					$fileupload_type = 'application/octet-stream';
					break;
			}
		}
	}
	*/
	function moveUpload($resource_data) {
		$tmp_file = $resource_data['file']['tmp_name'];
		$new_file = "{$resource_data['path']}{$resource_data['slug']}.{$resource_data['extension']}";
		return move_uploaded_file($tmp_file,$new_file);
	}
	
	function _moveFileuploadFile($fi,$resource_data) {
		$uploaded_file = $_FILES['Fileupload']['tmp_name'][$fi];
		$moved_file = $resource_data['path'].$resource_data['slug'].'.'.$resource_data['extension'];
		return move_uploaded_file($uploaded_file,$moved_file);
	}
	
	function countUploads() {
		$upload_count = 0;
		foreach($this->data['Resource'] as $resource)
			if(!empty($resource['file_upload']['error']) && $resource['file_upload']['error']!=4)
				$upload_count++;
		return $upload_count;
	}
	
	function _countUploads() {
		$upload_count = 0;
		foreach($_FILES['Fileupload']['error'] as $error_code) if($error_code!=4) $upload_count++;
		return $upload_count;
	}
	
	function _imageDimensionsAcceptable($fi) {
		
	}
	
	function _mimeTypeAcceptable($mimetype,$resource_type) {
		$decorative_mime_types = array(
			'image/jpeg' => 'jpg','image/png' => 'png','image/gif' => 'gif'
		);
		$inline_mime_types = array(
			'image/jpeg' => 'jpg','image/png' => 'png',
			'image/gif' => 'gif','video/x-flv' => 'flv'
		);
		$downloadable_mime_types = array(
			'image/jpeg' => 'jpg','image/png' => 'png',
			'image/gif' => 'gif','video/x-flv' => 'flv',
			'video/mp4' => 'mp4','video/quicktime' => 'mov',
			'video/mpeg' => 'mpg','application/pdf' => 'pdf',
			'application/msword' => 'doc','application/zip' => 'zip'
		);
		switch($resource_type) {
			case MOONLIGHT_RESTYPE_DECO:
				if(in_array($mimetype,array_keys($decorative_mime_types))) return true;
				break;
			case MOONLIGHT_RESTYPE_INLINE:
				if(in_array($mimetype,array_keys($inline_mime_types))) return true;
				break;
			case MOONLIGHT_RESTYPE_DOWNLOAD:
				if(in_array($mimetype,array_keys($downloadable_mime_types))) return true;
				break;
			default:
				return false;
		}
	}
	
	function getParentTitle($x) {
		$t = 'Untitled Media';
		if(!empty($this->data['Resource'][$x]['title'])) {
			$t = $this->data['Resource'][$x]['title'];
		} elseif(!empty($this->data[$this->name]['title'])) {
			$t = $this->data[$this->name]['title'];
		} elseif($t_fromdb = $this->field('title')) {
			$t = $t_fromdb;
		}
		return $t;
	}
	
	function _getParentTitle ($uploadindex = 0) {
		if(isset($this->data['Fileupload']['title'][$uploadindex])  && !empty($this->data['Fileupload']['title'][$uploadindex]))
			return $this->data['Fileupload']['title'][$uploadindex];
		elseif(isset($this->data[$this->name]['title'])) return $this->data[$this->name]['title'];
		elseif($title_from_parent = $this->field('title')) return $this->field('title');
		else return 'Untitled Media';
	}

	public function setUploadExtension($mimetype,$filename) {
		$arr_mimetype = array('image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif');
		if(isset($arr_mimetype[$mimetype])) {
			return $arr_mimetype[$mimetype];
		} else {
			return (($ext_from_file = pathinfo($filename,PATHINFO_EXTENSION))!='')?$ext_from_file:'';
		}
	}
	
	function _getUploadExtension($mimetype, $filename) {
		$arr_mimetype = array(
			'image/jpeg'	=>	'jpg',
			'image/png'		=>	'png',
			'image/gif'		=>	'gif'
		);
		if(isset($arr_mimetype[$mimetype])) return $arr_mimetype[$mimetype];
		else return (($ext_from_file = pathinfo($filename,PATHINFO_EXTENSION))!='')?$ext_from_file:'';
	}
	
	public function alreadyHasDeco() {
		if(empty($this->id)) {
			return false;
		} else {
			$check_for_deco = $this->find('first',array(
				'conditions'=>array("{$this->name}.id"=>$this->id),'recursive'=>1
			));
			if(!empty($check_for_deco['Decorative'])) {
				foreach($check_for_deco['Decorative'] as $resource) {
					if($resource['type']==Resource::$types['Decorative']) {
						return true;
					}
				}
			} else {
				return false;
			}
		}
		return false;
	}
	
	function _alreadyHasDeco() {
		if(empty($this->id))
			return false;
		else {
			$check_for_deco = $this->findById($this->id);
			foreach($check_for_deco['Decorative'] as $c_resource)
				if($c_resource['type']==MOONLIGHT_RESTYPE_DECO)
					return true;
		}
		return false;
	}
	
	function _noFileIsDeco($filelist) {
		$no_deco_file_present = true;
		foreach($filelist['type'] as $filetype)
			if($filetype==MOONLIGHT_RESTYPE_DECO)
				$no_deco_file_present = false;
		return $no_deco_file_present;
	}
	
	function swapFieldData($rowId1,$rowId2,$fieldname) {
		if( ($field1data = $this->field($fieldname,"{$this->name}.id=$rowId1")) &&
			($field2data = $this->field($fieldname,"{$this->name}.id=$rowId2")) )
				if( ($this->save(array("id"=>$rowId1,$fieldname=>$field2data))) &&
					($this->save(array("id"=>$rowId2,$fieldname=>$field1data))) )
					return true;
				else
					return false;
		else return false;
	}
	/* End of File Upload functions*/	
	
	function getUniqueSlug($string, $field="slug") {
		// Build URL
		$currentUrl = $this->_getStringAsSlug($string);
		// Look for same URL, if so try until we find a unique one
		
		$conditions = array($this->name . '.' . $field => 'LIKE ' . $currentUrl . '%');
		$result = $this->findAll($conditions, $this->name . '.*', null);
		
		if ($result !== false && count($result) > 0) {
			$sameUrls = array();
			foreach($result as $record)
			    $sameUrls[] = $record[$this->name][$field];
		}
		
		if(($this->name=='Resource') && isset($GLOBALS['MOONLIGHT_RESOURCE_PREV_SLUGS'])) {
			if(!isset($sameUrls)) $sameUrls = array();
			$sameUrls = array_merge($sameUrls,$GLOBALS['MOONLIGHT_RESOURCE_PREV_SLUGS']);
			}		
		
		if (isset($sameUrls) && count($sameUrls) > 0) {
			$currentBegginingUrl = $currentUrl;
			$currentIndex = 1;
		    while($currentIndex > 0) {
				if (!in_array($currentBegginingUrl . '-' . $currentIndex, $sameUrls)) {
				    $currentUrl = $currentBegginingUrl . '-' . $currentIndex;
				    $currentIndex = -1;
				}
				$currentIndex++;
			}
		}
		if($this->name=='Resource') $GLOBALS['MOONLIGHT_RESOURCE_PREV_SLUGS'][] = $currentUrl;
		return $currentUrl;
	}

	function _getStringAsSlug($string) {
		// Define the maximum number of characters allowed as part of the slug
		$currentMaximumSlugLength = 100;
				
		// Any non valid characters will be treated as _, also remove duplicate _
		$bad = array('Š','Ž','š','ž','Ÿ','À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ñ',
		'Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','à','á','â','ã','ä','å','ç','è','é','ê',
		'ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ',
		'Þ','þ','Ð','ð','ß','Œ','œ','Æ','æ','µ',
		'”',"'",'“','”',"\n","\r",'_');
		$good = array('S','Z','s','z','Y','A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','N',
		'O','O','O','O','O','O','U','U','U','U','Y','a','a','a','a','a','a','c','e','e','e',
		'e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y',
		'TH','th','DH','dh','ss','OE','oe','AE','ae','u',
		'','','','','','','-');
		$string = trim(str_replace($bad, $good, $string));
		
		$bad_reg = array('/\s+/','/[^A-Za-z0-9\-]/');
		$good_reg = array('-','');
		$string = preg_replace($bad_reg, $good_reg, $string);

		// Cut at a specified length
		if (strlen($string) > $currentMaximumSlugLength)
			$string = substr($string, 0, $currentMaximumSlugLength);
		
		// Remove beggining and ending signs
		$string = preg_replace('/_$/i', '', $string);
		$string = preg_replace('/^_/i', '', $string);
		
		$string = str_replace(array('----','---','--'),array('-','-','-'),$string);
		return strtolower($string);
	}

	function fulltext($q=null,$fields=null,$order=null,$limit=null,$page=null) {
		if (is_array($fields))
			$f = $fields;
		elseif ($fields)
			$f = array($fields);
		else
			$f = array('*');

		$fields_string = implode(', ',$f);
		$order_string = $order?" ORDER BY {$order}":"";
		$limit_string = $limit?(" LIMIT ".($page?$limit*($page-1):"0").",{$limit}"):"";

		$query = $this->query("SELECT id FROM {$this->tablePrefix}{$this->table} WHERE MATCH ($fields_string) AGAINST ('$q' IN BOOLEAN MODE)$order_string$limit_string");
		if(!empty($query)) foreach($query as $result) $results[] = $this->findById($result[$this->tablePrefix.$this->table]['id']);
		return isset($results)?$results:array();
	}
}
?>