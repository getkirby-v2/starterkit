<?php if($image = $item->coverimage()->toFile()): ?>
	<figure>
		<img src="<?= $image->url() ?>" alt="<?= $image->caption() ?>" />
		<?php if($image->caption() or $image->credits()): ?>
			<!-- <figcaption><?= $image->caption() ?></figcaption> -->
			<figcaption><?= kirbytextRaw($image->caption()) ?>
				<span class="credits"><?= kirbytextRaw($image->credits()) ?></span>
			</figcaption>
		<?php endif ?>
	</figure>
<?php endif ?>
