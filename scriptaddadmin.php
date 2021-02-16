<?php
include("includes/autoloader.inc.php");
include_once("sessionchecknouser.php");
include_once("sessioncheckadmin.php");

	

	if(isset($_POST['btnaddadmin'])){
			$adminid = $_POST['adminid'];
			$adminusername = $_POST['adminusername'];
			$adminpassword = $_POST['adminpassword'];
			$adminconfirmpassword = $_POST['adminconfirmpassword'];
			$adminoldpassword = $_POST['adminoldpassword'];
			
		 // $user->signUp($studentid, $fname, $course, $year, $section, $username, $password, $confirmpassword);
			$user->adminValidateAddProfile($adminid, $adminusername, $adminpassword, $adminconfirmpassword, $adminoldpassword);

	}
?>