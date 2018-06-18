<?php
/*
This is the Resident controller
Retreive all articles that match current tags
*/
return function($site, $pages, $page) {
	$perpage  = $page->perpage()->int();
	$articles = $pages->children()
		->visible()
		->not($site->activePage())
		->flip();
		// ->paginate(($perpage >= 1)? $perpage : 5);
	// convert tags string (Aurele,Iris,etc.) to array
	$resident = $page->residents()->value();
	$resident__array = explode(',', $resident);
	// start a temp article list
	$articles_filtered = new Pages();
	// add matching articles
	foreach($resident__array as $filter) {
		$articles_filtered->add($articles->filterBy('residents', $filter,','));
	}
	// voila, we have a list populated with unique articles
	$articles = $articles_filtered
		->sortBy('datetime', 'desc');
		// ->paginate(($perpage >= 1)? $perpage : 5)
	return [
		'articles'   => $articles,
		'pagination' => $articles->pagination(),
		'resident'	 => $resident__array // not currently used
	];
};