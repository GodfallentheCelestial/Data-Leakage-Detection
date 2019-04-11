<?php 
function toBin($str){
  $str = (string)$str;
  $l = strlen($str);
  $result = '';
  while($l--){
    $result = str_pad(decbin(ord($str[$l])),8,"0",STR_PAD_LEFT).$result;
  }
  return $result;
}
function toString($binary){
  return pack('H*',base_convert($binary,2,16));
}
?>