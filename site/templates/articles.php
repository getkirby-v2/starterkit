<?php snippet('header') ?>
<pre>articles template</pre>
<main class="main <?= $page ?>" role="main">
	<h1 class="accessibility"><?= $page->title()->html() ?></h1>
	<h2 class="residents--residency">Residency programme participants</h2>
	<?php snippet('list', ['cp_filter' => 'residency', 'per_page' => '50']) ?>

	<h2 class="residents--matching">Matching programme participants</h2>
	<?php snippet('list', ['cp_filter' => 'matching','per_page' => '50'] ); ?>
</main>

<?php snippet('footer') ?>