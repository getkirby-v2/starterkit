<?php
/*
This is the resident controller
Retreive all articles that match either Current Affairs or Articles


*/
return function($site, $pages, $page) {
	// $perpage  = $page->perpage()->int();
	$articles = $page->children()
		->visible()
		->flip();
		// ->paginate(($perpage >= 1)? $perpage : 5);

	return [
		'articles'   => $articles
		// 'pagination' => $articles->pagination()
	];

};
