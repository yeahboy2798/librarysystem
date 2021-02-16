<?php
include("includes/autoloader.inc.php");
include_once("sessionchecknouser.php");
include_once("sessioncheckadmin.php");
	
	if(isset($_POST['btn'])){
		$id = $_POST['id'];
		$name = $_POST['name'];
		$address = $_POST['address'];

		$publisher->saveEditedPublisher($id, $name, $address);

	}


?>