<?php
include("includes/autoloader.inc.php");
include_once("sessionchecknouser.php");
include_once("sessioncheckadmin.php");


	$publisher = new Publisher();
	$publisherid = $_GET['publisherid'];
	$publisher->deletePublisher($publisherid);


?>