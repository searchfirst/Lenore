<?php
class SlugRoute extends CakeRoute {
	function parse ($url) {
		$params = parent::parse($url);
		if (empty($params)) {
			return false;
		}
		$section_slugs = Cache::read('section_slugs', 'lenore');
		if (empty($section_slugs)) {
			App::import('Model', 'Section');
			$Section = new Section();
			$sections = $Section->find('all', array(
				'fields' => array('Section.slug'),
				'recursive' => -1
			));
			$section_slugs = array_flip(Set::extract('/Section/slug', $sections));
			Cache::write('section_slugs', $section_slugs, 'lenore');
		}
		$article_slugs = Cache::read('article_slugs', 'lenore');
		if (empty($article_slugs)) {
			App::import('Model', 'Article');
			$Article = new Article();
			$articles = $Article->find('all', array(
				'fields' => array('Article.slug'),
				'recursive' => -1
			));
			$article_slugs = array_flip(Set::extract('/Article/slug', $articles));
			Cache::write('article_slugs', $article_slugs, 'lenore');
		}
		if (!empty($params['section'])) {
			if (isset($article_slugs[$params['slug']]) && isset($section_slugs[$params['section']])) {
				$params['pass']['slug'] = $params['slug'];
				return $params;
			}
		}
		if (isset($section_slugs[$params['slug']])) {
			$params['pass']['slug'] = $params['slug'];
			return $params;
		}
		return false;
	}
}
