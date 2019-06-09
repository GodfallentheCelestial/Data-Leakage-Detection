<?php
	session_start();
	$_SESSION["username"] = $_POST["user"];
	
	$conn = mysqli_connect("","","");//Your Database info here
	mysqli_select_db($conn,"project1");
	$selectquery = "select * from users";
	$selectqueryexec = mysqli_query($conn,$selectquery);
	$flag=0;
	
	if($_POST["user"]=="admin" && $_POST["pwd"]=="admin1234"){
		include("Admin.html");
	}
	else{
	while($row = mysqli_fetch_array($selectqueryexec))
		{
			if($row['Username']==$_POST["user"] && $row['Password']==$_POST["pwd"])
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