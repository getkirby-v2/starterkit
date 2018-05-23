<?php
// $target = "invalid_url";
// $target = "http://google.com/";
$target = "http://jegensentevens.nl/2017/06/tu-delft-en-todaysart-nieuw-artist-in-residence-programma/";
$data = metascrape($target);

// print_r( $data );
//
if ( $data !== null ) {
// 	echo $data['author'];
	$datetime = strtotime($data['date']);
	$newformat = date('Y-m-d h:m',$datetime);
	// 2018-05-21 14:55
	echo $newformat;
// 	echo $data['description'];
// 	echo $data['image'];
// 	echo $data['logo'];
// 	echo $data['publisher'];
// 	echo $data['title'];
// 	echo $data['url'];
}



function metascrape($targetURL) {
	$url = "https://micro-open-graph-mvnhoamdcv.now.sh?url=". $targetURL;
	$content = file_get_contents($url);
	$json = json_decode($content, true);
	return $json;
}

?>