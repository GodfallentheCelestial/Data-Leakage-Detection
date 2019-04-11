<?php 
include('functions.php');
$message_to_hide = '1896';
$binary_message = toBin($message_to_hide);
$message_length = strlen($binary_message);
$src = 'source.jpg';
$im = imagecreatefromjpeg($src);
for($x=0;$x<$message_length;$x++){
  $y = $x;
  $rgb = imagecolorat($im,$x,$y);
  $r = ($rgb >>16) & 0xFF;
  $g = ($rgb >>8) & 0xFF;
  $b = $rgb & 0xFF;
  
  $newR = $r;
  $newG = $g;
  $newB = toBin($b);
  $newB[strlen($newB)-1] = $binary_message[$x];
  $newB = toString($newB);
  
  $new_color = imagecolorallocate($im,$newR,$newG,$newB);
  imagesetpixel($im,$x,$y,$new_color);
}
echo $x;
imagepng($im,'duplicate.png');
imagedestroy($im);
?>