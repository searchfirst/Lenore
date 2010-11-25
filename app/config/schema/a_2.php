<?php 
/* SVN FILE: $Id$ */
/* App schema generated on: 2010-11-18 11:11:00 : 1290080700*/
class V09SnippetsSchema extends CakeSchema {
	var $name = 'V09Snippets';

	var $file = 'v09_snippets.php';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $aros = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $aros_acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'aro_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'aco_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
		'_create' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'_read' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'_update' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'_delete' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'ARO_ACO_KEY' => array('column' => array('aro_id', 'aco_id'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $articles = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'length' => 150),
		'slug' => array('type' => 'string', 'null' => false, 'length' => 150),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'section_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'order_by' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
		'inline_count' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3),
		'draft' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'featured' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'meta_description' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'meta_keywords' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $articles_resources = array(
		'article_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'primary'),
		'resource_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'primary'),
		'indexes' => array('PRIMARY' => array('column' => array('resource_id', 'article_id'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $categories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'length' => 150),
		'slug' => array('type' => 'string', 'null' => false, 'length' => 150),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'order_by' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
		'inline_count' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3),
		'draft' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'featured' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'meta_description' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'meta_keywords' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $categories_resources = array(
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'primary'),
		'resource_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'primary'),
		'indexes' => array('PRIMARY' => array('column' => array('category_id', 'resource_id'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $groups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
	var $products = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'length' => 150),
		'slug' => array('type' => 'string', 'null' => false, 'length' => 150),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'order_by' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
		'inline_count' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3),
		'draft' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'featured' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'meta_description' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'meta_keywords' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'options' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'price' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $products_resources = array(
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'primary'),
		'resource_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'primary'),
		'indexes' => array('PRIMARY' => array('column' => array('product_id', 'resource_id'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $protected_items = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'length' => 150, 'key' => 'index'),
		'slug' => array('type' => 'string', 'null' => false, 'length' => 150),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'protected_section_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'order_by' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
		'inline_count' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3),
		'draft' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
		'featured' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'title' => array('column' => array('title', 'description'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $protected_items_resources = array(
		'protected_item_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'primary'),
		'resource_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'primary'),
		'indexes' => array('PRIMARY' => array('column' => array('protected_item_id', 'resource_id'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $protected_sections = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'length' => 150, 'key' => 'index'),
		'slug' => array('type' => 'string', 'null' => false, 'length' => 150),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'order_by' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
		'inline_count' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3),
		'draft' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
		'featured' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
		'password' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'title' => array('column' => array('title', 'description'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $protected_sections_resources = array(
		'protected_section_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'primary'),
		'resource_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'primary'),
		'indexes' => array('PRIMARY' => array('column' => array('protected_section_id', 'resource_id'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $resources = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'length' => 150),
		'slug' => array('type' => 'string', 'null' => false, 'length' => 150),
		'description' => array('type' => 'string', 'null' => false),
		'type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
		'mime_type' => array('type' => 'string', 'null' => false, 'length' => 50),
		'extension' => array('type' => 'string', 'null' => false, 'length' => 10),
		'path' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'order_by' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $resources_sections = array(
		'section_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'primary'),
		'resource_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'key' => 'primary'),
		'indexes' => array('PRIMARY' => array('column' => array('section_id', 'resource_id'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $sections = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'length' => 150),
		'slug' => array('type' => 'string', 'null' => false, 'length' => 150),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'order_by' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
		'inline_count' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3),
		'draft' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'featured' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'meta_description' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'meta_keywords' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'articles_enabled' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $snippets = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'slug' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'content' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);
	var $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'unique'),
		'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'username' => array('column' => 'username', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
}
?>