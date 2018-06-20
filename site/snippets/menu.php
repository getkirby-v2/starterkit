<pre>menu snippet</pre>
<nav role="navigation" id="menu">

	<button class="hamburger">&#9776;</button>
	<button class="cross hidden">&times;</button>

	<ul class="hidden">
		<?php foreach($pages->visible() as $item): ?>
			<li class="<?= r($item->isOpen(), ' is-active') ?>">
				<a href="<?= $item->url() ?>"><?= $item->title()->html() ?></a>
			</li>
		<?php endforeach ?>
	</ul>
</nav>