<?php 

if(empty($url)) {
  return;
}

$extension = f::extension($url);
$mime      = f::extensionToMime($extension);

echo '<link rel="icon" href="' . url($url) . '" type="' . $mime . '">';