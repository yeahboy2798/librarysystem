<?php
	include("includes/autoloader.inc.php");
	

	if(isset($_POST['btnlogin'])){
		$username = $_POST['txtusername'];
		$password = $_POST['txtpassword'];
		$user->loginUser($username, $password);
	}
?>