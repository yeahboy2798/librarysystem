<?php
	class Publisher{
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

		public function fetchAllPublisher(){
			$query = "SELECT * FROM publisher";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
		}

		//fetch specific publisher, for edit
		public function fetchPublisherInfo($publisherid){
			$query = "SELECT * FROM publisher WHERE publisherid = '$publisherid'";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
		}

		//fetch specific publisher according to bookid
		public function fetchSpecificPublisher($bookid){
			$query = "SELECT * FROM book INNER JOIN publisher ON book.publisherId = publisher.publisherid WHERE book.bookid = '$bookid'";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
		}

		//add publisher

		public function publisherCounter(){
			$query = "SELECT * FROM publisher";
			$sql = $this->conn->query($query);
			return $result = mysqli_num_rows($sql);

		}

		public function publisherNameValidator($publishername){
			$sql = "SELECT * FROM publisher WHERE publishername = '$publishername'";
			$query = $this->conn->query($sql);
			$result = mysqli_num_rows($query);
			return $result;
		}

		public function displayPublishers(){
			?>
			<table class="table display dataTable" style="width:100%">
				<thead>
					<tr>
						<th>Publisher ID</th>
						<th>Name</th>
						<th>Address</th>
						<th>Edit</th>
						<th>Delete</th>
					</tr>

				</thead>

				<tbody>
						<?php
						$data = $this->fetchAllPublisher();
						if($data){
							foreach ($data as $row) {
							$id = $row['publisherid'];
							$name = $row['publishername'];
							$address = $row['publisheraddress'];

							?>
							<tr>
								<td><?php echo $id;?></td>
								<td><?php echo $name;?></td>
								<td><?php echo $address;?></td>
								<?php 
								echo "<td><a class = 'btn btn-primary' href = 'admineditpublisher.php?publisherid=$id'>Edit</a></td>";

								echo "<td><a class = 'btn btn-danger' href = 'scriptdeletepublisher.php?publisherid=$id'>Delete</a></td>";
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

		public function displayAddPublisher(){
			?>
			<!--button trigge modal-->
			<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPublisherModal"><i class="fas fa-plus-circle"></i> Add Publisher</button>

			<!--modal-->
			<form action="index.php" id="frmAddPublisher" method="POST">
				
				<!-- Modal -->
				<div class="modal fade" id="addPublisherModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="staticBackdropLabel">Add Publisher</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label>Publisher Name</label>
									<input type="text" name="txtpublishername" id="txtpublishername" class="form-control" placeholder="Enter publisher name">
								</div>

								<div class="form-group">
									<label>Publisher Address</label>
									<input type="text" name="txtpublisheraddress" id="txtpublisheraddress" class="form-control" placeholder="Enter publisher address">
								</div>

								
								<span class="createpublishermessage"></span>
							</div>

							<div class="modal-footer">
								
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" id="btnSavePublisher" value="signup" class="btn btn-success">Save Publisher </button>
							</div>


						</div>

					</div>
				</div>
			</form>
			<?php
		}
		public function validateAddPublisher($publishername, $publisheraddress){
			//validate publisher name
			$publishervalidator1 = $this->publisherNameValidator($publishername);
			//check if publishername has no value
			$publishervalidator2 = !$publishername;

			if($publishervalidator1){
				$error = "Publisher name is already in the database";
				$this->errorHandler($error);
			}

			if($publishervalidator2){
				$error = "Please enter publisher name";
				$this->errorHandler($error);
			}

			if(!$publisheraddress){
				$error = "Please enter publisher address";
				$this->errorHandler($error);
			}

			if($publishervalidator1){
				
			}

			else if($publishervalidator2){
				
			}

			else if(!$publisheraddress){
				
			}
			else{
				$this->saveNewPublisher($publishername, $publisheraddress);
			}
				
		}

		public function saveNewPublisher($publishername, $publisheraddress){
			$query = "INSERT INTO publisher (publishername, publisheraddress) VALUES ('$publishername', '$publisheraddress')";
			$sql = $this->conn->query($query);
			if($sql){
				$this->clearForm();
				$success = "Publisher has been saved.";
				$this->successHandler($success);

			}
			else{
				
				$error = "There was an error";
				$this->errorHandler($error);
			}
		}

		//edit publisher


		//delete publisher
		public function deletePublisher($publisherid){
			if(!$publisherid){
				$error = "No such data";
				$this->errorHandlerAlert($error);
			}

			//validate publisher id if its present in db
			$query1 = "SELECT * FROM publisher WHERE publisherid = '$publisherid'";
			$sql = $this->conn->query($query1);
			$result = mysqli_num_rows($sql);
			
			if($result < 1){
				$error = "No such data";
				$this->errorHandlerAlert($error);
			}


			if($publisherid AND $result){
				$querydelete = "DELETE FROM publisher WHERE publisherid = '$publisherid'";
				$sqldelete = $this->conn->query($querydelete);
				$success = "Publisher has been deleted";
				$this->successHandlerAlert($success);

			}


		}


		//edit publisher

		public function displayEditPublisher($publisherid){


			?>
			<form action="index.php" id="frmEditPublisher" method="POST">
				
				
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="staticBackdropLabel">Edit Publisher</h5>
								
							</div>
							<div class="modal-body">
								<?php
								//php code here to fetch publisher
								$data = $this->fetchPublisherInfo($publisherid);
								if($data){
									foreach ($data as $row) {
									$publisherid = $row['publisherid'];
									$name = $row['publishername'];
									$address = $row['publisheraddress'];
									}

									?>
									<input type="hidden" id="txthiddeneditpubid" value="<?php echo $publisherid;?>" name="">
									<!--display the inputs-->
									<div class="form-group">
									<label>Publisher Name</label>
									<input type="text" name="txteditpubname" value="<?php echo $name;?>" id= "txteditpubname" class="form-control">
									</div>

								
									<div class="form-group">
										<label>Publisher Address</label>
										<input type="text" name="txteditpubaddress" value="<?php echo $address;?>" id="txteditpubaddress" class="form-control">
									</div>


									
							</div>
							<div class="form-group">
									<span class="editPublisherMessage"></span>
							</div>
							
							<div class="modal-footer">
								
									<button type="submit" id="btnSaveEditPublisher" value="save" class="btn btn-success">Save</button>
									
							</div>
									<?php

								}
								else{
									$error = "No publisher data";
									$this->errorHandler($error);
									$redirect = "index.php";
									$ms = 1300;
									$this->timeoutRedirectHandler($redirect, $ms);
								}
								
								?>

									


						</div>

					</div>
				
			</form>
			<?php
		}


		public function editPublisher($publisherid){
			if(!$publisherid){
				$error = "No such data";
				$this->errorHandlerAlert($error);
			}

			//validate publisher id if its present in db
			$query1 = "SELECT * FROM publisher WHERE publisherid = '$publisherid'";
			$sql = $this->conn->query($query1);
			$result = mysqli_num_rows($sql);
			
			if($result < 1){
				$error = "No such data";
				$this->errorHandlerAlert($error);
			}


		}

		public function saveEditedPublisher($id, $name, $address){
			if(!$name){
				$error = "Please add publisher name";
				$this->errorHandler($error);
			}

			if(!$address){
				$error = "Please add publisher address";
				$this->errorHandler($error);
			}

			if($name && $address){

				$query = "UPDATE publisher SET publishername = '$name', publisheraddress = '$address'";
				$sql = $this->conn->query($query);
				if($sql){
					$success = "Publisher info has been updated";
					$redirect = "adminhome.php#publishers";
					$ms = 1200;
					$this->timeoutRedirectHandler($redirect, $ms);
					$this->successHandler($success);
				}
				else{
					$error = "There's an error";
					$this->errorHandler($error);
				}
				
			}
		}


		//success and error handlers
		public function errorHandler($error){
			echo "<h4 class = 'text-danger'>$error!</h4>";
		}

		public function successHandler($success){
			echo "<h4 class = 'text-success'>$success</h4>";
		}



		private function errorHandlerAlert($error){
			?>
			<script type="text/javascript">
				var error = "<?php echo $error;?>";
				alert(error);
				history.go(-1);
			</script>
			<?php
		}

		private function successHandlerAlert($success){
			?>
			<script type="text/javascript">
				var success = "<?php echo $success;?>";
				alert(success);
				history.go(-1);
			</script>
			<?php
		}



		public function clearForm(){
			?>
			<script type="text/javascript">
				$("#frmAddPublisher").trigger('reset');
				
			</script>
			<?php
		}

		public function timeoutRedirectHandler($redirect, $milliseconds){
			?>
			<script type="text/javascript">
				var redirect = "<?php echo $redirect;?>";
				var milliseconds = "<?php echo $milliseconds;?>";
				window.setTimeout( function(){
                window.open(redirect, '_SELF')
             }, milliseconds );
			</script>
			<?php
		}
		
	}
?>