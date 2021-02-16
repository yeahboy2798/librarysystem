<?php
include("includes/autoloader.inc.php");
include_once("sessionchecknouser.php");

$bookid = $_GET['bookid'];
$borrowerid = $_SESSION['account_id'];


// $book->borrowBook($bookid);

//check if the user is student or admin.
//call the admin borrow function if user is admin. otherwise, call the student borrow function
$rows = $user->getAllUserInfo();
if(!empty($rows)){
	foreach ($rows as $row) {
		$role = $row['accountType'];

	}
	if($role == "admin"){
		
		// $book->adminBorrow($bookid);
	}
	else{
		//query to extract borrowid
		$data = $book->checkBookPending($bookid, $borrowerid);
		foreach ($data as $row) {
			$borrowid = $row['borrowid'];
		}
		$book->studentCancel($borrowid);
	}
}
else{
	echo "empty";
}


?>