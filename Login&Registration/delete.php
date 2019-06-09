<?php
require 'C:Path to Composer Autoload php File';

if(isset($_POST['Delete'])){

$credentials = new Aws\Credentials\Credentials('Your secret AWS Key and ID');

	$s3 = new Aws\S3\S3Client([
	    'version'     => 'latest',
	    'region'      => 'Your region here',
	    'credentials' => $credentials
	]);	

	//Session 
	session_start();
	$username = $_SESSION['username'];
	
	
	// Fetching Source User UID number
	$conn = mysqli_connect("","","");//Your Database info here
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