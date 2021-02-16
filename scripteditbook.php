<?php
	include("includes/autoloader.inc.php");
	include_once("sessionchecknouser.php");
	include_once("sessioncheckadmin.php");



	if(isset($_POST['btn'])){
		
		$bookid = $_POST['bookid'];
		$booktitle = $_POST['title'];
		$subject = $_POST['subject'];
		$author = $_POST['author'];
		$stock = $_POST['stock'];
		$publisherid = $_POST['publisherid'];
		$yearpublished = $_POST['yearpublished'];

		$book->validateEditBook($bookid, $booktitle, $subject, $author, $stock, $publisherid, $yearpublished);
		
	}
?>