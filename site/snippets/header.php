<!doctype html>
<html lang="<?= site()->language() ? site()->language()->code() : 'en' ?>">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">

	<title><?= $site->title()->html() ?> | <?= $page->title()->html() ?></title>
	<meta name="description" content="<?= $site->description()->html() ?>">

	<!-- add meta and or opengraph generators here! -->
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

	<?= css('assets/css/index.css') ?>

</head>
<body>
	<pre>header snippet</pre>
	<header>
		<h1>
			<a href="<?= url() ?>" rel="home"><?= $site->title()->html() ?></a>
		</h1>
		<?php snippet('menu') ?>
	</header>
