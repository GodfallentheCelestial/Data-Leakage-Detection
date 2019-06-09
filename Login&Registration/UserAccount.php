<html>

<head>
	<title> User Page </title>
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
body {
 background-color:white;
 }
 .nav_float_1{
  margin-left: 62px;
   left: 0;
   bottom: 0;
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
  background: black;
  border: none;
  padding: 10px;
  border-radius: 4px;
  border-bottom: 4px solid black;
  transition: all .2s ease;
  outline: none;
  text-transform: uppercase;
  font-weight: 700;
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
  
   left: 0;
   bottom: 0;
   width: 100%;
   color: white;
   text-align: center;
   background-color : white;
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
              
        <a href="#" class="list-group-item list-group-item-action" style = "background-color:black;color:white;">My Account</a>
		<a href="UserAccount.php" class="list-group-item list-group-item-action" style = "background-color:#002366;color:white;" > Upload File </a> 
		<a href="view.php" class="list-group-item list-group-item-action">View or Share File </a>  
        <a href="logout.php" class="list-group-item list-group-item-action">Logout</a>         
              
              
            </div> 
    </div>

	<div class="col-md-9">
			<div class="jumbotron text-center">
				<h3> Welcome <?php echo $_SESSION["username"]; ?> </h3>
				<form name="user" method="POST" action="user.php" enctype = "multipart/form-data" id="#">
		<div class="form-group files">
			  
                <h3 style="font-size:20px"><label>Upload Your File </label></h3>
		<input type="file" name="file" id="file" class="form-control" ></div>
		<input type="submit" value="upload" name="Upload" class="file-upload-btn" >

	</form>	
	 </div>
	</div>
   </div>
   
<div class="footer">
 <p style="color:black">Developed By :- Akshat Gandhi , Bhavna Varshney & Anurag Varshney</p>
</div>
</body>

</html>