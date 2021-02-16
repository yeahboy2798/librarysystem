<?php
	include("includes/autoloader.inc.php");
	include_once("sessionchecknouser.php");
	include_once("sessioncheckadmin.php");
	
	if(isset($_POST['btneditadmin'])){
		$recordid = $_POST['recordid'];
		$oldadminid = $_POST['oldadminid'];
		$oldadminuname = $_POST['oldadminuname'];
		$adminid= $_POST['adminid'];
		$adminusername= $_POST['adminusername'];
		$adminnewpassword= $_POST['adminnewpassword'];
		$adminconfirmpassword= $_POST['adminconfirmpassword'];
		$adminoldpassword= $_POST['adminoldpassword'];
		
		//call the save edited profile function in users class
		$user->adminValidateEditedProfile($recordid, $oldadminid, $oldadminuname,$adminid,$adminusername,$adminnewpassword,$adminconfirmpassword,$adminoldpassword);
		}


	
?>