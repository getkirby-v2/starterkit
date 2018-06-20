<!doctype html>
<html lang="<?= site()->language() ? site()->language()->code() : 'en' ?>">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">

	<title><?= $site->title()->html() ?>: <?= $page->title()->html() ?></title>
	<!-- <meta name="description" content="<?= $site->description()->html() ?>"> -->

	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
		<!-- add meta and or opengraph generators here! -->
	<?= $page->metaTags() ?>

	<?= css('/assets/css/index.css') ?>

</head>
<body>
	<pre>header snippet</pre>
	<header>
		<a href="<?= url() ?>" rel="home">
			<svg class="worm" viewBox="0 0 771.3 109.9">
				<text><?= $site->title()->html() ?></text>
				<use xlink:href="/assets/svg/symbols.svg#crossing-parallels-worm" />
			</svg>
		</a>
		<?php snippet('menu') ?>
	</header>
