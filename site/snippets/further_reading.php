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

<nav class="further-reading">
	<h2>Further reading</h2>
	<ul>
		<?php foreach($articles as $article): ?>
			<li>
				<div class="card--title">
					<a href="<?= $article->url() ?>">
						<h3><?= $article->title()->html() ?></h3>
						<?php // image check
							$image = $article->image($article->coverimage());
							if($image):
						?>
							<div class="card--image" style="background-image: url(<?php echo $image->url() ?>); background-position: <?php echo $image->focusPercentageX() ?>% <?php echo $image->focusPercentageY() ?>%;">
						<?php else: ?>
							<div class="card--image">
						<?php endif ?>
							</div>
						</a>
				</div>
				<?php if(! $article->teaser()->empty() ): ?>
					<div class="card--main">
						<p><?= $article->teaser() ?></p>
					</div>
				<?php elseif(! $article->text()->empty() ): ?>
					<div class="card--main">
						<p><?= excerpt($article->text(), 300) ?></p>
					</div>
				<?php else: ?>
				<?php endif ?>

				<!--
				<?php if(! $article->datetime()->empty() ): ?>
					<div class="card--infobox">
						<p>Published: <?= $article->datetime() ?></p>
					</div>
				<?php endif ?>
				<?php if(! $article->author()->empty() ): ?>
					<div class="card--infobox">
						<p>Author: <?= $article->author() ?></p>
					</div>
				<?php endif ?>
			-->
			</li>
		<?php endforeach ?>
	</ul>
</nav>