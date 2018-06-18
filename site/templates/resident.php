<?php snippet('header') ?>
<pre>this is the resident template</pre>
<main class="main resident" role="main">
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
		</header>

		<div class="intro">
			<?= $page->intro()->kirbytext() ?>
		</div>

		<div class="text">
			<?= $page->text()->kirbytext() ?>
		</div>

	</article>
	<?php snippet('list'); ?>

</main>

<?php snippet('footer') ?>