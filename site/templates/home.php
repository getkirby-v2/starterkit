<?php snippet('header') ?>
<pre>home template</pre>
<main class="main" role="main">

	<header class="wrap">
		<h1><?= $page->title()->html() ?></h1>
		<div class="intro text">
			<?= $page->introduction()->kirbytext() ?>
		</div>
	</header>

	<pre>
		<?php print_r( $articles ); ?>
	</pre>

	<?php snippet('list') ?>

</main>

<?php snippet('footer') ?>