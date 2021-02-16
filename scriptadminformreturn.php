<?php
include("includes/autoloader.inc.php");
include_once("sessionchecknouser.php");
include_once("sessionchecknouser.php");

if(isset($_POST['btn'])){
	$bookid = $_POST['bookid'];
	$studentid = $_POST['studentid'];
	$book->adminReturn($bookid, $studentid);
	
}

?>