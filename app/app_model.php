<?php
class AppModel extends Model {
	function afterDelete() {
		$resource = new Resource;
		if(!empty(Resource::$delete_list) && $this->name!='Resource')
			foreach(Resource::$delete_list as $res)
				$resource->delete($res);
		return true;
	}

	function beforeSave() {
		if($this->name!='Resource' && empty($this->data[$this->name]['id']) && $this->hasField('slug'))
			$this->data[$this->name]['slug'] = $this->getUniqueSlug($this->data[$this->name]['title']);
		return true;
	}

	function rebindModel($params, $reset=false) {
		$unbind = array();
		$newBindings = array();
		foreach ($params as $assoc => $models) {
			foreach ($models as $model => $settings) {
				if (!empty($this->{$assoc}[$model])) {
					$newBindings[$assoc][$model] = array_merge($this->{$assoc}[$model],$settings);
					$unbind[$assoc][] = $model;
				}
			}
		}
		$this->unbindModel($unbind, $reset);
		$this->bindModel($newBindings, $reset);
	}

	function beforeDelete($cascade) {
		if($this->name!=='Resource') {
			$model = $this->findById($this->id);
			$delete_list = array();
			if(!empty($model['Resource'])) foreach($model['Resource'] as $resource) $delete_list[] = $resource['id'];
			if(!empty($model['Resource'])) foreach($model['Decorative'] as $resource) $delete_list[] = $resource['id'];
			if(!empty($model['Resource'])) foreach($model['Downloadable'] as $resource) $delete_list[] = $resource['id'];
			Resource::setDeleteList($delete_list);
		}
		return true;
	}
	
	function beforeValidate() {
		$this->sanitiseData();
		if($this->name!='Resource') {
			$this->setInlineCount();
 			$this->handleFileUploads();
		}
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
	
	private function validFileArray(&$file_array) {
		if(!empty($file_array['file']) && is_array($file_array['file'])) return true;
		return false;
	}
	
	public function handleFileUploads() {
		if(!empty($this->data['Resource'])) {
			//$resources = $this->getExistingResourceIds();
			// In 1.3, the previous line doesn't seem necessary and, in fact, breaks image handling
			// One of these days I'll rip this crap code out and just do it the Cake way.
			$resources = array();
			foreach($this->data['Resource'] as $x => $resource) {
				if($this->validFileArray($resource) && $resource['file']['error']==UPLOAD_ERR_OK) {
					if($resource['type']==Resource::$types['Decorative'] && $this->alreadyHasDeco()) {
						$this->fileUploadError('has_deco');
					} else {
						$resource['mime_type'] = $this->repairMimeTypes($resource['file']['type'],$resource['file']['name']);
						$resource['extension'] = $this->getExtension($resource['file']['type'],$resource['file']['name']);
						$resource['slug'] = $this->Resource->getUniqueSlug($this->getParentTitle($x));
						$resource['path'] = Configure::read('Resource.media_path').DS.Inflector::underscore($this->name).DS;

						if($this->moveUpload($resource)) {
							$this->Resource->create();
							if($this->Resource->save(array('Resource'=>$resource))) {
								$resources[] = $this->Resource->id;
							} else {
								$this->fileUploadError('save_error');
							}
						} else {
							$this->fileUploadError('move_file');
						}
					}
				}
			}
			if(!empty($resources)) {
				$this->data['Resource'] = array('Resource'=>$resources);
			} else {
				unset($this->data['Resource']);
			}
		}
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
	
	public function fileUploadError($key) {
		$upload_errors = array(
			'has_deco' => 'You are uploading a new decorative image when this item already has one.',
			'move_file' => 'Error moving file. Serious error. Contact support.',
			'file_error' => 'Error with file. Check it\'s a valid media file of the correct size and dimensions.',
			'save_error' => 'Error saving a Resource.',
			'unknown' => 'General file upload error. Contact support.'
		);
		if(in_array($key,$upload_errors)) {
			Session::setFlash($upload_errors[$key]);
			return true;
		} else {
			Session::setFlash($upload_errors['unknown']);
			return false;
		}
	}
	
	public function repairMimeTypes($fileupload_type,$filename) {
		if($fileupload_type=='image/pjpeg') {
			$fileupload_type = 'image/jpeg';
		} elseif($fileupload_type=='application/octet-stream') {
			$pathinfo_ext = pathinfo($filename,PATHINFO_EXTENSION);
			if ($pathinfo_ext == 'flv') {
				$fileupload_type = 'video/x-flv';
			}
		}
		return $fileupload_type;
	}

	function moveUpload($resource_data) {
		$tmp_file = $resource_data['file']['tmp_name'];
		$new_file = "{$resource_data['path']}{$resource_data['slug']}.{$resource_data['extension']}";
		return move_uploaded_file($tmp_file,$new_file);
	}
	
	function countUploads() {
		$upload_count = 0;
		foreach($this->data['Resource'] as $resource)
			if(!empty($resource['file_upload']['error']) && $resource['file_upload']['error']!=4)
				$upload_count++;
		return $upload_count;
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

	public function setUploadExtension($mimetype,$filename) {
		$arr_mimetype = array('image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif');
		if(isset($arr_mimetype[$mimetype])) {
			return $arr_mimetype[$mimetype];
		} else {
			return (($ext_from_file = pathinfo($filename,PATHINFO_EXTENSION))!='')?$ext_from_file:'';
		}
	}
	
	function getExtension($mimetype,$filename) {
		$mimetypes = array(
			'image/jpeg'	=>	'jpg',
			'image/png'		=>	'png',
			'image/gif'		=>	'gif'
		);
		if (isset($mimetypes[$mimetype])) {
			return $mimetypes[$mimetype];
		} else {
			$deduced_mimetype = strtolower(pathinfo($filename,PATHINFO_EXTENSION));
			return !empty($deduced_mimetype) ? $deduced_mimetype : '';
		}
	}
	
	function alreadyHasDeco() {
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
		$current_url = strtolower(Inflector::slug($string,'-'));
		
		$conditions = array("{$this->name}.$field LIKE"=>"$current_url%");
		$result = $this->find('all',array('conditions'=>$conditions,'recursive'=>0));
		if ($result !== false && count($result) > 0) {
			$matching_slugs = array();
			foreach($result as $record)
			    $matching_slugs[] = $record[$this->name][$field];
		}
		
		if (isset($matching_slugs) && count($matching_slugs) > 0) {
			$stem = $current_url;
			$x=1;
		    while($x>0) {
				if (!in_array("$stem-$x", $matching_slugs)) {
				    $current_url = "$stem-$x";
				    $x=-1;
				}
				$x++;
			}
		}
		return $current_url;
	}

	function ajaxFlagArray($id,$status) {
		$this->recursive = 0;
		$model = $this->read(null,$id);
		return array(
			'status' => $status,
			'flag_text' => array(
				'draft' => $model[$this->name]['draft']?'Draft':'Published',
				'featured' => $model[$this->name]['featured']?'Featured':'Not Featured'
			),
			$this->name => $model[$this->name]
		);
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
