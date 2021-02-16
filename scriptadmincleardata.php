<?php
	include("includes/autoloader.inc.php");
	include_once("sessionchecknouser.php");
	include_once("sessioncheckadmin.php");

	if(isset($_POST['btn'])){
		$tablename = $_POST['tablename'];
		$password = $_POST['password'];

		$system->deleteTable($tablename, $password);
	}
?>