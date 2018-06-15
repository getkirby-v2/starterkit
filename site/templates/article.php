<?php snippet('header') ?>
<pre>this is the article template</pre>

<main class="main" role="main">
	<article>
		<header class="article-header">
			<?php // image check
				$image = $page->image($page->coverimage());
				if($image):
			?>
				<!-- <img src="<?php echo $image->url() ?>"> -->
				<div class="cover--image" style="background-image: url(<?php echo $image->url() ?>); background-position: <?php echo $image->focusPercentageX() ?>% <?php echo $image->focusPercentageY() ?>%;">
				</div>
			<?php endif ?>
				<div class="cover--title">
					<h1><?= $page->title()->html() ?></h1>
				</div>
			<?php if(! $page->datetime()->empty() ): ?>
				<div class="cover--infobox">
					<p>Published: <?= $page->datetime() ?></p>
				</div>
			<?php endif ?>
			<?php if(! $page->author()->empty() ): ?>
				<div class="cover--infobox">
					<p>Author: <?= $page->author() ?></p>
				</div>
			<?php endif ?>
		</header>

		<div class="intro">
			<?= $page->intro()->kirbytext() ?>
		</div>

		<div class="text">
			<?= $page->text()->kirbytext() ?>
		</div>

	</article>

	<?php	snippet('further_reading', ['limit' => 3]); ?>

</main>

<?php snippet('footer') ?>