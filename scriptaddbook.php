<?php
	include("includes/autoloader.inc.php");
	include_once("sessionchecknouser.php");
	include_once("sessioncheckadmin.php");
	$book = new Books();

	if(isset($_POST['btn'])){
		$booktitle = $_POST['booktitle'];
		$subject = $_POST['subject'];
		$author = $_POST['author'];
		$stock = $_POST['stock'];
		$publisherid = $_POST['publisher'];
		$yearpublished = $_POST['yearpublished'];

		$book->validateBooks($booktitle, $subject, $author, $stock, $publisherid, $yearpublished);
		
	}
?>