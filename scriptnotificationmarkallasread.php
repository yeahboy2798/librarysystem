<?php
	include("includes/autoloader.inc.php");
	include_once("sessionchecknouser.php");
	$user = new Users();
	$userid = $_GET['userid'];

	$field = "account_id";
	$getinfo = $user->getUserInfo($field, $userid);
	foreach ($getinfo as $rowgetinfo) {
		$accounttype = $rowgetinfo['accountType'];
	}

	if($accounttype == "admin"){
		
		$user->markAllNotificationsAsRead("admin");
	}
	else{
		$user->markAllNotificationsAsRead($userid);
	}

	
?>