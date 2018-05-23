<?php
/*
This is the Home controller
Retreive all articles that match either Current Affairs or Articles


*/
return function($site, $pages, $page) {
	$perpage  = $page->perpage()->int();
	// $articles = $page->children()
	// 	->visible()
	// 	->flip()
	// 	->paginate(($perpage >= 1)? $perpage : 5);


	// start a temp article list
	$articles_filtered = new Pages();

	// add matching articles
	foreach(array('current-affairs','articles') as $filter) {
		$articles_filtered->add(page($filter)->children()->visible());
	}

	$articles = $articles_filtered
		->sortBy('datetime', 'desc');
		// ->paginate(($perpage >= 1)? $perpage : 5)

	return [
		'articles'		=> $articles,
		'pagination'	=> $articles->pagination()
	];

};
