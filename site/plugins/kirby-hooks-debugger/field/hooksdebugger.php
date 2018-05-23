<?php
use JensTornell\HooksDebugger as HooksDebugger;

class HooksdebuggerField extends BaseField {
	static public $fieldname = 'hooksdebugger';
	static public $assets = array(
		'css' => array(
			'style.css',
		)
	);

	public function input() {
		if( ! c::get('debug') ) return '';
		
		$core = new HooksDebugger\Core();
		$object = $core->readLog();

		if( ! is_object( $object ) ) return '';

		$type = $core->types($object->type);
		$line = $object->line;
		$time = $object->time;
		$message = $object->message;
		$file = $object->file;

		$html = tpl::load( __DIR__ . DS . 'template.php', $data = array(
			'field' => $this,
			'page' => $this->page(),
			'type' => $type,
			'line' => $line,
			'time' => $time,
			'message' => $message,
			'file' => $file
		));
		return $html;
	}
}