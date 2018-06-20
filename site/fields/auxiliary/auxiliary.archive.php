<?php
/**
 * Auxiliary
 * metadata loader for Kirby 2.
 *
 * @version   0.1.0
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
		$this->icon        = 'arrow-circle-down';
		$this->label       = l::get('fields.auxiliary.label', 'URL');
		$this->placeholder = 'http://';

	}

	public function validate() {
		return v::url($this->value());
	}

	public function input() {
		$input = parent::input();
		$input->data('field', 'auxiliary');
		// debug_to_console($input);

		return $input;
	}

}

// end here -----------------------
//
// function run_hook() {
// 	kirby()->hook('panel.page.update', function($page) {
// 		$url = $page->auxiliary();
//
// 		if ($url) {
// 			// loads json into a php object
// 			$data = metascrape($url);
//
// 			// converts time to preferred string
// 			$datetime = strtotime($data['date']);
// 			$datetime_new = date('Y-m-d h:m',$datetime);
//
// 			// writes data to .txt file
// 			try {
// 				$page->update(array(
// 					'author'			=> $data['author'],
// 					'datetime'		=> $datetime_new,
// 					// 'datetime'		=> $data['date'],
// 					'description'	=> $data['description'],
// 					'imageurl'			=> $data['image'],
// 					'publisher'		=> $data['publisher'],
// 					'title'			=> $data['title']
// 				));
// 			} catch(Exception $e) {
// 				echo $e->getMessage();
// 			}
// 		}
// 	});
// }
//
// function metascrape($targetURL) {
// 	$url = "https://micro-open-graph-mvnhoamdcv.now.sh?url=". $targetURL;
// 	$content = file_get_contents($url);
// 	$json = json_decode($content, true);
//
// 	return $json;
// }

/**
 * Send debug code to the Javascript console
 */
function debug_to_console($data) {
  if(is_array($data) || is_object($data)) {
    echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
  } else {
    echo("<script>console.log('PHP: $data');</script>");
  }
}