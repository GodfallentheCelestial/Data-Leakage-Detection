
<html>
<head>
	<title>Search Data By ID</title>
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
	
	th{
		border: 2px solid black;
		width: 1100px;
		background-color: 	#002366;
		color:white;
	}
	.btn{
		width:20%;
		height:5%;
		font-size: 22px;
		padding: 0px;
	}
	body {
 background-color:white;
 
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
	<center><h3> User Details</h3></center>
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
		$connection = mysqli_connect("","","");
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
					<td> <?php echo $row['PAN']; ?> </td>
					<td> <?php echo $row['Address']; ?> </td>
					<td> <?php echo $row['Phone']; ?> </td>
				</tr>
				<?php
			}
		}
		?>
	</table>
	<div class="footer">
 <p style="color:black">Developed By :- Akshat Gandhi , Bhavna Varshney & Anurag Varshney</p>
</div>
	</body>
	</html>
	 