<?php
use JensTornell\HooksDebugger as HooksDebugger;

if( site()->user() && c::get('debug') ) {
	kirby()->routes(array(
		array(
			'pattern' => 'plugin.hooks.debugger.log.clear/(:any)',
			'action'  => function($uid) {
				$object = new HooksDebugger\Core();
				$object->clearLog();
				$url = u() . '/panel/pages/' . $uid . '/edit';
				header::redirect( $url );
			}
		),
	));
}