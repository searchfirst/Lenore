<?php
App::import('Vendor','simplepie');
class RssComponent extends Object {
	function fetchRss($url) {
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->set_output_encoding("UTF-8");
		$feed->enable_order_by_date(false);
		$feed->set_cache_location(Configure::read('Rss.cache_path'));
		$feed->init();
		return $feed->get_items();
	}	
}
?>
