<?php
/**
 * Auxiliary
 * metadata loader for Kirby 2.
 *
 * @version   0.2.0
 * @author    Michael Tjia <mmm@tjjjia.work>
 * @copyright Michael Tjia <mmm@tjjjia.work>
 * @link
 * @license
 */

class AuxiliaryField extends TextField {

	static public $assets = array(
		'js' => array(
			'auxiliary.js'
		)
	);

	public function __construct() {
		$this->type        = 'auxiliary';
		$this->icon        = 'undo';
		// $this->icon        = 'arrow-circle-down';
		$this->label       = l::get('fields.auxiliary.label', 'URL');
		$this->placeholder = 'http://';

	}

	public function validate() {
		return v::url($this->value());
	}

	public function input() {
		$input = parent::input();
		$input->data('field', 'auxiliary');

		return $input;
	}

}