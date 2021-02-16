<?php
include("includes/autoloader.inc.php");
include_once("sessionchecknouser.php");
include_once("sessionchecknouser.php");

@$borrowid = $_GET['borrowid'];
$book->returnBook($borrowid);





?>