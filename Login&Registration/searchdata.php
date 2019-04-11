
<html>
<head>
	<title>Search Data By ID</title>
	<style>
	
	th{
		border: 2px solid black;
		width: 1100px;
		background-color: 	#1a8cff;
	}
	.btn{
		width:20%;
		height:5%;
		font-size: 22px;
		padding: 0px;
	}
	body {
 background-color:#C0C0C0;
 
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
	<center><h1> User Details</h1></center>
	<!--
	<div class="container">
	<form action="" method="POST">
	<input type="text" name="UID" class="btn" placeholder="Enter UID" />
	<input type="submit" name="search" class="btn" value="search by UID" />
	</form>
	-->
	<table border="2">
		<tr>
			<th>Name</th>
			<th>Username</th>
			<th>Password</th>
			<th>Gender</th>
			<th>DOB</th>
			<th>Email</th>
			<th>Aadhar</th>
			<th>Pan</th>
			<th>Address</th>
			<th>Phone</th>
			
		</tr><br>
		<?php
		$connection = mysqli_connect("localhost","root","");
		$db = mysqli_select_db($connection,'project1');
		if(isset($_POST['search']))
		{
			$UID = $_POST['UID'];
			$query = "select * from users where UID = '$UID' ";
			$query_run = mysqli_query($connection,$query);
			while($row = mysqli_fetch_array($query_run))
			{
				?>
				<tr>
					<td> <?php echo $row['Name']; ?> </td>
					<td> <?php echo $row['Username']; ?> </td> <!--for futher change the Usename to Username-->
					<td> <?php echo $row['Password']; ?> </td>
					<td> <?php echo $row['Gender']; ?> </td>
					<td> <?php echo $row['DOB']; ?> </td>
					<td> <?php echo $row['Email']; ?> </td>
					<td> <?php echo $row['Aadhar']; ?> </td>
					<td> <?php echo $row['Pan']; ?> </td>
					<td> <?php echo $row['Address']; ?> </td>
					<td> <?php echo $row['Phone']; ?> </td>
				</tr>
				<?php
			}
		}
		?>
	</table>
	<div class="footer">
 <p style="color:black">Developed By :- Bhavna Varshney , Anurag Varshney & Akshat Gandhi</p>
</div>
	</body>
	</html>
	 