<?php 
include('functions.php');
$src = 'duplicate.png';
$im = imagecreatefrompng($src);
$real_message = '';
for($x=0;$x<40;$x++){
  $y = $x;
  $rgb = imagecolorat($im,$x,$y);
  $r = ($rgb >>16) & 0xFF;
  $g = ($rgb >>8) & 0xFF;
  $b = $rgb & 0xFF;
  
  $blue = toBin($b);
  $real_message .= $blue[strlen($blue)-1];
}
$real_message = toString($real_message);
echo $real_message;
die;
?>