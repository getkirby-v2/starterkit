<pre>this is the list snippet</pre>
<?php
/*
This list returns all articles related to a resident (original content)
*/

// echo "<pre>";
// print_r( $articles );
// echo "</pre>";
?>
<?php if (isset($cp_filter)): ?>
	<section class="list <?=$cp_filter?>">
<?php else: ?>
	<section class="list">
<?php endif; ?>
	<?php if($articles->count()): ?>
		<?php
			echo '<pre>'.$articles.'</pre>';
			if (isset($cp_filter)) {
				$articles = $articles->filterBy('programme', $cp_filter,',');
			}
			echo '<pre>'.$articles.'</pre>';
		?>
		<ul>
			<?php foreach($articles as $article): ?>
				<?php if($article->intendedTemplate() == 'article'): ?>
					<li class="<?= $article->intendedTemplate() ?>">
							<div class="card--title">
								<a href="<?= $article->url() ?>">
									<h3><?= $article->title()->html() ?></h3>
									<?php // image check
										$image = $article->image($article->coverimage());
										if($image):
									?>
										<div class="card--image filter--blueAlpha" style="background-image: url(<?php echo $image->url() ?>); background-position: <?php echo $image->focusPercentageX() ?>% <?php echo $image->focusPercentageY() ?>%;">
									<?php else: ?>
										<div class="card--image filter--blueAlpha">
									<?php endif ?>
										</div>
									</a>
							</div>
						<?php if(! $article->teaser()->empty() ): ?>
							<div class="card--main">
								<p><?= $article->teaser() ?></p>
							</div>
						<?php elseif(! $article->text()->empty() ): ?>
							<div class="card--main">
								<p><?= excerpt($article->text(), 300) ?></p>
							</div>
						<?php else: ?>
						<?php endif ?>
						<?php if(! $article->datetime()->empty() ): ?>
							<div class="card--infobox">
								<p>Published: <?= $article->datetime() ?></p>
							</div>
						<?php endif ?>
						<?php if(! $article->author()->empty() ): ?>
							<div class="card--infobox">
								<p>Author: <?= $article->author() ?></p>
							</div>
						<?php endif ?>
							<!-- <div class="card--residents">
								<ul>
									<?php
										$resident = $article->residents()->value();
										$resident__array = explode(',', $resident);
										foreach($resident__array as $resident):
									?>
										<li><?= $resident ?></li>
									<?php endforeach ?>
								</ul>
							</div> -->
						<!-- <pre><?php print_r($article); ?></pre> -->
					</li>

				<?php elseif($article->intendedTemplate() == 'metascraper'): ?>
					<li class="<?= $article->intendedTemplate() ?>">
						<div class="card--main">
							<a href="<?= $article->url_src() ?>" target="_blank">
								<h3><?= $article->title()->html() ?> <img src="/assets/svg/chain.svg" style="fill:#fff; height: 5rem; padding: .7rem;"></h3>
							</a>
							<p><?= excerpt($article->description(), 300) ?></p>
						</div>
						<?php if(! $article->datetime_src()->empty() ): ?>
							<div class="card--infobox">
								<p>Published: <?= $article->datetime_src() ?></p>
							</div>
						<?php endif ?>
						<?php if(! $article->url_src()->empty() ): ?>
							<div class="card--infobox">
								<p>
									Source:
									<a href="<?= parse_url($article->url_src(), PHP_URL_SCHEME) ?>://<?= parse_url($article->url_src(), PHP_URL_HOST) ?>" target="_blank">
										<?= parse_url($article->url_src(), PHP_URL_HOST) ?>
									</a>
								</p>
							</div>
						<?php endif ?>

						<pre><?php print_r($article); ?></pre>

					</li>

				<?php elseif($article->intendedTemplate() == 'resident'): ?>
							<li class="<?= $article->intendedTemplate() ?>">
								<a href="<?= $article->url() ?>">
									<h3><?= $article->title()->html() ?></h3>
									<!-- <?php if($image = $article->images()->sortBy('sort', 'asc')->first()): $thumb = $image->crop(720, 240); ?>
										<img src="<?= $thumb->url() ?>" alt="Thumbnail for <?= $article->title()->html() ?>" />
									<?php endif ?> -->
								</a>
								<pre><?php print_r($article); ?></pre>
							</li>

				<?php endif ?>
			<?php endforeach ?>
		</ul>
	<?php else: ?>
		<pre>No associated items found.</pre>
	<?php endif ?>
</section>
