<?php
class ContactsController extends AppController {

	var $name = 'Contacts';
	var $uses = array('Contact','Section','Category');
	var $helpers = array('TextAssistant','MediaAssistant');
	var $components = array('Email');
	var $pageTitle = 'Contact Us';

	function index() {
		$this->Contact->loadInfo(false);
		$contact_content = $this->Section->findBySlug('contact');
		$this->set('title_for_layout','Contact Us');
		if(!empty($contact_content['Section']['meta_description']) || !empty($contact_content['Section']['meta_keywords']))
			$this->set('metadata_for_layout',array(
				'description'=>$contact_content['Section']['meta_description'],
				'keywords'=>$contact_content['Section']['meta_keywords'])
			);
		$this->set('contact_content',$contact_content);
		if(isset($this->data['Contact'])) {
			if($this->Contact->create($this->data)) {
				$email_data = $this->data;
				$this->set('email_data',$email_data);
				$this->Email->to = Configure::read('Moonlight.webmaster_email');
				$this->Email->subject = !empty($this->data['Contact']['subject'])?$this->data['Contact']['subject']:"Enquiry from {$_SERVER['HTTP_HOST']}";
				if(!(empty($this->data['Contact']['name']) || empty($this->data['Contact']['email']))) {
					$this->Email->from = sprintf('%s <%s>',$this->data['Contact']['name'],$this->data['Contact']['email']);
				} else {
					$this->Email->from = Configure::read('Moonlight.webmaster_email');
				}
				$this->Email->template = 'contact';
				$this->Email->sendAs = 'both';
				if($this->Email->send()) $email_status = 'The email was sent';
				else $email_status = 'The email failed to send. Try again later';
				$this->Session->setFlash($email_status);
				$this->redirect($this->referer('/'));
			} else $this->Session->setFlash('Please correct the errors below');
		}
	}
}
?>