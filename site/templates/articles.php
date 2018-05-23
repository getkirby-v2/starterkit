<?php snippet('header') ?>
<pre>articles template</pre>
<main class="main" role="main">

	<header class="wrap">
		<h1><?= $page->title()->html() ?></h1>
		<div class="intro text">
			<?= $page->introduction()->kirbytext() ?>
		</div>
	</header>

	<?php snippet('list') ?>

</main>

<?php snippet('footer') ?>