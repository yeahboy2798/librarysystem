<?php
	include("includes/autoloader.inc.php");
	include_once("sessionchecknouser.php");
	include_once("sessioncheckadmin.php");

	$bookid = $_GET['bookid'];
	$book->deleteBook($bookid);
?>