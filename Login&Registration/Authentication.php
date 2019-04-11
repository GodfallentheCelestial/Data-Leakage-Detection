<?php
	session_start();
	$_SESSION["username"] = $_GET["user"];
	
	$conn = mysqli_connect("localhost","root","");
	mysqli_select_db($conn,"project1");
	$selectquery = "select * from users";
	$selectqueryexec = mysqli_query($conn,$selectquery);
	$flag=0;
	
	if($_GET["user"]=="admin" && $_GET["pwd"]=="admin1234"){
		include("Admin.html");
	}
	else{
	while($row = mysqli_fetch_array($selectqueryexec))
		{
			if($row['Username']==$_GET["user"] && $row['Password']==$_GET["pwd"])
			{	
				include("UserAccount.php");
				$flag=1;
				break;
			}
			}
	if($flag==0){
		echo "Incorrect Credentials!";
	}
}
 
?>