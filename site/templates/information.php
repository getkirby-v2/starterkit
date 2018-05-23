<?php snippet('header') ?>
<pre>this is the information template</pre>
<main class="main" role="main">
	<div class="wrap">
		<header>
			<h1><?= $page->title()->html() ?></h1>
			<?= $page->text()->kirbytext() ?>
		</header>

		<?php foreach($page->children()->visible() as $section): ?>
			<section>
				<h3><?= $section->title()->html() ?></h3>
				<?= $section->section()->kirbytext() ?>
			</section>
		<?php endforeach ?>
	</div>
</main>

<?php snippet('footer') ?>
