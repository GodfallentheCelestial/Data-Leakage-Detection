<?php 

echo readFile("Sample1.txt");
echo "<pre>";
echo file_get_contents("Sample1.txt");
echo "</pre>";

//$f = fopen("Sample1.txt",'a');
//fwrite($f,"IP ADDRESS");

?>