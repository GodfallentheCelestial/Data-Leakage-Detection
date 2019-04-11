<?php
require 'C:\Users\project G22\vendor\autoload.php';

if(isset($_POST['Delete'])){



	//Session 
	session_start();
	$username = $_SESSION['username'];
	
	
	// Fetching Source User UID number
	$conn = mysqli_connect("localhost","root","");
	mysqli_select_db($conn,"project1");
	$sql = "Select UID from users where username='$username'";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)!=0){
		while($row = mysqli_fetch_assoc($result))
			$uid = $row['UID'];
	}
$filename = $_POST['varname'];
	$sourceBucket = 'user'.$uid;
	$sourceKeyname = $filename;//file name

$s3->deleteObject([
    'Bucket' => $sourceBucket,
    'Key'    => $sourceKeyname
]);
echo "<script type='text/javascript'>alert('Your File has been Deleted');</script>";
}
?>