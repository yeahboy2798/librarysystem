<?php
include("includes/autoloader.inc.php");
include_once("sessionchecknouser.php");

$accountid = $_SESSION['account_id'];
@$bookid = $_GET['bookid'];
// $book->borrowBook($bookid);

//check if the user is student or admin.
//call the admin borrow function if user is admin. otherwise, call the student borrow function
$rows = $user->fetchUsersTable($accountid);
if(!empty($rows)){
	foreach ($rows as $row) {
		$role = $row['accountType'];

	}
	
	if($role == "admin"){
		// call the admin method for borrowing books
		if(isset($_POST['btn'])){
			$bookid = $_POST['bookid'];
			$studentid = $_POST['studentid'];
			$book->adminBorrow($bookid, $studentid);
		}

		
	}
	else{
		//call the student method for borrowing books
		$book->studentBorrow($bookid);

	}
}
else{
	echo "empty";
}




?>