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
background-color:#002366;
color:white;
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
  background: black;
  border: none;
  padding: 10px;
  border-radius: 4px;
  border-bottom: 4px solid;
  transition: all .2s ease;
  outline: none;
  text-transform: uppercase;
  font-weight: 700;
}
body{
background-color: white;
}
.jumbotron{
margin-left: auto;
margin-right: auto;
margin-top: 
background-color : #fefefe;
color:white;
border-radius:5%;
text-align:center;
vertical-align: top;
}
.footer {
   position: fixed;
   left: 0;
   bottom: 0;
   width: 100%;
   color: white;
   text-align: center;
}
.table1{
  vertical-align: top;
  
}
</style>
</head>

<body>
 <?php
//S3 Handshake
  //S3 Handshake
  require 'C:Path to Composer Autoload php File';
$credentials = new Aws\Credentials\Credentials('Your secret AWS Key and ID');

$s3 = new Aws\S3\S3Client([
    'version'     => 'latest',
    'region'      => 'your AWS region',
    'credentials' => $credentials
]);  

{
//Session 
  
  session_start();
  $username = $_SESSION['username'];
  
  
  // Fetching UID number
  $conn = mysqli_connect("","","");
  mysqli_select_db($conn,"dbname");
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
  ?>
          
 
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
              
        <a href="UserAccount.php" class="list-group-item list-group-item-action" style = "background-color:black;color:white;">My Account</a>
		<a href="UserAccount.php" class="list-group-item list-group-item-action"> Upload File </a> 
		<a href="view.php" class="list-group-item list-group-item-action" style = "background-color:#002366;color:white;">View or Share File </a>  
        <a href="logout.php" class="list-group-item list-group-item-action">Logout</a> 
              
              
            </div> 
    </div>
  

<div class="col-md-3 " >
	<?php
	echo "<table class='table1'><tr><th colspan='4' rowspan='1'><h1> Your Files </h1></th></tr>";
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
</div>
</div>

<div class="footer">
 <p style="color:black">Developed By :- Akshat Gandhi, Bhavna Varshney & Anurag Varshney</p>
</div>
</body>

</html>