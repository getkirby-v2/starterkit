<?php snippet('header') ?>
<pre>this is the information template</pre>
<?php
	if ( $page ) {
		echo '<main class="main '. $page .'" role="main">';
	} else {
		echo '<main class="main information" role="main">';
	}
?>
	<article>
		<header class="article--header">
			<h1 class="accessibility"><?= $page->title()->html() ?></h1>
		</header>

		<?php foreach($page->children()->visible() as $section): ?>
			<div class="text">
				<h1 class="row-span"><?= $section->title()->html() ?></h1>
				<?= $section->section()->kirbytext() ?>
			</div>
		<?php endforeach ?>
	</article>

</main>

<?php snippet('footer') ?>