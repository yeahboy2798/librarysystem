<?php
	include("includes/autoloader.inc.php");
	include_once("sessionchecknouser.php");
	include_once("sessioncheckadmin.php");
	$publisher = new Publisher();

	if(isset($_POST['btnSavePublisher'])){
		$publishername = $_POST['txtpublishername'];
		$publisheraddress = $_POST['txtpublisheraddress'];
		
		$publisher->validateAddPublisher($publishername, $publisheraddress);
		
	}
?>