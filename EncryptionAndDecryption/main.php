<?php
require('aes.php');

$file = "File.pdf";
$dest = "File.pdf.encrypt";
$encryptedFile = encryption($file,"1234",$dest);
echo $encryptedFile;
$dest = "Decrypted_File.pdf";
$decryptedFile = decryption($encryptedFile,"1234",$dest);
echo $decryptedFile;

/*$file1 = fopen("try.pdf",'w');
$txt = "John Doe\n";
fwrite($file1, $txt);
fclose($file1);
*/
?>