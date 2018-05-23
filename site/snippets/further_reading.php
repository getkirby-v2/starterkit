<?php
/*
this snippet returns a list of articles
random?
sorted by date?
same tag?
a curated selection?
	i.e. 1 about same residents, 1 newest, 2

*/

$articles =
	// SOURCE
	$page->siblings($self = false) // pages in folder, 'false' excludes current

	// $site->index() // all pages
	// ->not($site->activePage())

	// FILTER
	->visible()
	->filterBy('template', 'article')

	// TRANSFORM
	// ->sortBy('datetime', 'desc'); // sorted selection
	->shuffle(); // random selection

if (isset($limit)) {
	$articles = $articles->limit($limit);
}
?>

<pre>this is the further reading snippet</pre>

<nav>
	<h2>Further reading</h2>
	<ul>
		<?php foreach($articles as $article): ?>
			<li>
				<a href="<?= $article->url() ?>">
					<h3><?= $article->title()->html() ?></h3>
					<?php if($image = $article->images()->sortBy('sort', 'asc')->first()): $thumb = $image->crop(100, 100); ?>
						<img src="<?= $thumb->url() ?>" alt="Thumbnail for <?= $article->title()->html() ?>" />
					<?php endif ?>
				</a>
			</li>
		<?php endforeach ?>
	</ul>
</nav>
