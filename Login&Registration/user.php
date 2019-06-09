<?php
	// Importing Libraries in this section
	
	//PDF Library
	require_once('..\PDF\EmbedPdfLibrary.php');
	//Audio Library
	require_once('..\EncryptionAndDecryption\aes.php');
	require 'KeyGeneration.php';
	require 'C:Path to Composer Autoload php File';

	include('functions.php');

	//S3 Handshake

	
$credentials = new Aws\Credentials\Credentials('Your secret AWS Key and ID');

	$s3 = new Aws\S3\S3Client([
	    'version'     => 'latest',
	    'region'      => 'Your region here',
	    'credentials' => $credentials
	]);	

	//Session 
	session_start();
	$username = $_SESSION['username'];
	
	
	// Fetching UID number
	$conn = mysqli_connect("","","");
	mysqli_select_db($conn,"project1");
	$sql = "Select UID from users where username='$username'";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)!=0){
		while($row = mysqli_fetch_assoc($result))
			$uid = $row['UID'];

	
	//uploadbutton
	if(isset($_POST["Upload"])){
		
		$file_name = $_FILES['file']['name'];
		$file_tmp = $_FILES['file']['tmp_name'];
		
		move_uploaded_file($file_tmp,"UploadedFiles/".$file_name);
		$pdfsrc = "UploadedFiles/".$file_name;
		$ext = pathinfo($file_name,PATHINFO_EXTENSION);
		$ext = strtolower($ext);

		//PDF EMBEDDING
		if($ext=="pdf"){
			//Embedding data section
			$binary = encodeUID($uid);
			$embed_data = makeUIDPdf($binary);
			embedPdf($pdfsrc,$embed_data);
			//echo "<br> PDF File uploaded";
		}
		else if($ext=='mp4'){
			$binary = encodeUID($uid);
			$embed_data = makeUIDPdf($binary);
			//Adding data at the new line
			$file2 = fopen($pdfsrc,"a+") or die("Unable to open file");
			fwrite($file2,"\n".$embed_data);

		}
		//IMAGE EMBEDDING
		else if($ext=="jpg" || $ext=="jpeg"){
		

			$message_to_hide = $uid;
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
			//echo ('<br><br> Image File Uploaded');

		}
		//AUDIO EMBEDDING
		else if($ext=='mp3'){

				$ffmpeg = FFMpeg\FFMpeg::create(array(
                'ffmpeg.binaries' => 'Path to ffmpeg exe',
                'ffprobe.binaries' => 'Path to ffprobe exe',
                'timeout' => 0, // The timeout for the underlying process
                'ffmpeg.threads' => 12, // The number of threads that FFMpeg should use
                 ), @$logger);
         $audio = $ffmpeg->open('UploadedFiles/'.$file_name);
         $audio->filters()->addMetadata(["description" => $uid]);
         $format = new FFMpeg\Format\Audio\mp3();
         $format->on('progress', function ($audio, $format, $percentage) {
         
         });
         
         $audio->save($format,$file_name);

		}
		else{
			echo "<script type='text/javascript'>alert('File Not Supported!');</script>";
		}
		
		// Encryption 
		$encryptedFile = encryption("UploadedFiles/".$file_name,generatekey($uid),"UploadedFiles/".$file_name.".encrypt");
		

		//Cloud Storage


        try{//putting an object
		$result = $s3->putObject([
			'Bucket' => 'user'.$uid,
			'Key'    => $uid."_".$file_name.'.encrypt',
			'SourceFile' => $encryptedFile			
		]);
		$message = "File Uploaded!";
                echo "<script type='text/javascript'>alert('$message');</script>";
		}catch (S3Exception $e) {
			echo $e->getMessage() . PHP_EOL;
		} 
		
	}
}
	
?>



