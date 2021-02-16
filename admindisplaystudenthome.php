<?php
	include("includes/autoloader.inc.php");
	include_once("sessionchecknouser.php");
	include_once("sessioncheckadmin.php");
	// echo $_SESSION['account_id'];
	$user = new Users();
	$book = new Books();
	$element = new Elements();
	$studentid = $_GET['studentid'];
	
?>


<!DOCTYPE html>
<html>
<head>
	<title>Library System</title>
	
</head>
<body class="bg-right">
	<!--arrow up-->
	<!-- <a href="#top"><span class="arrow-up">⬆</span></a> -->
	<!--arrow up-->



 <?php
 //display nav
 $element->displayNav();
 echo "<br>";
 //display main body

 ?>
 <div class="container-fluid mt-5">
				<div class="col-md-10 col-11 mx-auto">
					<nav aria-label="breadcrumb" class="mb-3">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">
								<?php
								
									//logic to display the name of the student
								$user = new Users();
								$accountid = $studentid;
								$getallinfo = $user->getAllUserInfoInnerJoin($accountid);
								if($getallinfo){
									foreach ($getallinfo as $user) {
									echo $user['name']."'s Profile";
									}	
								}else{
									?>
									<h5 class="text-danger">Data expired.</h5>
									<?php
								}
								?>
							</li>

						</ol>
					</nav>

					<div class="row">
						<!--left side nav-->
						<div class="col-lg-3 col-md-4 d-md-block">
							<div class="card bg-common card-left">
								<div class="card-body">
									<nav class="nav d-md-block d-none" id="sidebarnav">
										<a class="nav-link" aria-current="page" href="#profile"><i class="fas fa-user mr-1"></i> Profile
										</a>
										<a class="nav-link text-primary" aria-current="page" id="clickpending" href="#pending"><?php 
										$user = new Users();
										$accountid = $studentid;
										echo $user->studentPendingCounter($accountid);
										?> <i class="fas fa-book mr-1"></i>Pending books
										</a>
										<a class="nav-link text-success" id="clickborrowed" aria-current="page" href="#borrowed"><?php 
										$user = new Users();
										$accountid = $studentid;
										echo $user->studentBorrowedCounter($accountid);
										?> <i class="fas fa-book mr-1"></i>Borrowed books
										</a>

										<a class="nav-link text-info" aria-current="page" id="clickreturned" href="#returned"><?php 
										$user = new Users();
										$accountid = $studentid;
										echo $user->studentReturnedCounter($accountid);
										?> <i class="fas fa-book mr-1"></i>Returned books
										</a>

										<a class="nav-link text-danger" aria-current="page" id="clickoverdue" href="#overdue"><?php $user = new Users();
										$accountid = $studentid;
										echo $user->studentOverdueCounter($accountid);
										?> <i class="fas fa-book mr-1"></i>Overdue books
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
											$accountid = $studentid;
											echo $user->studentPendingCounter($accountid);
											?> <i class="fas fa-book mr-1"></i>
											</a>

											<a class="nav-link text-success" aria-current="page" href="#borrowed"><?php 
											$user = new Users();
											$accountid = $studentid;
											echo $user->studentBorrowedCounter($accountid);
											?> <i class="fas fa-book mr-1"></i>
											</a>

											<a class="nav-link text-info" aria-current="page" href="#returned"><?php 
											$user = new Users();
											$accountid = $studentid;
											echo $user->studentReturnedCounter($accountid);
											?> <i class="fas fa-book mr-1"></i>
											</a>

											<a class="nav-link text-danger" aria-current="page" href="#overdue"><?php 
											$user = new Users();
											$accountid = $studentid;
											echo $user->studentOverdueCounter($accountid);
											?> <i class="fas fa-book mr-1"></i>
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
												<div class="card-header">Pending Books</div>
												<div class="card-body">
													<h5 class="card-title">
														<?php 
														$user = new Users();
														$accountid = $studentid;
														echo $user->studentPendingCounter($accountid);
														?>
													</h5>

												</div>
											</a>
											</div>

											<div class="card text-white bg-success mb-3" style="max-width: 18rem;">
												<a data-toggle = "tab" class="cardlink" href="#borrowed">
												<div class="card-header">Borrowed Books</div>
												<div class="card-body">
													<h5 class="card-title">
														<?php 
														$user = new Users();
														$accountid = $studentid;
														echo $user->studentBorrowedCounter($accountid);
														?>
													</h5>

												</div>
												</a>
											</div>

											<div class="card text-white bg-info mb-3" style="max-width: 18rem;">
												<a data-toggle = "tab" class="cardlink" href="#returned">
												<div class="card-header">Returned Books</div>
												<div class="card-body">
													<h5 class="card-title">
														<?php 
														$user = new Users();
														$accountid = $studentid;
														echo $user->studentReturnedCounter($accountid);
														?>
													</h5>

												</div>
												</a>
											</div>										
										</div>

										<div class="card-columns">
											<div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
												<a data-toggle = "tab" class="cardlink" href="#overdue">
												<div class="card-header">Overdue Books</div>
												<div class="card-body">
													<h5 class="card-title">
														<?php 
														$user = new Users();
														$accountid = $studentid;
														echo $user->studentOverdueCounter($accountid);
														?>
													</h5>

												</div>
												</a>
											</div>

											<div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
												<a data-toggle = "tab" class="cardlink" href="#notifications">
												<div class="card-header">Notifications</div>
												<div class="card-body">
													<h5 class="card-title">
														<?php 
														$user = new Users();
														$accountid = $studentid;
														echo $user->studentNotificationsCounter($accountid);
														?>
													</h5>

												</div>
												</a>
											</div>
										</div>

										<div class="card-body tab-content border-0">
											<?php
											$user = new Users();
											$user->displayStudentProfile($accountid);
											?>
										</div>

										<div class="card-body tab-content border-0">
											<?php
											$user->studentShowProfileEdit($accountid);
											?>
										</div>
										<!--end profile section-->

										<!--end profile section-->
									</div>

									
									<!-- actual pending data -->
									<div class="tab-pane" id = "pending">
										<h1 class="text-info">
											Pending books
											<?php 
											$user = new Users();
											$accountid = $studentid;
											echo $user->studentPendingCounter($accountid);
											?>
										</h1>
										<?php
										$book = new Books();
										$book->studentDisplayPendingBooks($accountid);
										?>
									</div>



									<!-- actual borrowed data -->
									<div class="tab-pane" id = "borrowed">
										<h1 class="text-success">
											Borrowed books
											<?php 
											$user = new Users();
											$accountid = $studentid;
											echo $user->studentBorrowedCounter($accountid);
											?>
										</h1>

										<?php
										$book = new Books();
										$book->studentDisplayBorrowedBooks($accountid);
										?>
									</div>


									<!-- actual returned data -->
									<div class="tab-pane" id = "returned">
										<h1 class="text-info">
											Returned books
											<?php 
											$user = new Users();
											$accountid = $studentid;
											echo $user->studentReturnedCounter($accountid);
											?>
										</h1>
										<?php
										$book = new Books();
										$book->studentDisplayReturnedBooks($accountid);
										?>
										
									</div>


									

									<!-- actual overdue data -->
									<div class="tab-pane" id = "overdue">
										<h1 class="text-danger">
											Overdue books
											<?php 
											$user = new Users();
											$accountid = $studentid;
											echo $user->studentOverdueCounter($accountid);
											?>
										</h1>

										<?php
										$book = new Books();
										$book->studentDisplayOverdueBooks($accountid);
										?>
										
									</div>

									<!-- actual notifications data -->
									<div class="tab-pane " id = "notifications">
										<h1 class="text-warning">
											Notifications
											<?php 
											$user = new Users();
											$accountid = $studentid;
											echo $user->studentNotificationsCounter($accountid);
											?>
										</h1>

										<?php $user->displayNotifications($accountid);?>
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

			

			default: 
			
			break;
		}

		
		
	
	}	
</script>

