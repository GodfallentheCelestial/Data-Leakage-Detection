
<html>
<head>
	<title> Report </title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	
	<style>
		.navbar{
			text-align:center;
			font-size:24px;
			background-color:#002366;
			color:white;
		}
		
		body{
			background-color: white;
		}
		
		.jumbotron{
			margin-left: auto;
			margin-right: auto;
			background-color : #002366;
			color:white;
			border-radius:5%;
			text-align:center;
		}
		.footer {
			   position: fixed;
			   left: 0;
			   bottom: 0;
			   width: 100%;
			   color: white;
			   text-align: center;
		}
	
	</style>
	
</head>

<body>
<nav class="navbar navbar-expand-lg">
  <span class="navbar-text">
   Data Leakage Detection
  </span>
</nav>
<br><br>
<div class="container">
  <div class="row">
    <div class="col-md-3 ">
         <div class="list-group ">
              
			<a href="../Homepage.html" class="list-group-item list-group-item-action">Homepage</a>
			<a href="../Login&Registration/Admin.html" class="list-group-item list-group-item-action">Add User </a>
              <a href="../CosineSimilarity/SimilarityFrontend.html" class="list-group-item list-group-item-action">Similarity of Documents</a>
              <a href="#" style = "background-color:#002366;color:white;" class="list-group-item list-group-item-action">Find Guilty</a>
              <a href="searchdata.html" class="list-group-item list-group-item-action">Display User Details</a>
              <a href="logout.php" class="list-group-item list-group-item-action">Logout</a> 
              
              
            </div> 
    </div>
	<div class="col-md-9">
	<div class="jumbotron text-center">
<?php 
require_once('..\PDF\EmbedPdfLibrary.php');
	require_once('..\EncryptionAndDecryption\aes.php');
	require 'KeyGeneration.php';
	require 'Path to composer autoload.php';
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
            'ffmpeg.binaries' => 'Path to ffmpeg exe',
                'ffprobe.binaries' => 'Path to ffprobe exe',
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
</div>
</div>
</div>
</div>
</body>
</html>
