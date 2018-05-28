<?php snippet('header') ?>
<pre>home template</pre>
<main class="main" role="main">
	<h1 class="accessibility"><?= $page->title()->html() ?></h1>
	<?php snippet('list') ?>

</main>

<?php snippet('footer') ?>