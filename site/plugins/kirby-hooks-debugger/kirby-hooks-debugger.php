<?php
use JensTornell\HooksDebugger as HooksDebugger;

include __DIR__ . DS . 'core.php';
include __DIR__ . DS . 'routes.php';

$kirby->set('field', 'hooksdebugger', __DIR__ . DS . 'field');

if( site()->user() && c::get('debug') && function_exists('panel') ) {
	$debugger = new HooksDebugger\Core();
	$debugger->debug();
}