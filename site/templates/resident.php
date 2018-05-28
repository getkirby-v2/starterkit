<?php snippet('header') ?>
<pre>this is the resident template</pre>
<main class="main" role="main">
	<article>
		<header class="article-header">
			<h1><?= $page->title()->html() ?></h1>
			<figure>
				<?php if($image = $page->images()->sortBy('sort', 'asc')->first()): $thumb = $image->crop(240, 240); ?>
					<!-- <img src="<?= $thumb->url() ?>" alt="Thumbnail for <?= $page->title()->html() ?>" /> -->
					<img src="<?= $image->url() ?>" />
				<?php endif ?>
			</figure>
		</header>
		<div class="text">
			<?= $page->text()->kirbytext() ?>

		</div>
	</article>
	<?php snippet('list'); ?>
</main>

<?php snippet('footer') ?>
