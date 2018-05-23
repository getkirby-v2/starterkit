<?php
namespace JensTornell\HooksDebugger;
use C;
use f;

class Core {
	function handleErrors() {  
		$error = error_get_last();

		if( ! is_null( $error ) && $this->validErrorType( $error['type'] ) ) {
			$this->writeLog( $error );
		} else {
			$this->clearLog();
		}
	}

	function writeLog( $error, $hook ) {
		$json = $error;
		$json['time'] = time();
		
		f::write($this->logFile(), json_encode($json));
	}

	function hooks() {
		return c::get('plugin.hooks.debugger.hooks', array(
			'panel.page.create',
			'panel.page.update',
			'panel.page.delete',
			'panel.page.sort',
			'panel.page.hide',
			'panel.page.move',
			'panel.site.update',
			'panel.file.upload',
			'panel.file.replace',
			'panel.file.rename',
			'panel.file.update',
			'panel.file.sort',
			'panel.file.delete',
			'panel.user.create',
			'panel.user.update',
			'panel.user.delete',
			'panel.avatar.upload',
			'panel.avatar.delete'
		));
	}

	function clearLog() {
		$file = f::read($this->logFile());
		if( $file != '' ) {
			f::write($this->logFile(), '');
		}
	}

	function readLog() {
		$file = f::read($this->logFile());
		$object = json_decode($file);
		return $object;
	}

	function validErrorType( $type ) {
		$types = c::get('plugin.hooks.debugger.error.types', array(1));

		if( in_array( $type, $types ) ) {
			return true;
		}
	}

	function logFile() {
		return c::get( 'plugin.hooks.debugger.logfile', kirby()->roots()->index() . DS . 'hooks-debugger.txt' );
	}

	function debug() {
		register_shutdown_function(array($this, 'handleErrors'));
	}

	function types($type) {
		switch($type) {
			case E_ERROR: // 1 // 
				return 'E_ERROR';
			case E_WARNING: // 2 // 
				return 'E_WARNING';
			case E_PARSE: // 4 // 
				return 'E_PARSE';
			case E_NOTICE: // 8 // 
				return 'E_NOTICE';
			case E_CORE_ERROR: // 16 // 
				return 'E_CORE_ERROR';
			case E_CORE_WARNING: // 32 // 
				return 'E_CORE_WARNING';
			case E_COMPILE_ERROR: // 64 // 
				return 'E_COMPILE_ERROR';
			case E_COMPILE_WARNING: // 128 // 
				return 'E_COMPILE_WARNING';
			case E_USER_ERROR: // 256 // 
				return 'E_USER_ERROR';
			case E_USER_WARNING: // 512 // 
				return 'E_USER_WARNING';
			case E_USER_NOTICE: // 1024 // 
				return 'E_USER_NOTICE';
			case E_STRICT: // 2048 // 
				return 'E_STRICT';
			case E_RECOVERABLE_ERROR: // 4096 // 
				return 'E_RECOVERABLE_ERROR';
			case E_DEPRECATED: // 8192 // 
				return 'E_DEPRECATED';
			case E_USER_DEPRECATED: // 16384 // 
				return 'E_USER_DEPRECATED';
		}
		return "";
	}
}