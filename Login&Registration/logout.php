<?php
   session_start();
   unset($_SESSION["username"]);
   echo 'You have successfully Logged Out!';
   header('Refresh: 1; URL = ../Homepage.html');
?>