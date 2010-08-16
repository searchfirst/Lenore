<?php
Router::connect('/', array('controller' => 'Facades', 'action' => 'index'));
Router::connect('/admin', array('controller' => 'AdminOverview', 'action' => 'index', 'admin' => true));
Router::connect("/admin/:controller", array('action' => 'index', 'prefix' => 'admin', 'admin' => true));
Router::connect("/admin/:controller/:action/*", array('prefix' => 'admin', 'admin' => true));
Router::connect('/products',array('controller'=>'Categories','action'=>'index'));
Router::connect('/products/:category/:slug',array('controller'=>'Products','action'=>'view'),array('category'=>'.+','slug'=>'.+','pass'=>array('slug')));
Router::connect('/products/*',array('controller'=>'Categories','action'=>'view'));
Router::connect('/thumbs/*', array('controller'=>'Thumbs','action'=>'index'));
Router::connect('/rss/:controller/', array('alt_content'=>'Rss','action'=>'index'));
Router::connect('/rss/:controller/*', array('alt_content'=>'Rss','action'=>'view'));
Router::connect('/ajax/:controller/:action/*',array('alt_content'=>'Ajax'));
Router::connect('/contact', array('controller' => 'Contacts', 'action' => 'index'));
Router::connect('/contact/:action', array('controller' => 'Contacts'));
Router::connect('/cart/:action',array('controller'=>'Carts'));
Router::connect('/search',array('controller'=>'Searches','action'=>'index'));
//Router::connect('/:section/:slug', array('controller'=>'Articles','action'=>'view'), array('section'=>'.+','slug'=>'.+'));
Router::connect('/:section/:slug', array('controller'=>'Articles','action'=>'view'), array('section'=>'(?!admin|plugin|groups|users)(.*)','slug'=>'.+'));
//Router::connect('/*', array('controller' => 'Sections','action' => 'view'),array('section'=>'(?!admin|plugin|groups|users)(.*)'));
Router::connect('/:slug', array('controller' => 'Sections','action' => 'view'),array('slug'=>'(?!admin|plugin|groups|users)(.*)','pass'=>array('slug')));
Router::connect('/:controller',array('action'=>'index'));
Router::connect('/:controller/:action',array());
?>