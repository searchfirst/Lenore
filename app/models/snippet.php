<?php
class Snippet extends AppModel {
    var $name = 'Snippet';
    var $validate = array(
		'title' => array('rule'=>'notEmpty','message'=>"Snippets need titles.")
	);
}