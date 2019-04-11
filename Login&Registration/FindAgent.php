<?php 
require 'C:\Users\project G22\vendor\autoload.php';
	require_once('..\PDF\EmbedPdfLibrary.php');
	include('functions.php');

	if(isset($_POST['submit'])){
		
		// Store File in Temp folder
		$file_name = $_FILES['file']['name'];
		$file_tmp = $_FILES['file']['tmp_name'];
		if(isset($file_name)){
			if(!empty($file_name)){
				move_uploaded_file($file_tmp,"LeakedFiles/".$file_name);
			}
		}
		
		$ext = pathinfo($file_name,PATHINFO_EXTENSION); 
		$pdfsrc = "LeakedFiles/".$file_name;
		
		// Process
		//PDF 

		if($ext=='pdf' || $ext=='mp4'){

		$data = extractPdfData($pdfsrc);
		$list = findUID($data);
		
		// Display List
		echo "<br> <h3> LIST OF ACCESS </h3>";
		foreach($list as $person){
			echo "<br>".$person;
		}
		
		$len = count($list);
		if($len>2){
			echo "<br><br> <h2> Leaked Agent : ";
			echo $list[1];
		}
		}

		else if($ext=='jpeg' || $ext=='jpg'){
			$src = "LeakedFiles/".$file_name; 
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
			
		}
		else if($ext=='mp3'){
			$ffmpeg = FFMpeg\FFMpeg::create(array(
            'ffmpeg.binaries' => 'C:/Users/project G22/vendor/php-ffmpeg/php-ffmpeg/bin/ffmpeg.exe',
            'ffprobe.binaries' => 'C:/Users/project G22/vendor/php-ffmpeg/php-ffmpeg/bin/ffprobe.exe',
            'timeout' => 0, // The timeout for the underlying process
			'ffmpeg.threads' => 12, // The number of threads that FFMpeg should use
			), @$logger);
			//embed audio
			$audiosrc = "LeakedFiles/".$file_name;
			$getID3 = new getID3;
			$audio = $ffmpeg->open($audiosrc);//fetch file
			$ThisFileInfo = $getID3->analyze($audiosrc);
			$agentuid = $ThisFileInfo['tags']['id3v2']['text']['description'];
			$str_arr = explode (",", $agentuid); 
			echo "<br> <h3> LIST OF ACCESS </h3>";
			foreach($str_arr as $person){
			echo "<br>".$person;
		}
			
		$len = count($str_arr);
		if($len>1){
			echo "<br><br> <h2> Leaked Agent : ";
			echo $str_arr[1];
		}
		}
}
?>