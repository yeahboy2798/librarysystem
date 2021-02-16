<?php
	include_once("./includes/autoloader.inc.php");
	
?>

<?php
	class Users{
		private $server = "localhost";
		private $username = "root";
		private $password;
		private $db = "librarysys";
		private $conn;

		public function __construct(){
			try{
				$this->conn = new mysqli($this->server, $this->username, $this->password,$this->db);
			}catch(execption $e){
				echo "connection failed", $e->getMessage();
			}
		}

		//general functions
		public function getAllUserInfo(){
			$query = "SELECT * FROM account";
			$sql = $this->conn->query($query);
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
		}

		public function getSpecificBorrow($borrowid){
			$query = "SELECT * FROM borrow WHERE borrowid = '$borrowid'";
			$sql = $this->conn->query($query);
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
		}

		public function getUserInfo($field, $variable){
			//variable and name may vary depending on which is assigned
			
			$query = "SELECT * FROM account WHERE $field = '$variable'";
			$sql = $this->conn->query($query);
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
			

		}

		public function getUserInfoCount($field, $variable){
			//variable and name may vary depending on which is assigned
			
			$query = "SELECT * FROM account WHERE $field = '$variable'";
			$sql = $this->conn->query($query);
			@$count = mysqli_num_rows($sql);
			return $count;

		}

		public function fetchUsersTable($id){
			
			$query = "SELECT * FROM account WHERE account_id = '$id'";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
		}

		public function getAllUserInfoInnerJoin($accountid){
			// $query = "SELECT 'account.account_id', 'account.username', 'student.name', 'student.course', 'student.yearSection' INNER JOIN account ON 'account.account_id' = 'student.studentid'";

			$query = "SELECT * FROM `account` INNER JOIN `student` ON account.account_id = student.studentid WHERE account.account_id = '$accountid'";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
		}

		public function getDateToday(){
			$date1 = new DateTime("now", new DateTimeZone('America/New_York'));
			return $date = $date1->format('y-m-d');	
		}

		//validator
		//function to get data with 7 parameters
		public function fetchTable($tablename, $fldaccountid, $varaccountid, $fldrecordid, $varrecordid, $connector, $condition){
			$query = "SELECT * FROM $tablename WHERE $fldaccountid = '$varaccountid' $connector $fldrecordid $condition $varrecordid";
			$sql = $this->conn->query($query);
			return @$result = mysqli_num_rows($sql);
		}

		

		//all users
		//login and sign up
		public function loginUser($username, $password){
			$query = "SELECT * FROM account WHERE username = '$username' OR account_id = '$username'";
			$sql = $this->conn->query($query);
		
			if($sql){	
			$result = mysqli_num_rows($sql);
			if($result > 0){
				// password_verify();
				while($row = mysqli_fetch_assoc($sql)){
					$dbpassword = $row['password'];
					$verify = password_verify($password, $dbpassword);
					if($verify){
						//extract info
						foreach ($sql as $rowsql) {
						$account_id = $rowsql['account_id'];
						$accountType = $rowsql['accountType'];
						}
						
						$_SESSION['account_id'] = $account_id;
						if($accountType == "admin"){
							$redirect = "adminhome.php";
							$this->redirectHandler($redirect);
						}
						else{
							$redirect = "home.php";
							$this->redirectHandler($redirect);
						}
					}
						
					else{
						
						$error = "Wrong password";
						$this->errorHandler($error);
					}
				}
			}
			else{
				
				$error = "Username or studentid not found";
				$this->errorHandler($error);
			}
			}
			else{
				$error = "An error occured";
				$this->errorHandler($error);
			}
			
		}

		//sign up
		public function signUp($studentid, $fname, $course, $year, $section, $username, $password, $confirmpassword){
			//validate if student id is already taken
			$fldsid = "account_id";
			$checksid = $this->getUserInfo($fldsid, $studentid);
			//check if username is already taken
			$flduname = "username";
			$checkuname = $this->getUserInfo($flduname, $username);
			//validate if pass and confirm pass are the same
			$checkpass = $password == $confirmpassword;
			if($checksid){
				$error = "Student ID is already taken";
				$this->errorHandler($error);
			}
			if($checkuname){
				$error = "Username is already taken";
				$this->errorHandler($error);
			}
			if(!$checkpass){
				$error = "Passwords don't match";
				$this->errorHandler($error); 
			}

			//validate if all inputs have vlues
			$valuevalidate = $studentid && $fname && $course && $year && $section && $username && $password && $confirmpassword;
			if(!$valuevalidate){
				$error = "Input values to all fields";
				$this->errorHandler($error); 
			}
			//if all are satisfied
			if(!$checksid && !$checkuname && $checkpass && $valuevalidate){
				$savestudentid = preg_replace('/\s+/',' ',$studentid);
				$savefname = preg_replace('/\s+/',' ',$fname);
				$savecourse = preg_replace('/\s+/',' ',$course);
				$saveyear = preg_replace('/\s+/',' ',$year);
				$savesection = preg_replace('/\s+/',' ',$section);
				$saveyearsection = $saveyear." ".$savesection;
				$saveusername = preg_replace('/\s+/',' ',$username);
				//combine year and section		$saveusername = preg_replace('/\s+/',' ',$username);
				$savepassword = password_hash($password, PASSWORD_DEFAULT);
				
				//get date today
				$book = new Books();
				$today = $book->getDateToday();
				$querysaveaccount = "INSERT INTO account (account_id, username, password, dateCreated) VALUES ('$savestudentid', '$saveusername', '$savepassword', '$today')";
				$querysavestudentdata =  "INSERT INTO student (studentid, name, course, yearSection) VALUES ('$savestudentid', '$savefname', '$savecourse', '$saveyearsection')";

				$sqlsaveaccount = $this->conn->query($querysaveaccount);
				$sqlsavestudentdata = $this->conn->query($querysavestudentdata);
				if($sqlsaveaccount && $querysavestudentdata){
					//clear form inputs of frmCreateAccount form
					$formid = "#frmCreateAccount";
					$this->clearForm();
					echo "<h4 class = 'text-success'>Account has been created. Close this modal and login to your account.</h4>";
				}
				else{
					$error = "There was a problem";
					$this->errorHandler($error);
				}


			}

		}

		//add admin 
		public function adminShowAddProfile(){
			//display modal for admin
			?>
			<!--button to trigger the modal-->
			<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAddAdmin"><i class="fas fa-user-plus"></i> Create admin account</button>

			<form action="index.php" id="frmAddAdmin" method="POST">
				
				<!-- Modal to edit admin info -->
				<div class="modal fade" id="modalAddAdmin" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="staticBackdropLabel">Create admin account</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								
								<div class="form-group">
									<label>Admin ID</label>
									<input type="text" name="txtaddadminid" id="txtaddadminid" class="form-control">
								</div>

								<div class="form-group">
									<label>Username</label>
									<input type="text" name="txtaddadminusername" id="txtaddadminusername" class="form-control">
								</div>

								
								<div class="form-group">
									<label>Password</label>
									<input type="password" name="txtaddadminpassword"  id="txtaddadminpassword" class="form-control">
								</div>

								<div class="form-group">
									<label>Confirm password</label>
									<input type="password" name="txtaddadminconfirmpassword" id="txtaddadminconfirmpassword" class="form-control">
								</div>

								<h5 class="text-danger">Required</h5>
								<label>Enter your password</label>
									<input type="password" required="" name="txtaddaddminoldpassword" id="txtaddaddminoldpassword" class="form-control">
								
							</div>
							<div class="form-group">
									<span class="addprofilemessage"></span>
							</div>
							
							<div class="modal-footer">
								
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" id="btnAddAdmin" value="save" class="btn btn-success">Save</button>
							</div>


						</div>

					</div>
				</div>
			</form>
			<?php
		}

		public function adminValidateAddProfile($adminid, $adminusername, $adminpassword, $adminconfirmpassword, $adminoldpassword){
			//====================validate if admin id is already used by other users
			$fieldid = "account_id";
			$idvalidator1 = $this->getUserInfoCount($fieldid, $adminid);
			//validate if id has a value
			$idvalidator2 = !$adminid;
			//====================validate if username is already taken
			$fieldusername = "username";
			$usernamevalidator1 = $this->getUserInfoCount($fieldusername, $adminusername);
			//validate if username field has a value
			$usernamevalidator2 = !$adminusername;
			//======================validate if password fields has inputs
			$passvalidator1 = !$adminpassword;
			$passvalidator2 = !$adminconfirmpassword;
			//check if both password fields are matched
			$passvalidator3 = $passvalidator1 != $passvalidator2;
			//check if old password field has value
			$passvalidator4 = !$adminoldpassword;
			//check if the old password input is the same in the database
			$session = $_SESSION['account_id'];
			$sql1 = $this->fetchUsersTable($session);
			foreach ($sql1 as $row1) {
				$dboldpassword = $row1['password'];
			}
			$passvalidator5 = password_verify($adminoldpassword, $dboldpassword);

			//display errors simoultaneuosly
			if($idvalidator1){
				$error = "ID is already taken.";
				$this->errorHandler($error);
			}
			if($idvalidator2){
				$error = "Please input admin id.";
				$this->errorHandler($error);
			}
			if($usernamevalidator1){
				$error = "Username is already taken.";
				$this->errorHandler($error);
			}
			if($usernamevalidator2){
				$error = "Please input username.";
				$this->errorHandler($error);
			}
			if($passvalidator1){
				$error = "Please input password.";
				$this->errorHandler($error);
			}
			if($passvalidator2){
				$error = "Please confirm password.";
				$this->errorHandler($error);
			}
			if($passvalidator3){
				$error = "Passwords do not match.";
				$this->errorHandler($error);
			}
			if($passvalidator4){
				$error = "Please enter your current password.";
				$this->errorHandler($error);
			}
			if(!$passvalidator5){
				$error = "You've entered a wrong password";
				$this->errorHandler($error);
			}

			//validate one by one
			if($idvalidator1){
				
			}
			else if($idvalidator2){
				
			}
			else if($usernamevalidator1){
				
			}
			else if($usernamevalidator2){

			}
			else if($passvalidator1){
				
			}
			else if($passvalidator2){
				
			}
			else if($passvalidator3){
				
			}
			else if($passvalidator4){
				
			}
			else if(!$passvalidator5){
				
			}else{
				//calll the function to save the data
				$this->adminSaveAccount($adminid, $adminusername, $adminpassword);
			}





		}

		public function adminSaveAccount($adminid, $adminusername, $adminpassword){
			$today = $this->getDateToday();
			
			$savepass = password_hash($adminpassword, PASSWORD_DEFAULT);
			$query = "INSERT INTO account (account_id, username, password, dateCreated, accountType) VALUES ('$adminid', '$adminusername', '$savepass', '$today', 'admin')";
			$sql = $this->conn->query($query);
			if($sql){
				
				$this->clearForm();
				$success = "Success. A new admin account has been created.";
				$this->successHandler($success);
					
			}else{
				$error = "There's an error.";
				$this->errorHandler($error);
			}
		}

		//edit admin profile
		public function adminShowProfileEdit($accountid){
			//display modal for admin
			?>
			<!--button to trigger the modal-->
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalEditAdmin"><i class="fas fa-edit"></i> Edit profile</button>

			<form action="index.php" id="frmEditAdmin" method="POST">
				
				<!-- Modal to edit admin info -->
				<div class="modal fade" id="modalEditAdmin" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="staticBackdropLabel">Edit admin account</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<?php
								//logic to get the value of admin info in database
								$field = "account_id";
								$getuserinfo = $this->getUserInfo($field, $accountid);
								foreach ($getuserinfo as $row) {
									$recordid = $row['id'];
									$dbaccountid = $row['account_id'];
									$dbdusername = $row['username'];
								}
								?>
								<input type="hidden" id="txthiddenadminid" value="<?php echo $dbaccountid;?>" name="">
								<input type="hidden" id="txthiddenadminuname" value="<?php echo $dbdusername;?>" name="">
								<input type="hidden" id="txthiddenrecordid" value="<?php echo $recordid;?>" name="">
								<div class="form-group">
									<label>Admin ID</label>
									<input type="text" name="txtadminid" value="<?php echo $dbaccountid;?>" id="txtadminid" class="form-control">
								</div>

								<div class="form-group">
									<label>Username</label>
									<input type="text" name="txtadminusername" value="<?php echo $dbdusername;?>" id="txtadminusername" class="form-control">
								</div>

								<h5 class="text-success">Optional</h5>
								<div class="form-group">
									<label>New password</label>
									<input type="password" name="txtadminnewpassword"  id="txtadminnewpassword" class="form-control">
								</div>

								<div class="form-group">
									<label>Confirm new password</label>
									<input type="password" name="txtadminconfirmpassword" id="txtadminconfirmpassword" class="form-control">
								</div>

								<h5 class="text-danger">Required</h5>
								<div class="form-group">
									<label>Enter old password to continue</label>
									<input type="password" name="txtadminoldpassword" required="" id="txtadminoldpassword" class="form-control">
								</div>
								
							</div>
							<div class="form-group">
									<span class="editprofilemessage"></span>
							</div>
							
							<div class="modal-footer">
								
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" id="btnEditAdmin" value="edit" class="btn btn-success">Save</button>
							</div>


						</div>

					</div>
				</div>
			</form>
			<?php
		}
		//validate inputs form the edit admin profile modal
		public function adminValidateEditedProfile($recordid, $oldadminid, $oldadminuname,$adminid,$adminusername,$adminnewpassword,$adminconfirmpassword,$adminoldpassword){
			//validate if oldid == id in input
			$validateinputId = $oldadminid == $adminid;
			//validate if oldusername == admin uname in input
			$validateInputuname = $oldadminuname == $adminusername;
			//validate if the new password field has no input
			$valnewpass = $adminnewpassword;
			$valconfirmnewpass = $adminconfirmpassword;
			
			$passnovalue = !$adminnewpassword;
			$confirmpassnovalue = !$adminconfirmpassword;
			//evaluate if password and confirm password are the same
			$valmatchinputpassword = $valnewpass == $valconfirmnewpass;
			$fieldid = "id";
			$getuserinfo = $this->getUserInfo($fieldid, $recordid);
			foreach ($getuserinfo as $rowgetuserinfo) {
				$dbpass = $rowgetuserinfo['password'];
				
			}
			//validate if the old pasword in input is the same in pass in db
			$valpassdbpass = password_verify($adminoldpassword, $dbpass);

			// validate if the newly entered admin id is already used by another user
			$tablename = "account";
			$fldaccountid = "account_id";
			$varaccountid =  preg_replace('/\s+/',' ',$adminid); //needspreg
			$fldrecordid = "id";
			$varrecordid = $recordid;
			$connector = "AND";
			$condition = "!=";
			$validatedbid = $this->fetchTable($tablename, $fldaccountid, $varaccountid, $fldrecordid, $varrecordid, $connector, $condition);
			// validate if the newly entered admin username is already used by another user
			$fldusername = "username";
			$varusername = preg_replace('/\s+/',' ',$adminusername);
			$validatedbuname = $this->fetchTable($tablename, $fldusername, $varusername, $fldrecordid, $varrecordid, $connector, $condition);

			//start conditions to save
			//validate if the value of admin id is the same in db
			if($validateinputId){
				$saveadminid = $oldadminid;
			}else{
				$saveadminid = $adminid;
			}
			//validate if the value of admin username is the same in db
			if($validateInputuname){
				$saveusername = $oldadminuname;
			}else{
				$saveusername = $adminusername;
			}

			//validate if the admin password and confirm password has no value
			if($passnovalue && $confirmpassnovalue){
				//empty this to be conditioned later. this password cannot be saved because it is hashed
				$savepass = "";
			}else{
				$savepass = $adminnewpassword;
			}
			//display all errors according to these conditions
			if(!$adminid){
				$error = "Please input admin id";
				$this->errorHandler($error);
			}
			if(!$adminusername){
				$error = "Please input username";
				$this->errorHandler($error);
			}
			
			
			if($validatedbid > 0){
				$error = "This id is already used by an other user.";
				$this->errorHandler($error);
			}
			if($validatedbuname > 0){
				$error = "This username is already used by an other user.";
				$this->errorHandler($error);
			}

			if($adminnewpassword && !$adminconfirmpassword){
				$error = "Please input password confirmation";
				$this->errorHandler($error);
			}
			if(!$adminnewpassword && $adminconfirmpassword){
				$error = "Please input new password";
				$this->errorHandler($error);
			}
			if($adminnewpassword && $adminnewpassword && !$valmatchinputpassword){
				$error = "Passwords do not match";
				$this->errorHandler($error);
			}

			if(!$valpassdbpass){
				$error = "You've entered a wrong old password";
				$this->errorHandler($error);
			}

			//all in one condition to display success
			if(!$adminid){
				
			}
			else if(!$adminusername){
			}
			
			
			else if($validatedbid > 0){
				
			}
			else if($validatedbuname > 0){
				
			}

			else if($adminnewpassword && !$adminconfirmpassword){
				
			}
			else if(!$adminnewpassword && $adminconfirmpassword){
				
			}
			else if($adminnewpassword && $adminnewpassword && !$valmatchinputpassword){
				
			}

			else if(!$valpassdbpass){
				
			}else{
				//save the data to the database
				$this->adminSaveEditedProfile($recordid, $saveadminid, $saveusername, $savepass);

			}
		}

		//function to save edited admin profile
		private function adminSaveEditedProfile($recordid, $saveadminid, $saveusername, $savepass){
			//get the date today
			$modifieddate = $this->getDateToday();
			//escape characters
			$adminid = preg_replace('/\s+/',' ',$saveadminid);
			$adminusername = preg_replace('/\s+/',' ',$saveusername);
			$editedby = $adminusername;

			//create sql statement according to the condition if savepass has value or nothing
			if($savepass == ""){
				$query = "UPDATE account SET account_id = '$adminid', username = '$adminusername', dateModified = '$modifieddate', editedby = '$editedby' WHERE id = '$recordid'";
			}
			else{
				//hash password
				$pass = password_hash($savepass, PASSWORD_DEFAULT);
				$query = "UPDATE account SET account_id = '$adminid', username = '$adminusername', password = '$pass', dateModified = '$modifieddate', editedby = '$editedby' WHERE id = '$recordid'";

			}

			$sql = $this->conn->query($query);

			if($sql){
				
				$success = "Your account has been succcessfully edited. Please relogin your account";
				$this->successHandler($success);
				$this->timeoutRedirect();

			}
			else{
				$error = "An error occured";
				$this->errorHandler($error);
			}
		}

		//edit student profile

		public function studentShowProfileEdit($accountid){
			//get the user edit profile modal
			?>
			<!--button to trigger the modal-->
			<?php
			//logic here to display whether logout of edit
			$data = $this->getAllUserInfoInnerJoin($accountid);
			if($data){
				//display edit button
				?>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editstudentaccountmodal"> <i class="fas fa-edit"></i> Edit profile</button>
				<?php
			}
			else{//begin else
				//validate if the user is admin or not
				//if admin, don't logout
				$session = $_SESSION['account_id'];
				$sql1 = $this->fetchUsersTable($session);
				if($sql1){
					foreach ($sql1 as $rowsql1) {
						$accounttype = $rowsql1['accountType'];
					}
					if($accounttype == "admin"){
						?>
						<a class="btn btn-primary" href="adminhome.php">Home <i class="fas fa-home"></i></a>
						<?php
					}else{
							$this->timeoutRedirect();
						?>
						<a class="btn btn-primary" href="logout.php">Logout <i class="fas fa-sign-out-alt"></i></a>
						<?php
					}
				}

					$this->timeoutRedirect();
					?>
					<!--needs session expired-->
					<h1><i class="fas fa-user text-danger"> Session expired!</i></h1>
					<a class="btn btn-primary" href="logout.php">Logout <i class="fas fa-sign-out-alt"></i></a>
					<?php

				}//end of else
				?>
			
			<!--modal-->
			<form action="index.php" id="frmEditStudentAccount" method="POST">
				
				<!-- Modal -->
				<div class="modal fade" id="editstudentaccountmodal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="staticBackdropLabel">Edit student account</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<?php
								//logic here to fetch data from the database and inject to the inputs
								$fetchstudentinfo = $this->getAllUserInfoInnerJoin($accountid);
								foreach ($fetchstudentinfo as $row) {
									$recordid = $row['id'];
									$studentid = $row['account_id'];
									$username = $row['username'];
									$fullname = $row['name'];
									$course = $row['course'];
									$yearsection = $row['yearSection'];
									$extractyearsection = explode(" ", $yearsection);	
									$year = $extractyearsection[0];	
									$section = $extractyearsection[1];					
								}
								?>
								<input type="hidden" name="" id="studenthiddenid" value="<?php echo $recordid;?>">
								<input type="hidden" name="" id="studenthiddenaccountid" value="<?php echo $studentid;?>">
								<input type="hidden" name="" id="studenthiddenusername" value="<?php echo $username;?>">
								<div class="form-group">
									<label>Student ID</label>
									<input type="text" name="txteditstudentid" id="txteditstudentid" value="<?php echo $studentid;?>" class="form-control" placeholder="Enter student id">
								</div>

								<div class="form-group">
									<label>Full name</label>
									<input type="text" name="txteditfname" id="txteditfname" value="<?php echo $fullname;?>" class="form-control" placeholder="Enter full name">
								</div>
								<div class="form-group">
									<label>Course</label>
									<input type="text" name="txteditcourse" id="txteditcourse" value="<?php echo $course;?>" class="form-control" placeholder="Enter course">
								</div>

								<div class="form-group">
									<label>Year</label>
									<input type="number" name="txtedityear" id="txtedityear" value="<?php echo $year;?>" class="form-control" placeholder="Enter year level">
								</div>

								<div class="form-group">
									<label>Section</label>
									<input type="text" name="txteditsection" id="txteditsection" value="<?php echo $section;?>" class="form-control" placeholder="Enter section">
								</div>

								<div class="form-group">
									<label>Username</label>
									<input type="text" name="txteditusername" id="txteditusername" value="<?php echo $username;?>" class="form-control" placeholder="Enter username">
								</div>

								<h5 class="text-success">Optional</h5>
								<div class="form-group">
									<label>New Password</label>
									<input type="password" name="txteditpassword" id="txteditpassword" class="form-control" placeholder="Enter password">
								</div>

								<div class="form-group">
									<label>Confirm New Password</label>
									<input type="password" name="txteditconfirmpassword" id="txteditconfirmpassword" class="form-control" placeholder="Confirm password">
								</div>  

								<?php
								//insert logic here to display required and not. if the user is admin, don't display required
								//if the session user is admin, don't display required
								$field = "account_id";
								$session = $_SESSION['account_id'];

								$getuserinfo = $this->getUserInfo($field, $session);
								foreach ($getuserinfo as $row) {
									 $accounttype = $row['accountType'];
								}

								if($accounttype == "admin"){
									
								}
								else{
									?>
									<h5 class="text-danger">Required</h5>
									<div class="form-group">
										<label>Old Password</label>
										<input type="password" name="txtspassword" required id="txtspassword" class="form-control" placeholder="Enter password">
									</div> 
									<?php
								}

								?>
								   	


							</div>
							<div class="form-group p-10">
								<span class="editstudentprofilemessage"></span>
							</div> 
							
							<div class="modal-footer">
								
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" id="btneditstudentaccount" value="edit" class="btn btn-success">Save</button>
							</div>


						</div>

					</div>
				</div>
			</form>
			<?php
		}

		public function studentValidateEditedProfile($recordid, $dbusername,  $studentid, $dbaccountid, $editfname, $editcourse, $edityear, $editsection, $editusername, $editpassword, $editconfirmpassword, $spassword, $btneditstudentaccount){
			//validate student id 
			
			/*===================ID VALIDATOR==========================*/
			
			//01. validate if the student id is not used by other users
			$tablename = "account";
			$fldaccountid = "account_id";
			$varaccountid =  preg_replace('/\s+/',' ',$studentid); 
			$fldrecordid = "id";
			$varrecordid = $recordid;
			$connector = "AND";
			$condition = "!=";
			$idvalidator1 = $this->fetchTable($tablename, $fldaccountid, $varaccountid, $fldrecordid, $varrecordid, $connector, $condition);
			//0 means it is not used by other users
			//02. validate if the id has no value. 1 means id is empty
			$idvalidator2 = !$studentid;


			/*=======================FULL NAME VALIDATOR=====================*/
			$fnamevalidator1 = !$editfname;

			/*=======================COURSE VALIDATOR========================*/
			$coursevalidator1 = !$editcourse;

			/*=====================YEAR VALIDATOR============================*/
			$yearvalidator1 = !$edityear;

			/*=====================SECTION VALIDATOR============================*/
			$sectionvalidator1 = !$editsection;

			/*======================USERNAME VALIDATOR==================*/
			
			//validate if username is already used by other users
			$flduname = "username";
			$varuname =  preg_replace('/\s+/',' ',$editusername); 
			$unamevalidator1 = $this->fetchTable($tablename, $flduname, $varuname, $fldrecordid, $varrecordid, $connector, $condition);
			$unamevalidator2 = !$editusername;

			/*=====================INPUT PASSWORD VALIDATOR==============*/
			//check if input password is empty
			$inppassvalidator1 = !$editpassword;
			//check if input confirm is empty
			$inputconfirmvalidator1 = !$editconfirmpassword;
			//check if the passwords match
			$inputpasswordvalidator3 = $editpassword == $editconfirmpassword;

			/*=====================OLD PASSWORD VALIDATOR================*/
			//check if the password has no value
			$spasswordvalidator1 = !$spassword;
			//check if password in db matches with the spassword
			//insert a logic here to identify the session user. automatically set password 
			//validator into 1 if the user is admin. otherwise, use password_verify
			$session = $_SESSION['account_id'];
			$field = "account_id";
			if($sql = $this->getUserInfo($field, $session)){
				foreach ($sql as $row){
				$accountType = $row['accountType'];
				//we will use this for redirect purpose. 
				/*if the user is admin, redirect to admin page, if the 
				user is student, logout adn relogin*/
				if($accountType == "admin"){
					$passwordvalidator2 = 1;
				}
				else{
					$passval2query = $this->fetchUsersTable($dbaccountid);
					foreach ($passval2query as $rowpassval2query) {
						$dbpass = $rowpassval2query['password'];
					}
					$passwordvalidator2 = password_verify($spassword, $dbpass);
				}
				}
		}else{
			$error = "Your info has been changed by someone. Try again later.";
			$this->errorHandler($error);
		}	
			

			


			


			//run validators one by one
			if($idvalidator1){
				$error = "ID has been taken";
				$this->errorHandler($error);
			}
			if($idvalidator2){
				$error = "Please input student id";
				$this->errorHandler($error);
			}
			if($fnamevalidator1){
				$error = "Please input full name";
				$this->errorHandler($error);
			}
			if($coursevalidator1){
				$error = "Please input course";
				$this->errorHandler($error);
			}
			if($yearvalidator1){
				$error = "Please input year level";
				$this->errorHandler($error);
			}
			if($sectionvalidator1){
				$error = "Please input section";
				$this->errorHandler($error);
			}
			if($unamevalidator2){
				$error = "Please input username";
				$this->errorHandler($error);
			}
			if($editpassword && !$editconfirmpassword){
				$error = "Please enter password confirmation";
				$this->errorHandler($error);
			}
			if(!$editpassword && $editconfirmpassword){
				$error = "Please enter new password";
				$this->errorHandler($error);
			}
			if(!$inputpasswordvalidator3){
				$error = "Passwords do not match";
				$this->errorHandler($error);
			}
			if(!@$passwordvalidator2){
				$error = "You've entered a wrong old password";
				$this->errorHandler($error);
			}

			//combine all the validators
			if($idvalidator1){
				
			}
			else if($idvalidator2){
				
			}
			else if($fnamevalidator1){
				
			}
			else if($coursevalidator1){
				
			}
			else if($yearvalidator1){
				
			}
			else if($sectionvalidator1){
				
			}
			else if($unamevalidator2){
				
			}
			else if($editpassword && !$editconfirmpassword){
				
			}
			else if(!$editpassword && $editconfirmpassword){
				
			}
			else if(!$inputpasswordvalidator3){
				
			}
			else if(!$passwordvalidator2){
				
			}
			else{
				//call the function to save edited student info
				$this->studentSaveEditedProfile($recordid, $studentid, $dbaccountid, $editfname, $editcourse, $edityear, $editsection, $editusername, $editpassword, $passwordvalidator2);
			}

		}

		private function studentSaveEditedProfile($recordid, $studentid, $dbaccountid, $editfname, $editcourse, $edityear, $editsection, $editusername, $editpassword, $passwordvalidator2){
			/*condition to evaluate if the user is admin. if the user is student, old password is required
			if not, old password is not required

			validate also if the password field has a value, if no value, create another query
			*/
			$yearsection = $edityear." ".$editsection;
			$modifieddate = $this->getDateToday();
			$session = $_SESSION['account_id'];
			$field = "account_id";
			$sql = $this->getUserInfo($field, $session);
			foreach ($sql as $row){
				$accountType = $row['accountType'];
				//we will use this for redirect purpose. 
				/*if the user is admin, redirect to admin page, if the 
				user is student, logout adn relogin*/
			}

			if($accountType == "admin")
			{
				$editedby = "admin";
			}
			else{
				$editedby = $editusername;
			}

				//validate if new password has value
				if($editpassword){
					$savepassword = password_hash($editpassword, PASSWORD_DEFAULT);
					$query1 = "UPDATE account SET account_id = '$studentid', username = '$editusername', password = '$savepassword', dateModified = '$modifieddate', editedby = '$editedby' WHERE id = '$recordid'";
					$query2 = "UPDATE student SET studentid = '$studentid', name = '$editfname', course = '$editcourse', yearSection = '$yearsection' WHERE student.studentid = '$dbaccountid'";
					$sql1 = $this->conn->query($query1);
					$sql2 = $this->conn->query($query2);
					if($sql1 && $sql2){
						//evaluate user to know the redirect page
						if($accountType == "admin"){
							$success = "Account has been updated. Refresh the page to view updated data.";
							$redirect = "admindisplaystudenthome.php?studentid=$studentid";
						}
						else{
							$success = "Account has been updated. Please relogin your account.";
							$redirect = "logout.php?studentid=$studentid";
						}
						$this->successHandler($success);
						$this->timeoutRedirectHandler($redirect);
					}
					else{
						$error = "There was an error";
						$this->errorHandler($error);
					}
				}
				else{
					//no edit password field
					$query1 = "UPDATE account SET account_id = '$studentid', username = '$editusername', dateModified = '$modifieddate', editedby = '$editedby' WHERE id = '$recordid'";
					$query2 = "UPDATE student SET studentid = '$studentid', name = '$editfname', course = '$editcourse', yearSection = '$yearsection' WHERE student.studentid = '$dbaccountid'";
					$sql1 = $this->conn->query($query1);
					$sql2 = $this->conn->query($query2);
					if($sql1 && $sql2){
						//evaluate user to know the redirect page
						if($accountType == "admin"){
							$success = "Account has been updated. Refresh the page to view updated data.";
							$redirect = "admindisplaystudenthome.php?studentid=$studentid";
						}
						else{
							$success = "Account has been updated. Please relogin your account.";
							$redirect = "logout.php?studentid=$studentid";
						}
						$this->successHandler($success);
						$this->timeoutRedirectHandler($redirect);
					}
					else{
						$error = "There was an error";
						$this->errorHandler($error);
					}

				}
			
			
		}


		

		public function adminGetUsersInfo(){
			// $query = "SELECT 'account.account_id', 'account.username', 'student.name', 'student.course', 'student.yearSection' INNER JOIN account ON 'account.account_id' = 'student.studentid'";

			$query = "SELECT * FROM `account` INNER JOIN `student` ON account.account_id = student.studentid";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
		}

		public function adminGetBorrowerName($borrowerid){

			$query = "SELECT * FROM student WHERE studentid = '$borrowerid'";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
		}

		public function sessionChecker(){
			@$_SESSION['account_id'];
			if($_SESSION['account_id']){
				$field = "account_id";
				$rows = $this->getUserInfo($_SESSION['account_id'], $field);
				if(!empty($rows)){
					foreach($rows as $row){
						$accountType = $row['accountType'];
					}

					if($accountType == "admin"){
						header("location: adminhome.php");
					}
					else{
						header("location: home.php");	
					}
				}
			}
			else{
				header("location: index.php");
			}
		}

		public function sessionOnChecker(){
			//function to check if session is present
			@$session = $_SESSION['account_id'];
			if($session){

			}
			else{
				header("location: index.php");
			}
		}

		public function displayStudentProfile($accountid){
			?>
			<hr>
			
			<?php
			$data = $this->getAllUserInfoInnerJoin($accountid);
			if($data){
				//end of foreach
				foreach ($data as $row) {
				$studentid = $row['account_id'];
				$fullname = $row['name'];
				$username = $row['username'];
				$course = $row['course'];
				$yearsection = $row['yearSection'];
				$joined = $row['dateCreated'];
				$lastedited = $row['dateModified'];
				$editedby = $row['editedby'];

				?>
				<h1>User Profile <i class="fas fa-user text-primary"></i></h1>
				<label class="label text-info">Student ID: </label> <strong><?php echo $studentid;?></strong>
				<br>
				<label class="label text-info">Full name: </label> <strong><?php echo $fullname;?></strong>
				<br>
				<label class="label text-info">Username: </label> <strong><?php echo $username;?></strong>
				<br>
				<label class="label text-info">Course: </label> <strong><?php echo $course;?></strong>
				<br>
				<label class="label text-info">Year and section: </label> <strong><?php echo $yearsection;?></strong>
				<br>
				<label class="label text-info">Date created: </label> <strong><?php echo $joined;?></strong>
				<br>
				<label class="label text-info">Last edited: </label> <strong><?php echo $lastedited;?></strong>
				<br>
				<?php

				//logic here to display edited by
				if($editedby){
				?>
					<label class="label text-info">Edited by: </label> <strong><?php echo $editedby;?></strong>
				<?php
				}
				else{

				}
			}
			}
			else{
				//display session expired
				//evaluate if the user is admin or student.
				//the the user is admin, display Data expired
				$session = $_SESSION['account_id'];
				$data = $this->fetchUsersTable($session);
				if($data){
					foreach ($data as $row) {
						$accounttype = $row['accountType'];
					}
					if($accounttype == "admin"){
					//display data expired
						?><h1><i class="fas fa-user text-danger"> Data expired!</i></h1><?php
					}
					else{
						?><h1><i class="fas fa-user text-danger"> Session expired!</i></h1><?php
					}
				}else{

				}

			}

			
		}

		public function displayAdminProfile($accountid){
			?>
			<hr>
			<h1>Admin Profile <i class="fas fa-user-cog text-primary"></i></h1>
			<?php
			$data = $this->fetchUsersTable($accountid);
			foreach ($data as $row) {
				$accountid = $row['account_id'];
				$username = $row['username'];
				$joined = $row['dateCreated'];
				$lastedited = $row['dateModified'];
				$editedby = $row['editedby'];

				?>
				<label class="label text-info">Admin ID: </label> <strong><?php echo $accountid;?></strong>
				<br>
				
				<label class="label text-info">Username: </label> <strong><?php echo $username;?></strong>
				<br>
				<label class="label text-info">Date created: </label> <strong><?php echo $joined;?></strong>
				<br>
				<label class="label text-info">Last edited: </label> <strong><?php echo $lastedited;?></strong>	
				<?php
				//php script here to evaluate if edited or not
				if($editedby){
					?>
					<br>
					<label class="label text-info">Edited by: </label> <strong><?php echo $editedby;?></strong>
					<?php
				}
			}
		}

		
		//student methods
		public function studentPendingCounter($accountid){
			$query = "SELECT * FROM borrow WHERE borrowerid = '$accountid' AND remarks = 'pending'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		public function studentBorrowedCounter($accountid){
			$query = "SELECT * FROM borrow WHERE borrowerid = '$accountid' AND remarks = 'borrowed'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		public function studentReturnedCounter($accountid){
			$query = "SELECT * FROM borrow WHERE borrowerid = '$accountid' AND remarks = 'returned'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		public function studentOverdueCounter($accountid){
			$query = "SELECT * FROM borrow WHERE borrowerid = '$accountid' AND remarks = 'overdue'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		public function studentNotificationsCounter($accountid){

			$query = "SELECT * FROM notification WHERE receiver = '$accountid' AND notificationremarks = 'unread'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		//admin methods
		public function adminStudentCounter(){
			$query = "SELECT * FROM `account` INNER JOIN `student` ON account.account_id = student.studentid";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		public function adminAdminCounter(){
			$query = "SELECT * FROM `account` WHERE accountType = 'admin'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		public function adminPendingCounter(){
			$query = "SELECT * FROM borrow WHERE remarks = 'pending'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		public function adminBorrowedCounter(){
			$query = "SELECT * FROM borrow WHERE remarks = 'borrowed'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		public function adminReturnedCounter(){
			$query = "SELECT * FROM borrow WHERE remarks = 'returned'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		public function adminOverdueCounter(){
			$query = "SELECT * FROM borrow WHERE remarks = 'overdue'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		public function adminNotificationsCounter($accountid){
			$query = "SELECT * FROM notification WHERE receiver = 'admin' AND notificationremarks = 'unread'";
			$sql = $this->conn->query($query);
			if($sql){
				$result = mysqli_num_rows($sql);
			}else{
				$result = 0;
			}
			return $result;
		}



		//notofication functions
		//STUDENT
		public function studentConfirmedNotification($borrowerid, $bookid){
			//fetch book name using bookid
			$book = new Books();
			$notificationtitle = "Confirmed borrow book request.";
			$sql = $book->fetchSpecificBook($bookid);
			foreach ($sql as $row) {
				$booktitle =$row['title'];
			}
			$notificationcontent = "Your request to borrow the book ".$booktitle." has been approved.";

			$query2 = "INSERT INTO notification (receiver, notiftitle, notifcontent) VALUES ('$borrowerid', '$notificationtitle', '$notificationcontent')";
			$sql2 = $this->conn->query($query2);
		}


		public function fetchAllStudentNotifications($receiverid){
			$query = "SELECT * FROM notification WHERE receiver = '$receiverid'";
			
			$sql = $this->conn->query($query);
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
				return @$data;
			}
			
		}

		public function studentOverdueNotification($borrowid){
			$book = new Books();
			//fetch book id using borrowid
			$fetch = $this->getSpecificBorrow($borrowid);
			foreach ($fetch as $rowfetch) {
				$bookid = $rowfetch['bookid'];
				$borrowerid = $rowfetch['borrowerid'];
			}

			
			$sql = $book->fetchSpecificBook($bookid);
			foreach ($sql as $row) {
				$booktitle =$row['title'];
			}
			$notificationtitle = "Overdue book";
			$notificationcontent = "Your book ".$booktitle." is is already overdue. Return the book to library as soon as possible";

			$query2 = "INSERT INTO notification (receiver, notiftitle, notifcontent) VALUES ('$borrowerid', '$notificationtitle', '$notificationcontent')";
			$sql2 = $this->conn->query($query2);
		}

		

		public function displayNotifications($receiverid){
			?>
			<table class="dataTable" class="table display nowrap" style="width:100%">
				<thead>
					<tr>
						
						<th>Title</th>
						<th>Content</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//display notifications
					$data = $this->fetchAllStudentNotifications($receiverid);

					if($data){
						foreach ($data as $row) {
							$id = $row['notifid'];
							$title = $row['notiftitle'];
							$content = $row['notifcontent'];
							$remarks = $row['notificationremarks'];

							?>
							<tr>
							<td><?php echo $title;?></td>
							<td><?php echo $content;?></td>
							<?php

							

							if($remarks == "unread"){
							echo "<td><a href = 'scriptnotificationmarkasread.php?id=$id'><i class='fas fa-times'></i></i> $remarks</a></td>";
							}
							else{
							echo "<td class = 'text-success'><i class='fas fa-check-square'>$remarks</i></td>";
							}

							
							?>
							</tr>
							<?php
						}
					}
					?>
				</tbody>

			</table>
			<?php
		}

		//this function fetches the notification table according to notif id and user id
		public function fetchUserNotification($userid, $notifid){
			$query = "SELECT * FROM notification WHERE notifid = '$notifid' AND receiver = '$userid'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return @$result;
		}

		public function markNotificationAsRead($userid, $notifid){
			//validate if the user owns the notification
			$sql1 = $this->fetchUserNotification($userid, $notifid);
			if($sql1 > 0){
				$query2 = "UPDATE notification SET notificationremarks = 'read' WHERE notifid = '$notifid'";
				$sql2 = $this->conn->query($query2);
				if($sql2){
					$redirect = "home.php#notifications";
					$this->redirectHandler($redirect);
				}
				else{
					echo "error";
				}
				

			}else{
				$error = "You don't have permission to perform this action.";
				$this->errorHandler($error);
				$redirect = "home.php#notifications";
				$this->timeoutRedirectHandler($redirect);
			}
			

			
		}

		public function markAllNotificationsAsRead($userid){
			//check if the userid is the user
			$session = $_SESSION['account_id'];
			if($session == $userid){
				$query = "UPDATE notification SET notificationremarks = 'read' WHERE receiver = '$userid'";
				$sql = $this->conn->query($query);
				$redirect = "home.php#notifications";
				$this->redirectHandler($redirect);

			}
			else{
				$error = "You don't have permission to perform this action.";
				$this->errorHandler($error);
				$redirect = "index.php";
				$this->timeoutRedirectHandler($redirect);

			}
		}

		

		//ADMIN
		public function adminOverDueNotification($borrowid){
			//extract borrow id and get book id and borrower id
			$book = new Books();
			//fetch book id using borrowid
			$fetch = $this->getSpecificBorrow($borrowid);
			foreach ($fetch as $rowfetch) {
				$bookid = $rowfetch['bookid'];
				$borrowerid = $rowfetch['borrowerid'];
			}

			//fetch book name
			$fetchbookname = $book->fetchSpecificBook($bookid);
			foreach ($fetchbookname as $rowfetchbookname) {
				$bookname = $rowfetchbookname['title'];
			}

			//fetch borrower name
			$fetchborrowername = $this->getAllUserInfoInnerJoin($borrowerid);
			foreach ($fetchborrowername as $rowfetchborrowername) {
				$borrowername = $rowfetchborrowername['name'];
			}
			
			$sql = $book->fetchSpecificBook($bookid);
			foreach ($sql as $row) {
				$booktitle =$row['title'];
			}
			$notificationtitle = "Overdue book";
			$notificationcontent = "The book $bookname borrowed by $borrowername witht the student number of $borrowerid is already overdue.";

			$query2 = "INSERT INTO notification (receiver, notiftitle, notifcontent) VALUES ('admin', '$notificationtitle', '$notificationcontent')";
			$sql2 = $this->conn->query($query2);
		}

		public function adminFetchAllNotifications(){
			$query = "SELECT * FROM notification WHERE receiver = 'admin'";
			
			$sql = $this->conn->query($query);
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
				return @$data;
			}
			
		}

		public function displayAdminNotifications(){
			?>
			<table class="dataTable" class="table display nowrap" style="width:100%">
				<thead>
					<tr>
						
						<th>Title</th>
						<th>Content</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//display notifications
					$data = $this->adminFetchAllNotifications();

					if($data){
						foreach ($data as $row) {
							$id = $row['notifid'];
							$title = $row['notiftitle'];
							$content = $row['notifcontent'];
							$remarks = $row['notificationremarks'];

							?>
							<tr>
							<td><?php echo $title;?></td>
							<td><?php echo $content;?></td>
							<?php

							

							if($remarks == "unread"){
							echo "<td><a href = 'scriptnotificationmarkasread.php?id=$id'><i class='fas fa-times'></i></i> $remarks</a></td>";
							}
							else{
							echo "<td class = 'text-success'><i class='fas fa-check-square'>$remarks</i></td>";
							}

							
							?>
							</tr>
							<?php
						}
					}
					?>
				</tbody>

			</table>
			<?php
		}

		public function adminMarkNotificationAsRead($notifid){
			//validate if the user owns the notification
			$sql1 = $this->fetchUserNotification('admin', $notifid);
			if($sql1 > 0){
				$query2 = "UPDATE notification SET notificationremarks = 'read' WHERE notifid = '$notifid'";
				$sql2 = $this->conn->query($query2);
				if($sql2){
					$redirect = "adminhome.php#notifications";
					$this->redirectHandler($redirect);
				}
				else{
					echo "error";
				}
				

			}else{
				$error = "You don't have permission to perform this action.";
				$this->errorHandler($error);
				$redirect = "adminhome.php#notifications";
				$this->timeoutRedirectHandler($redirect);
			}
			

			
		}


		public function errorHandler($error){
			echo "<h4 class = 'text-danger'>$error!</h4>";
		}

		public function successHandler($success){
			echo "<h4 class = 'text-success'>$success</h4>";
		}

		public function redirectHandler($redirect){
			
			?>
			<script type="text/javascript">
				var redirect = "<?php echo $redirect;?>";
				window.open(redirect, '_SELF');
			</script>
			<?php
		}

		public function clearForm(){
			?>
			<script type="text/javascript">
				$("#frmAddAdmin").trigger('reset');
				$("#frmCreateAccount").trigger('reset');
			</script>
			<?php
		}

		public function timeoutRedirect(){
			?>
			<script type="text/javascript">
				window.setTimeout( function(){
                window.open('logout.php', '_SELF')
             }, 3000 );
			</script>
			<?php
		}

		public function timeoutRedirectHandler($redirect){
			?>
			<script type="text/javascript">
				var redirect = "<?php echo $redirect;?>";
				window.setTimeout( function(){
                window.open(redirect, '_SELF')
             }, 3000 );
			</script>
			<?php
		}
		
	}
?>