<?php
	include("includes/autoloader.inc.php");
	include_once("sessionchecknouser.php");
	include_once("sessioncheckadmin.php");
	
	//get the borrow id
	@$borrowid = $_GET['borrowid'];
	$book->adminConfirmBorrowRequest($borrowid);
	
?>