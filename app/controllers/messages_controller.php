<?php
class MessagesController extends AppController {

	var $name = 'Messages';
	var $uses = array('Message','Section');
	var $helpers = array('TextAssistant','MediaAssistant');
	var $components = array('Notify');

	function index($type='contact-us') {
		$title = Inflector::humanize(str_replace('-','_',$type));
		if($this->Message->isValidType($type)) {
			$this->set('title_for_layout',$title);
			if(!empty($section['Section']['meta_description']) || !empty($section['Section']['meta_keywords'])) {
				$this->set('metadata_for_layout',array(
					'description'=>$section['Section']['meta_description'],'keywords'=>$section['Section']['meta_keywords']
				));
			}
			$section = $this->Section->find('first',array('conditions'=>array('Section.slug'=>$type)));
			$this->set('section',$section);
			if(!empty($this->data['Message'])) {
				$this->Message->getOptions($this->data['Message'],$type);
				if($this->Message->save($this->data)) {
					$this->Notify->send($this->data['Message']);
				} else {$this->Session->setFlash(__('There was an error saving your message. Check for errors and try again',true),'flash/default',array('class'=>'error'));}
			}
		} else {
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}
}