<?php
	include("includes/autoloader.inc.php");
	include_once("sessionchecknouser.php");
	include_once("sessioncheckadmin.php");
	if(isset($_POST['btneditstudentaccount'])){
		$recordid = $_POST['recordid'];
		$dbusername = $_POST['dbusername'];
		$dbaccountid = $_POST['dbaccountid'];
		$studentid = $_POST['studentid'];
		$editfname = $_POST['editfname'];
		$editcourse = $_POST['editcourse'];
		$edityear = $_POST['edityear'];
		$editsection = $_POST['editsection'];
		$editusername = $_POST['editusername'];
		$editpassword = $_POST['editpassword'];
		$editconfirmpassword = $_POST['editconfirmpassword'];
		@$spassword = $_POST['spassword'];
		$btneditstudentaccount = $_POST['btneditstudentaccount'];

		//call the function in users to validate the inputs
		$user->studentValidateEditedProfile($recordid, $dbusername,  $studentid, $dbaccountid, $editfname, $editcourse, $edityear, $editsection, $editusername, $editpassword, $editconfirmpassword, $spassword, $btneditstudentaccount);
	}
?>