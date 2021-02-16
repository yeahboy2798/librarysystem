<?php
include_once("includes/autoloader.inc.php");
include_once("sessionchecknouser.php");

?>

<!DOCTYPE html>
<html>
<head>
	<title>Books</title>
</head>
<body>
<?php
	 $element->displayNav();
?>

<div class="container">
	<br>
	<h2>Books</h2>



	<!--evaluate if the user is admin or student to know which function to call upon rendering content-->
	<?php
	$field = "account_id";
	$session = $_SESSION['account_id'];
	$checkuser = $user->getUserInfo($field, $session);
	foreach ($checkuser as $row) {
		$accounttype = $row['accountType'];
	}

	if($accounttype == "admin"){
		$book->adminDisplayBooks();
	}
	else{
		$studentid = $_SESSION['account_id'];
		$book->studentDisplayBooks($studentid);
	}

	?>

</div>
	
<?php
	$element->getComponents();
?>
</body>
</html>


