<?php
	include("includes/autoloader.inc.php");

	if(@$_SESSION['account_id']){
		header("location: home.php");
	}
	else{
		
	}

	// echo $_SESSION['account_id'];

		
?>


<!DOCTYPE html>
<html>
<head>
	<title>Library System</title>


</head>
<body>
	<!--arrow up-->
	<!-- <a href="#top"><span class="arrow-up">⬆</span></a> -->
	<!--arrow up-->
<!--nav-->
  <?php
  $elements = new Elements();
  $elements->displayNav();
  ?>
 <!--end nav-->

<br><br><br>

 <!--begin main body-->
 <div class="container">
 	

 
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Login</h5>
       
      </div>
      <div class="modal-body">
        <form action="index.php" id="loginform" method="POST">
	 			<input type="text" class="form-control" placeholder="Username" id="txtusername" name="txtusername"><br>
	 			<input type="password" class="form-control" name="txtpassword" id="txtpassword" placeholder="Password"><br>
	 			<input type="submit" name="btnlogin" id="btnlogin" class="btn btn-primary" value="Login">

	 			<span class="message"></span>

	 			<p>Don't have an account? Click <a href="#" data-toggle="modal" data-target="#createaccountmodal">
	 			here </a> to create.</p>
	 	</form>
      </div>
     
    </div>
  </div>


</div>

<!--modal-->
<form action="index.php" id="frmCreateAccount" method="POST">
	
	<!-- Modal -->
	<div class="modal fade" id="createaccountmodal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="staticBackdropLabel">Create student account</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>Student ID</label>
						<input type="text" name="txtsstudentid" id="txtsstudentid" class="form-control" placeholder="Enter student id">
					</div>

					<div class="form-group">
						<label>Full name</label>
						<input type="text" name="txtsfname" id="txtsfname" class="form-control" placeholder="Enter full name">
					</div>
					<div class="form-group">
						<label>Course</label>
						<input type="text" name="txtscourse" id="txtscourse" class="form-control" placeholder="Enter course">
					</div>

					<div class="form-group">
						<label>Year</label>
						<input type="number" name="txtsyear" id="txtsyear" class="form-control" placeholder="Enter year level">
					</div>

					<div class="form-group">
						<label>Section</label>
						<input type="text" name="txtssection" id="txtssection" class="form-control" placeholder="Enter section">
					</div>

					<div class="form-group">
						<label>Username</label>
						<input type="text" name="txtsusername" id="txtsusername" class="form-control" placeholder="Enter username">
					</div>

					<div class="form-group">
						<label>Password</label>
						<input type="password" name="txtspassword" id="txtspassword" class="form-control" placeholder="Enter password">
					</div>

					<div class="form-group">
						<label>Confirm Password</label>
						<input type="password" name="txtsconfirmpassword" id="txtsconfirmpassword" class="form-control" placeholder="Confirm password">
					</div>      	


				</div>

				<div class="for-group">
					<span class="signupmessage"></span>
				</div>

				<div class="modal-footer">
					
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" id="btnsignup" value="signup" class="btn btn-success">Create account</button>
				</div>


			</div>

		</div>
	</div>
</form>
 	
 
<!--End main body-->
<?php
	$getcomponents = new Elements();
	$getcomponents->getComponents();


?>
  
</body>
</html>




