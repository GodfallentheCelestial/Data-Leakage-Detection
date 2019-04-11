<html>

<head>
	<title> View Files Page </title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style>
.navbar{
text-align:center;
font-size:24px;
<!--justify-content:center;-->
}
body {
 background-color:#C0C0C0;
 }
 .nav_float_1{
  margin-left: 62px;
   left: 0;
   bottom: 0;
   width: 100%;
   color: white;
   text-align: center;
}
 .container {
   margin-left: 475px;
   margin-top: 0;
   width: 100%;
   color: white;
   text-align: center;
}

.files input {
    outline: 2px dashed #92b0b3;
    outline-offset: -10px;
    -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
    transition: outline-offset .15s ease-in-out, background-color .15s linear;
    padding: 120px 0px 85px 35%;
    text-align: center !important;
    margin: 0;
    width: 100% !important;
}
.files input:focus{     outline: 2px dashed #92b0b3;  outline-offset: -10px;
    -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
    transition: outline-offset .15s ease-in-out, background-color .15s linear; border:1px solid #92b0b3;
 }
.files{ position:relative}
.files:after {  pointer-events: none;
    position: absolute;
    top: 60px;
    left: 0;
    width: 50px;
    right: 0;
    height: 56px;
    content: "";
    background-image: url(https://image.flaticon.com/icons/png/128/109/109612.png);
    display: block;
    margin: 0 auto;
    background-size: 100%;
    background-repeat: no-repeat;
}
.color input{ background-color:#f1f1f1;}
.files:before {
    position: absolute;
    bottom: 10px;
    left: 0;  pointer-events: none;
    width: 100%;
    right: 0;
    height: 57px;
    content: " or drag it here. ";
    display: block;
    margin: 0 auto;
    color: #2ea591;
    font-weight: 600;
    text-transform: capitalize;
    text-align: center;
}

.file-upload-btn {
  width: 100%;
  margin: 0;
  color: #fff;
  background: #337AB7;
  border: none;
  padding: 10px;
  border-radius: 4px;
  border-bottom: 4px solid #337AB7;
  transition: all .2s ease;
  outline: none;
  text-transform: uppercase;
  font-weight: 700;
}
body{
background-color: C0C0C0;
}
#data{
  margin: 20px;
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar navbar-dark bg-primary">
  <span class="navbar-text">
   Data Leakage Detection
  </span>
</nav>
<br><br>
<div class="nav_float_1">
  <div class="row">
    <div class="col-md-3 ">
         <div class="list-group ">
              
        <a href="../Homepage.html" class="list-group-item list-group-item-action" >Homepage</a>
        <a href="logout.php" class="list-group-item list-group-item-action">Logout</a>         
              
              
            </div> 
    </div>
    <div class="col-md-9">
        
            </div>
          </div>
        </div>
        <div id="data"> 
<?php
//S3 Handshake
	require 'C:\Users\project G22\vendor\autoload.php';

$s3 = new Aws\S3\S3Client([
    'version'     => 'latest',
    'region'      => 'ap-south-1',
    'credentials' => $credentials
]);	 

if(isset($_POST["View"])){
//Session 
	
	session_start();
	$username = $_SESSION['username'];
	
	
	// Fetching UID number
	$conn = mysqli_connect("localhost","root","");
	mysqli_select_db($conn,"project1");
	$sql = "Select UID from users where username='$username'";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)!=0){
		while($row = mysqli_fetch_assoc($result))
			$uid = $row['UID'];
	}

//Retrieve Files
    try{
	$objects = $s3->getIterator('ListObjects', array(
	    'Bucket' => 'user'.$uid
	       //'Prefix' => 'files/' to indicate a folder
	));
	
	echo "<table><tr><th colspan='4'><h1> Your Files </h1></th></tr>";
	foreach ($objects as $object) {
		$tagit = $object['Key'];
    $array = explode("_",$tagit);

		echo "<tr><td colspan='2'>";

	    echo $array[1];

      echo "</td><td><form name='share' method='POST' action='share1.php' enctype = 'multipart/form-data'><input type='hidden' name='varname' value=$tagit /></td><td><input type='text' name='target_user' placeholder='Enter Username for sharing file'/></td><td><input type='submit' value='share' name='Share'></form></td><td><form name='delete' method='POST' action='delete.php' enctype = 'multipart/form-data'><input type='hidden' name='varname' value=$tagit /><input type='submit' value='Delete' name='Delete'></form></td><td><form name='download' method='POST' action='download.php' enctype = 'multipart/form-data'><input type='hidden' name='varname' value=$tagit /><input type='submit' value='Download' name='Download'></form></td></tr>";
		echo "<br>";
	}
	echo "</table>";
}
catch (S3Exception $e) {
			echo $e->getMessage() . PHP_EOL;
		} 
}



?>
</div>
</body>

</html>