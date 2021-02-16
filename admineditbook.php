<?php
	include("includes/autoloader.inc.php");
	include_once("sessionchecknouser.php");
	include_once("sessioncheckadmin.php");
	// echo $_SESSION['account_id'];
	$user = new Users();
	$book = new Books();
	$element = new Elements();
	$bookid = $_GET['bookid'];
	
?>

<?php
$bookid = $_GET['bookid'];
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>




<div class="container">
 <?php
 //display nav
 $element->displayNav();

 //display main body
echo "<br>";
echo "<br>";
echo "<br>";

 ?>


 <!--modal edit book-->
 <?php
 $book->displayEditBook($bookid);
 ?>

</div>
<!--End main body-->
<?php
	
	$element->getComponents();
?>
  
</body>
</html>


