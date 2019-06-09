<?php

//Libraries Section
require 'C:Path to Composer Autoload php File';
require_once('..\EncryptionAndDecryption\aes.php');  //Encryption Library
include('functions.php');  //Image Library
require_once('..\PDF\EmbedPdfLibrary.php'); //PDF Library
require 'KeyGeneration.php';

if(isset($_POST['Share'])){


	$target_Username = $_POST['target_user'];
	if($target_Username==" "){
		echo "Error : No Username Entered!";
	}
	else{
		
	//S3 Handshake
	
$credentials = new Aws\Credentials\Credentials('Your secret AWS Key and ID');

	$s3 = new Aws\S3\S3Client([
	    'version'     => 'latest',
	    'region'      => 'Your AWS Region',
	    'credentials' => $credentials
	]);	

	//Session 
	session_start();
	$username = $_SESSION['username'];
	
	
	// Fetching Source User UID number
	$conn = mysqli_connect("","","");
	mysqli_select_db($conn,"project1");
	$sql = "Select UID from users where username='$username'";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)!=0){
		while($row = mysqli_fetch_assoc($result))
			$source_uid = $row['UID'];
	}

	//Fetching Target UID
	$sql = "Select UID from users where username='$target_Username'";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)!=0){
		while($row = mysqli_fetch_assoc($result))
			$target_uid = $row['UID'];
	}

	$filename = $_POST['varname'];
	$sourceBucket = 'user'.$source_uid;//bucket of user who is sharing
	$sourceKeyname = $filename;//file name
	$targetBucket = 'user'.$target_uid;//bucket of reciever

	//Download File from Cloud Code
	$s3->getObject(array(
    'Bucket' => 'user'.$source_uid,
    'Key'    => $filename,
    'ResponseContentDisposition' => 'attachment; filename="'.$filename.'"',
    'SaveAs' => 'UploadedFiles/'.$filename
));
	
	//Decryption of File
		$arrays = explode("_",$filename);
		$author_uid = $arrays[0];
		$dest = str_replace(".encrypt","",$filename);
		$key = generatekey($author_uid);
		$decryptedFile = decryption("UploadedFiles/".$filename,$key,$dest);
		
		
	//Extension Fetching
		$filename = str_replace(".encrypt","",$filename);
		$ext = pathinfo("UploadedFiles/".$filename,PATHINFO_EXTENSION);
		$ext = strtolower($ext);
		//echo $ext;
		
		$target_file = realpath($decryptedFile);
	//PDF EMBEDDING
		if($ext=="pdf" || $ext=='mp4'){
			$binary = encodeUID($target_uid);
			$embed_data = makeUIDPdf($binary);
			embedPdf($decryptedFile,$embed_data);
			$encryptedFilepdf = encryption($decryptedFile,$key,'Share/'.$decryptedFile.".encrypt");
			try{//putting an object
				$result = $s3->putObject([
					'Bucket' => $targetBucket,
					'Key'    => $sourceKeyname,
					'SourceFile' => $encryptedFilepdf	
				]);
				}catch (S3Exception $e) {
					echo $e->getMessage() . PHP_EOL;
				} 	
				$message = "Your File has been Shared!";
                echo "<script type='text/javascript'>alert('$message');</script>";
		}
		else if($ext=="jpg" || $ext=="jpeg"){
			//Extracting Source UID
			$src = $decryptedFile;
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
			
			//Combining Source UID with Target UID
			$real_message .= toString($target_uid);
			
			//Embedding Both the UID's
			$message_to_hide = $real_message;
			$binary_message = toBin($message_to_hide);
			$message_length = strlen($binary_message);
			$src = $file_name;
			
			
			$im = imagecreatefromjpeg("UploadedFiles/".$src);
			
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
			imagepng($im,"UploadedFiles/".$src);
			imagedestroy($im);
			
		}
		else if($ext=='mp3'){
			//Audio Share Code
			$ffmpeg = FFMpeg\FFMpeg::create(array(
            'ffmpeg.binaries' => 'Path to ffmpeg.exe',
            'ffprobe.binaries' => 'Path to ffprobe.exe',
            'timeout' => 0, // The timeout for the underlying process
			'ffmpeg.threads' => 12, // The number of threads that FFMpeg should use
			), @$logger);
			//embed audio
			$audiosrc = realpath($decryptedFile);
			
			$audio = $ffmpeg->open($audiosrc);//fetch file

			//get existing uid from audio
			$getID3 = new getID3;
			$ThisFileInfo = $getID3->analyze($decryptedFile);
            $puid = $ThisFileInfo['tags']['id3v2']['text']['description'];
            $nuid = $puid.','.$target_uid;//concat uids

            $audio->filters()->addMetadata(["description" => $nuid]);//embed
            $format = new FFMpeg\Format\Audio\mp3();
			$format->on('progress', function ($audio, $format, $percentage) {
    		echo "$percentage % transcoded";
				});//keep same format
			$audio->save($format, $decryptedFile);//save to file
			
		
		$encryptedFile2 = encryption($target_file,$key,'Share/'.$decryptedFile.".encrypt");
		$message = "Your File has been Shared!";
                echo "<script type='text/javascript'>alert('$message');</script>";
		
	//Uploading file on Target User Bucket
	try{//putting an object
		$result = $s3->putObject([
			'Bucket' => $targetBucket,
			'Key'    => $sourceKeyname,
			'SourceFile' => $encryptedFile2		
		]);
	}catch (S3Exception $e) {
echo $e->getMessage() . PHP_EOL;
} 	
}
}
}

?>