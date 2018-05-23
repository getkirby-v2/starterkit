<?php snippet('header') ?>
<pre>this is the resident template</pre>
<main class="main" role="main">
		<header>
			<h1><?= $page->title()->html() ?></h1>
			<figure>
				<?php if($image = $page->images()->sortBy('sort', 'asc')->first()): $thumb = $image->crop(240, 240); ?>
					<img src="<?= $thumb->url() ?>" alt="Thumbnail for <?= $page->title()->html() ?>" />
				<?php endif ?>
			</figure>
			<?= $page->text()->introduction() ?>
		</header>

		<?php snippet('list'); ?>
</main>

<?php snippet('footer') ?>
