<?php
	include("includes/autoloader.inc.php");
	include_once("sessionchecknouser.php");
	include_once("sessioncheckadmin.php");
	// echo $_SESSION['account_id'];
	$user = new Users();
	$book = new Books();
	$element = new Elements();
	
	
?>


<!DOCTYPE html>
<html>
<head>
	<title>Library System</title>
	
</head>
<body class="bg-right" onload="clickDiv()">
	<!--arrow up-->
	<!-- <a href="#top"><span class="arrow-up">⬆</span></a> -->
	<!--arrow up-->



 <?php
 //display nav
 $element->displayNav();
 //display main body
 echo "<br>";
 $adminid = $_SESSION['account_id'];
 ?>
 <div class="container-fluid mt-5">
				<div class="col-md-10 col-11 mx-auto">
					<nav aria-label="breadcrumb" class="mb-3">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">Admin Panel</li>
						</ol>
					</nav>

					<div class="row">
						<!--left side nav-->
						<div class="col-lg-3 col-md-4 d-md-block">
							<div class="card bg-common card-left">
								<div class="card-body">
									<nav class="nav d-md-block d-none" id="sidebarnav">
										<a class="nav-link" aria-current="page" href="adminhome.php"><i class="fas fa-user mr-1"></i> Profile
										</a>
										<a class="nav-link text-primary" aria-current="page" id="clickpending" href="#pending"><?php 
										$user = new Users();
										echo $user->adminPendingCounter();
										?> <i class="fas fa-book mr-1"></i>Pending books
										</a>
										<a class="nav-link text-success" aria-current="page" id="clickborrowed" href="#borrowed" ><?php 
										$user = new Users();
										echo $user->adminBorrowedCounter();
										?> <i class="fas fa-book mr-1"></i>Borrowed books
										</a>

										 <a class="nav-link text-info" aria-current="page" id="clickreturned" href="#returned"><?php 
										$user = new Users();
										echo $user->adminReturnedCounter();
										?> <i class="fas fa-book mr-1"></i>Returned books
										</a>

										 <a class="nav-link text-danger" aria-current="page" id="clickoverdue" href="#overdue"><?php 
										$user = new Users();
										echo $user->adminOverdueCounter();
										?> <i class="fas fa-book mr-1"></i>Overdue books
										</a>
										<a class="nav-link text-warning" id="clicknotifications" href="#notifications">
										<?php 
										$user = new Users();
										$accountid = $adminid;
										echo $user->adminNotificationsCounter($accountid);
										?>  <i class="fas fa-bell mr-1"> </i>Notifications
										</a>

										<hr><hr>
										<a class="nav-link" aria-current="page" id="clickstudents" href="#students"><?php 
										$user = new Users();
										$accountid = $adminid;
										echo $user->adminStudentCounter();
										?>  <i class="fas fa-user mr-1"></i> Students
										</a>

										<a class="nav-link" aria-current="page" id="clickadmins" href="#admins"><?php 
										$user = new Users();
										echo $user->adminAdminCounter();
										?>  <i class="fas fa-user-cog"></i> Admins 
										</a>




										<hr><hr>
										<a class="nav-link" aria-current="page" id="clickallbooks" href="#allbooks">
										<?php 
										$book = new Books();
										echo $book->allBooksCounter();
										?>  <i class="fas fa-book"> </i> All Books
										</a>



										<hr><hr>
										<a class="nav-link" aria-current="page" id="clickpublishers" href="#publishers"> <?php 
										$publisher = new Publisher();
										echo $publisher->publisherCounter();
										?> <i class="fas fa-newspaper"></i>Publishers
										</a>

										<hr><hr>
										<a class="nav-link text-danger" aria-current="page" id="clickcleardata" href="#cleardata"> 
										<i class="fas fa-trash-alt"></i> Clear Data
										</a>
									</nav>
								</div>
							</div>
						</div>

						<!--right side div starts-->
						<div class="col-lg-9 col-md-9" id="dashboardcontainer">
							<div class="card">
								<div class="card-header border-bottom-3 mb-3 d-md-none">
									<ul class="nav nav-tabs card-header-tabs nav-fill">

								<li class="nav-item">



										<a data-toggle = "tab" class="nav-link active" aria-current="page" href="#profile"><i class="fas fa-user mr-1"></i></a>
										</li>

										
											<a class="nav-link text-primary " aria-current="page" href="#pending"><?php 
											$user = new Users();
											echo $user->adminPendingCounter();
										?>  <i class="fas fa-book mr-1"></i></a>

											
											<a class="nav-link text-success" aria-current="page" href="#borrowed"><?php 
											$user = new Users();
											echo $user->adminBorrowedCounter();
											?>  <i class="fas fa-book mr-1"></i>
											</a>

											 <a class="nav-link text-info" aria-current="page" href="#returned"><?php 
											$user = new Users();
											echo $user->adminReturnedCounter();
											?> <i class="fas fa-book mr-1"></i>
											</a>

											 <a class="nav-link text-danger" aria-current="page" href="#overdue"><?php 
											$user = new Users();
											echo $user->adminOverdueCounter();
											?> <i class="fas fa-book mr-1"></i>
											</a>

											 <a class="nav-link text-warning" aria-current="page" href="#notifications"><?php 
											$user = new Users();
											$accountid = $adminid;
											echo $user->adminNotificationsCounter($accountid);
											?> <i class="fas fa-bell mr-1"></i>
											</a>

											 <a class="nav-link text-primary" aria-current="page" href="#students"><?php 
											$user = new Users();
											$accountid = $adminid;
											echo $user->adminStudentCounter();
											?> <i class="fas fa-user mr-1"></i>
											</a>

											 <a class="nav-link text-primary" aria-current="page" href="#admins"><?php 
											$user = new Users();

											echo $user->adminAdminCounter();
											?> <i class="fas fa-user-cog mr-1"></i>
											</a>

											<a class="nav-link" aria-current="page" href="#allbooks"><?php 
											$book = new Books();
											echo $book->allBooksCounter();
											?>  <i class="fas fa-book mr-1"></i>
											</a>

											<a class="nav-link" aria-current="page" href="#publishers"><?php 
											$publisher = new Publisher();
											echo $publisher->publisherCounter();
											?> <i class="fas fa-newspaper mr-1"></i>
											</a>

										
										
									</ul>
								</div>

								<!-- actual data which is live -->
								<div class="card-body tab-content border-0">
									<!-- actual profile data -->
									<div class="tab-pane active" id = "profile">

										<!-- displays all info including borrowed books, pending books, currently borrowed books, retured books -->


										<div class="card-columns">
											

											<div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
												<a data-toggle = "tab" class="cardlink" href="#pending">
												<div class="card-header"><i class="fas fa-book mr-1"></i> Pending Books</div>
												<div class="card-body">
													<h5 class="card-title">
														<?php 
														$user = new Users();
														echo $user->adminPendingCounter();
														?>
													</h5>

												</div>
											</a>
											</div>

											<div class="card text-white bg-success mb-3" style="max-width: 18rem;">
												<a data-toggle = "tab" class="cardlink" href="#borrowed">
												<div class="card-header"><i class="fas fa-book mr-1"></i> Borrowed Books</div>
												<div class="card-body">
													<h5 class="card-title">
														<?php 
														$user = new Users();
														echo $user->adminBorrowedCounter();
														?>
													</h5>

												</div>
												</a>
											</div>

											<div class="card text-white bg-info mb-3" style="max-width: 18rem;">
												<a data-toggle = "tab" class="cardlink" href="#returned">
												<div class="card-header"><i class="fas fa-book mr-1"></i> Returned Books</div>
												<div class="card-body">
													<h5 class="card-title">
														<?php 
														$user = new Users();
														echo $user->adminReturnedCounter();
														?>
													</h5>

												</div>
												</a>
											</div>										
										</div>

										<div class="card-columns">
											<div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
												<a data-toggle = "tab" class="cardlink" href="#overdue">
												<div class="card-header"><i class="fas fa-book mr-1"></i> Overdue Books</div>
												<div class="card-body">
													<h5 class="card-title">
														<?php 
														$user = new Users();
														echo $user->adminOverdueCounter();
														?>
													</h5>

												</div>
												</a>
											</div>

											<div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
												<a data-toggle = "tab" class="cardlink" href="#notifications">
												<div class="card-header"><i class="fas fa-bell mr-1"></i> Notifications</div>
												<div class="card-body">
													<h5 class="card-title">
														<?php 
														$user = new Users();
														$accountid = $adminid;
														echo $user->adminNotificationsCounter($accountid);
														?>
													</h5>

												</div>
												</a>
											</div>

											<div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
												<a data-toggle = "tab" class="cardlink" href="#students">
												<div class="card-header"><i class="fas fa-user mr-1"></i> Students</div>
												<div class="card-body">
													<h5 class="card-title">
														<?php 
														$user = new Users();
														$accountid = $adminid;
														echo $user->adminStudentCounter();
														?>
													</h5>

												</div>
												</a>
											</div>


										</div>

										<div class="card-columns">
											<div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
												<a data-toggle = "tab" class="cardlink" href="#admins">
												<div class="card-header"><i class="fas fa-user-cog"></i> Admins</div>
												<div class="card-body">
													<h5 class="card-title">
														<?php 
														$user = new Users();
														
														echo $user->adminAdminCounter();
														?>
													</h5>

												</div>
												</a>
											</div>

											<div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
												<a data-toggle = "tab" class="cardlink" href="#books">
												<div class="card-header"><i class="fas fa-book"></i> Books</div>
												<div class="card-body">
													<h5 class="card-title">
														<?php 
														$book = new Books();
														echo $book->allBooksCounter();
														?>
													</h5>

												</div>
												</a>
											</div>

											<div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
												<a data-toggle = "tab" class="cardlink" href="#publishers">
												<div class="card-header"><i class="fas fa-newspaper"></i> Publishers</div>
												<div class="card-body">
													<h5 class="card-title">
														<?php 
														$publisher = new Publisher();
														echo $publisher->publisherCounter();
														?>
													</h5>

												</div>
												</a>
											</div>
										</div>

										<div class="card-body tab-content border-0">
											<?php
											$user = new Users();
											$user->displayAdminProfile($accountid);

											?>
										</div>

										<div class="card-body tab-content border-0">
											<?php
											$user->adminShowProfileEdit($accountid);
											?>
											<br>
											<?php
											$user->adminShowAddProfile();
											?>
										</div>







										<!--end profile section-->
									</div>

									
									<!-- actual pending data -->
									<div class="tab-pane" id = "pending">
										<h1 class="text-primary">
											Pending books
											<?php 
											$user = new Users();
											echo $user->adminPendingCounter();
											?>
										</h1>

										<?php
										$book = new Books();
										$book->adminDisplayPendingBooks();
										?>
									</div>



									<!-- actual borrowed data -->
									<div class="tab-pane" id = "borrowed">
										<h1 class="text-success">
											Borrowed books
											<?php 
											$user = new Users();
											echo $user->adminBorrowedCounter();
											?>
										</h1>

										<?php
										$book = new Books();
										$book->adminDisplayBorrowedBooks();
										?>
										
									</div>

									<!-- actual returned data -->
									<div class="tab-pane" id = "returned">
										<h1 class="text-info">
											Returned books
											<?php 
											$user = new Users();
											echo $user->adminReturnedCounter();
											?>
										</h1>

										<?php
										$book = new Books();
										$book->adminDisplayReturnedBooks();
										?>
										
									</div>

									<!-- actual overdue data -->
									<div class="tab-pane" id = "overdue">
										<h1 class="text-danger">
											Overdue books
											<?php 
											$user = new Users();
											
											echo $user->adminOverdueCounter();
											?>
										</h1>

										<?php
										$book = new Books();
										$book->adminDisplayOverdueBooks();
										?>
										
									</div>


									<!-- actual edit books data -->
									<div class="tab-pane" id = "editbooks">
										<h1 class="text-info">
											Edit books
										</h1>
									</div>

									<!-- actual notifications data -->
									<div class="tab-pane " id = "notifications">
										<h1 class="text-warning">
											Notifications
											<?php 
											$user = new Users();
											$accountid = $adminid;
											echo $user->adminNotificationsCounter($accountid);
											?>
										</h1>

										<?php echo "
										<a href='scriptnotificationmarkallasread.php?userid=$accountid' class = 'btn btn-success'><i class='fas fa-check'></i> Mark all as read</a>
										";?>

										<br><br>

										<?php
										$user->displayAdminNotifications();
										?>
									</div>

									<!-- actual students/users data -->
									<div class="tab-pane " id = "students">
										<h1 class="text-primary">
											Students
											<?php 
											$user = new Users();
											$accountid = $adminid;
											echo $user->adminStudentCounter();
											?>

										</h1>
										<table class="table display dataTable" style="width:100%">
											<thead>
												<tr>
													<th>Student ID</th>
													<th>Name</th>
													<th>Course</th>
													<th>Year and Section</th>
													<th>View Profile</th>
												</tr>
											</thead>
											<tbody>
												<?php
												//php code here to get info of the student
												$user = new Users();
												$getalluser = $user->adminGetUsersInfo();
												if(!empty($getalluser)){
													foreach ($getalluser as $user) {
														$studentid = $user['studentid'];
														echo "<tr>";
														echo "<td>".$user['studentid']."</td>";
														echo "<td>".$user['name']."</td>";
														echo "<td>".$user['course']."</td>";
														echo "<td>".$user['yearSection']."</td>";
														echo "<td><a href = 'admindisplaystudenthome.php?studentid=$studentid' class = 'btn btn-success'>View profile</a></td>";

														echo "</tr>";
													}
												}
												else{
													echo "No user at the moment";
												}
												

												?>
											</tbody>
										</table>
										
									</div>

									<!-- actual admins data -->
									<div class="tab-pane " id = "admins">
										<h1 class="text-primary">
											Admins
											<?php 
											$user = new Users();
											$accountid = $adminid;
											echo $user->adminAdminCounter();
											?>
										</h1>

										<table class="table display dataTable" style="width:100%">
											<thead>
												<tr>
													<th>ADMIN ID</th>
													<th>Username</th>
													<th>Date Created</th>
													<th>Last Edited</th>
													
												</tr>
											</thead>
											<tbody>
												<?php
												//php code here to get info of the admin
												$user = new Users();
												$field = "accountType";
												$getalladmin = $user->getUserInfo($field, "admin");
												
												if(!empty($getalladmin)){
													foreach ($getalladmin as $user) {
														$adminid = $user['account_id'];
														echo "<tr>";
														echo "<td>$adminid</td>";
														echo "<td>".$user['username']."</td>";
														echo "<td>".$user['dateCreated']."</td>";
														echo "<td>".$user['dateModified']."</td>";

														echo "</tr>";
													}
												}
												else{
													echo "No user at the moment";
												}
												

												?>
											</tbody>
										</table>
										
									</div>

									<!-- actual books data -->
									<div class="tab-pane " id = "allbooks">
										<h1 class="text-primary">
											All Books
											<?php 
											$book = new Books();
											echo $book->allBooksCounter();
											?>
										</h1>

										<br>
										<?php
										//call function add books
										$book->displayAddBook();
										?>
										<br>
										<?php
										$book->adminDisplayBooks();
										?>
										
									</div>

									<!-- actual publishers data -->
									<div class="tab-pane " id = "publishers">
										<h1 class="text-primary">
											All Publishers
											<?php 
											$publisher = new Publisher();
											echo $publisher->publisherCounter();
											?>
										</h1>

										<br>
										
										<?php
										$publisher->displayAddPublisher();
										?>
										<?php
										?>
										<br>
										<br>
										<?php
										$publisher->displayPublishers();
										?>
										
									</div>

									<div class="tab-pane " id = "cleardata">
										<h1 class="text-danger">
											Clear data
											
										</h1>

										<br>

										<form action="scriptadmincleardata.php"id = "frmClearData">
											<div class="form-group">
											<label>Select Table</label>
												<select id = "seltable" class="form-control">
													<option value="">Select table</option>
													<option value="users">Users</option>
													<option value="book">Book</option>
													<option value="bookcount">Book Count</option>
													<option value="borrow">Borrow</option>
													<option value="notification">Notification</option>
													<option value="publisher">Publisher</option>
													<option value="all">All</option>
												</select>

											</div>

											<div class="form-group">
												<h5 class="text-danger">Required</h5>
											<label>Enter password</label>
												<input type="password" id="cleardataadminpassword" name="" class="form-control">
											</div>

											<div class="form-group">
											<input type="submit" name="" class="btn btn-danger" id="btncleardata" value="Go">

											<br><br>
											<span class="cleardatamessage"></span>
											</div>



										</form>
										
										
									</div>
								</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<!--End main body-->
<?php
	
	$element->getComponents();
?>
  
</body>
</html>


<!-- script to show current nav tab on reload --> 
<script type="text/javascript">
	function clickDiv(){
		var url = window.location.href;
		var id = url.substring(url.lastIndexOf('#') + 1);

		switch(id){
			case 'pending':
			jQuery('#clickpending').click();

			break;

			case 'borrowed':
			jQuery('#clickborrowed').click();
			break;

			case 'returned':
			jQuery('#clickreturned').click();
			break;

			case 'overdue':
			jQuery('#clickoverdue').click();
			break;

			case 'notifications':
			jQuery('#clicknotifications').click();
			break;

			case 'students':
			jQuery('#clickstudents').click();
			break;

			case 'admins':
			jQuery('#clickadmins').click();
			break;

			case 'allbooks':
			jQuery('#clickallbooks').click();
			break;

			case 'publishers':
			jQuery('#clickpublishers').click();
			break;

			case 'cleardata':
			jQuery('#clickcleardata').click();
			break;

			default: 
			
			break;
		}
	}	



</script>


