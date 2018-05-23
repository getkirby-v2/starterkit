<pre>this is the list snippet</pre>
<?php
/*
This list returns all articles related to a resident (original content)
*/

// echo "<pre>";
// print_r( $articles );
// echo "</pre>";
?>

<section>
	<?php if($articles->count()): ?>
		<ul>
			<?php foreach($articles as $article): ?>
				<?php if($article->intendedTemplate() == 'article'): ?>

					<li class="<?= $article->intendedTemplate() ?>">
						<a href="<?= $article->url() ?>">
							<!-- <p><?= $article->intendedTemplate() ?></p> -->
							<p><?= $article->datetime() ?></p>
							<h3><?= $article->title()->html() ?></h3>
							<?php if($image = $article->images()->sortBy('sort', 'asc')->first()): $thumb = $image->crop(720, 240); ?>
								<img src="<?= $thumb->url() ?>" alt="Thumbnail for <?= $article->title()->html() ?>" />
							<?php endif ?>
						</a>
						<pre><?php print_r($article); ?></pre>
					</li>

				<?php elseif($article->intendedTemplate() == 'metascraper'): ?>
					<li class="<?= $article->intendedTemplate() ?>">
						<a href="<?= $article->url_src() ?>" target="_blank">
							<!-- <p><?= $article->intendedTemplate() ?></p> -->
							<p><?= $article->datetime() ?></p>
							<h3><?= $article->title()->html() ?> 🔗</h3>
							<?php if($image = $article->images()->sortBy('sort', 'asc')->first()): $thumb = $image->crop(720, 240); ?>
								<img src="<?= $thumb->url() ?>" alt="Thumbnail for <?= $article->title()->html() ?>" />
							<?php endif ?>
						</a>
						<pre><?php print_r($article); ?></pre>
					</li>

				<?php elseif($article->intendedTemplate() == 'resident'): ?>
					<li class="<?= $article->intendedTemplate() ?>">
						<a href="<?= $article->url() ?>">
							<!-- <p><?= $article->intendedTemplate() ?></p> -->
							<p><?= $article->datetime() ?></p>
							<h3><?= $article->title()->html() ?></h3>
							<?php if($image = $article->images()->sortBy('sort', 'asc')->first()): $thumb = $image->crop(720, 240); ?>
								<img src="<?= $thumb->url() ?>" alt="Thumbnail for <?= $article->title()->html() ?>" />
							<?php endif ?>
						</a>
						<pre><?php print_r($article); ?></pre>
					</li>

				<?php endif ?>
			<?php endforeach ?>
		</ul>
	<?php else: ?>
		<p>No associated items found.</p>
	<?php endif ?>
</section>
