<?php
/*
This list returns all articles related to a resident (original content)
*/

// get all new articles except the current
// $articles = $pages
// 	// ->articles() // omit this line to include auxiliary posts as well
// 	->children()
// 	->visible()
// 	->not($site->activePage())
// 	->flip();

// return articles from site which match $resident
// if (isset($resident)) {
//
// 	// convert tags string (Aurele,Iris,etc.) to array
// 	$resident__array = explode(',', $resident);
//
// 	// start a temp article list
// 	$articles_filtered = new Pages();
//
// 	// add matching articles
// 	foreach($resident__array as $filter) {
// 		$articles_filtered->add($articles->filterBy('residents', $filter,','));
// 	}
//
// 	// voila, we have a list populated with unique articles
// 	$articles = $articles_filtered;
// } else {
// 	// return child articles
// 	// $articles = $page->children()->visible();
// }

// limit list
// if (isset($limit)) {
// 	$articles = $articles->limit($limit);
// }

print_r( $articles );
/*
=======================	logic ends here/design starts here =======================
*/
?>

<pre>this is the list snippet </pre>
<section>
	<?php if($articles->count()): ?>
		<ul>
			<pre><?= $articles->count() . " items found" ?></pre>
			<?php foreach($articles as $article): ?>
				<li class="<?= $article->intendedTemplate() ?>">
					<a href="<?= $article->url() ?>">
						<!-- <p><?= $article->intendedTemplate() ?></p> -->
						<p><?= $article->datetime() ?></p>
						<h3><?= $article->title()->html() ?></h3>
						<?php if($image = $article->images()->sortBy('sort', 'asc')->first()): $thumb = $image->crop(720, 240); ?>
							<img src="<?= $thumb->url() ?>" alt="Thumbnail for <?= $article->title()->html() ?>" />
						<?php endif ?>
					</a>
					<?php print_r($article); ?>
				</li>
			<?php endforeach ?>
		</ul>
	<?php else: ?>
		<p>No associated items found.</p>
	<?php endif ?>
</section>
