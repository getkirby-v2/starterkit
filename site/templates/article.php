<?php snippet('header') ?>
<pre>this is the article template</pre>

<main class="main" role="main">
	<article>
		<header class="article-header">
			<h1><?= $page->title()->html() ?></h1>
			Published <?= $page->datetime('c') ?> by <?= $page->author() ?>
		</header>

		<?php snippet('coverimage', $page) ?>

		<div class="text">
			<?= $page->text()->kirbytext() ?>
		</div>

	</article>

	<?php	snippet('further_reading', ['limit' => 3]); ?>

</main>

<?php snippet('footer') ?>