<?php
	include("includes/autoloader.inc.php");
	include_once("sessionchecknouser.php");
	$user = new Users();
	$userid = $_SESSION['account_id'];
	$notifid = $_GET['id'];

	//evaluate if the user is admin or student
	$field = "account_id";
	$getinfo = $user->getUserInfo($field, $userid);
	foreach ($getinfo as $rowgetinfo) {
		$accounttype = $rowgetinfo['accountType'];

			if($accounttype == "admin"){
			$user->adminMarkNotificationAsRead($notifid);

			}
			else{
			$user->markNotificationAsRead($userid, $notifid);
			}
	}

	

	
?>