<?php

/*

---------------------------------------
License Setup
---------------------------------------

Please add your license key, which you've received
via email after purchasing Kirby on http://getkirby.com/buy

It is not permitted to run a public website without a
valid license key. Please read the End User License Agreement
for more information: http://getkirby.com/license

*/
c::set('license', 'K2-PERSONAL-UgBsIWi7CX6WIemsooXkULxKhS6yHRZU');

/*

---------------------------------------
Kirby Configuration
---------------------------------------

By default you don't have to configure anything to
make Kirby work. For more fine-grained configuration
of the system, please check out http://getkirby.com/docs/advanced/options

*/
c::set('debug', true); // debugging mode
c::set('home','current-affairs'); // custom home page
// c::set('home','crossing-parallels'); // custom home page

c::set('meta-tags.templates', function(Page $page, Site $site) {
	return [
		'article' => [ // template name
			'meta' => function(Page $page) {
				if (!empty( $page->teaser() )) {
					return [
						'description' => $page->teaser()
					];
				}
			},
			'og' => [  // tags group name
				'type' => 'article', // overrides the default
				'namespace:article' => [
					'author' => $page->author(),
					'published_time' => $page->date('%F'),
					'modified_time' => $page->modified('%F'),
					'tag' => ['tech', 'web']
				],
				'namespace:image' => function(Page $page) {
					if(!empty($page->coverimage()->toFile())) {
						$image = $page->coverimage()->toFile();

						return [
							'image' => $image->url(),
							'height' => $image->height(),
							'width' => $image->width(),
							'type' => $image->mime()
						];
					}
				}
			]
		]
	];
});