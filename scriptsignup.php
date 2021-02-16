<?php
	include("includes/autoloader.inc.php");


	if(isset($_POST['btnsignup'])){
		 $studentid = $_POST['txtsstudentid'];
		 $fname = $_POST['txtsfname'];
		 $course = $_POST['txtscourse'];
		 $year = $_POST['txtsyear'];
		 $section = $_POST['txtssection'];
		 $username = $_POST['txtsusername'];
		 $password = $_POST['txtspassword'];
		 $confirmpassword = $_POST['txtsconfirmpassword'];

		 $user->signUp($studentid, $fname, $course, $year, $section, $username, $password, $confirmpassword);
	}
?>