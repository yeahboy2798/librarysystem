

<?php
	include_once("./includes/autoloader.inc.php");
?>



<?php
	class Elements{

		public function displayNav(){
			
			?>
				<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
					<a class="navbar-brand" href="index.php">Library System</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>

					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav mr-auto">
						</ul>

						<ul class="nav navbar-nav navbar-right">
       						<?php
								if(@$_SESSION['account_id'])
								{
									?>
									<li class="nav-item">
										<a class="nav-link" aria-current="page" href="index.php">Home <i class="fas fa-home"></i></a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="pageviewbooks.php">Books <i class="fas fa-book"></i></a>
									</li>

									<li class="nav-item">
										<a class="nav-link" href="home.php">
											<?php
											//get the user info
											$user = new Users();
											$accountid = $_SESSION['account_id'];

											/*evaluate if the user is admin or not
											if the user is admin, get the username. if the user is student, get full name
											*/
											$fld = "account_id";
											$getuserinfos = $user->getUserInfo($fld, $accountid);
											if($getuserinfos){
												foreach ($getuserinfos as $userinfo) {
												$accounttype = $userinfo['accountType'];
												$username = $userinfo['username'];
												$accountid = $userinfo['account_id'];
											}
											}
											else{
												?>
												<span class="text-danger"><i class='fas fa-user text-d'></i>Session expired</span>
												<?php
											}
											

											if(@$accounttype == "admin"){
												echo $username." <i class='fas fa-user'></i>";
											}
											else{
												$rows = $user->getAllUserInfoInnerJoin($accountid);
												if(!empty($rows)){
													foreach ($rows as $row) {
													$name = $row['name'];
													echo $name." <i class='fas fa-user'></i>";
												
												}
												}
											}

											

											?></a>
									</li>

									<li class="nav-item"><a class="nav-link" href="logout.php">Logout <i class="fas fa-sign-out-alt"></i></a></li>

								

									



									<?php
								}
								else{
									?>
									<li class="nav-item"><a class="nav-link" href="index.php">Login <i class="fas fa-user"></i></a></li>
									<?php
								}
								?>
     					 </ul>

					</div>


				</nav>
			<?php
		}

		public function getComponents(){
			?>


			<meta name="viewport" content="width=device-width, initial-scale=1.0">

			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
			
			<script
			src="https://code.jquery.com/jquery-3.5.1.min.js"
			integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
			crossorigin="anonymous"></script>
			
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />

			
			<!-- For data tables -->
			<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
			<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
			<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
			<script type="text/javascript" src="js/main.js"></script>
			<link rel="stylesheet" type="text/css" href="styles/style.css">
			<!-- js scripts here -->

				

				

			<?php

		}	
	}


?>
