<?php

class System{
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

		public function deleteTable($tablename, $password){
			

			//validate if admin password is the same in the input
			$user = new Users();
			$field = "account_id";
			$id = $_SESSION['account_id'];
			$fetchadminpass = $user->getUserInfo($field, $id);
			foreach ($fetchadminpass as $rowfetchadminpass) {
				$dbpass = $rowfetchadminpass['password'];
			}

			$passverify = password_verify($password, $dbpass);

			//validate if user selecetd table name
			if(!$tablename){
				$error = "Select a table";
				$this->errorHandler($error);
			}

			if(!$password){
				$error = "Enter password";
				$this->errorHandler($error);
			}

			if(!$passverify){
				$error = "Wrong password";
				$this->errorHandler($error);
			}

			//all

			if(!$tablename){
				
			}
			else if(!$passverify){
				
			}

			else if(!$password){
				
			}
			else{
				//evaluate tables
				
				if($tablename == "users"){
					
					$sql1 = "DELETE FROM student";
					$sql2 = "DELETE FROM account WHERE account_id != '$id'";
					$this->conn->query($sql1);
					$this->conn->query($sql2);
					$success = "Account and User table has been successfully cleared.";
					$this->successHandler($success);
				}
				else if($tablename == "book"){
					//book
					$sql = "DELETE FROM book";
					$this->conn->query($sql);
					$success = "Book table has been successfully cleared.";
					$this->successHandler($success);
				}
				else if($tablename == "bookcount"){
					//bookcount
					$sql = "DELETE FROM bookcount";
					$this->conn->query($sql);
					$success = "Bookcount table has been successfully cleared.";
					$this->successHandler($success);
				}
				else if($tablename == "borrow"){
					// borrow
					$sql = "DELETE FROM borrow";
					$this->conn->query($sql);
					$success = "Borrow table has been successfully cleared.";
					$this->successHandler($success);
				}
				else if($tablename == "notification"){
					//notification
					$sql = "DELETE FROM notification";
					$this->conn->query($sql);
					$success = "Notification table has been successfully cleared.";
					$this->successHandler($success);
				}
				else if($tablename == "publisher"){
					//publisher
					$sql = "DELETE FROM publisher";
					$this->conn->query($sql);
					$success = "Publisher table has been successfully cleared.";
					$this->successHandler($success);
				}
				else{
					//all
					//accounts and students
					$sql1 = "DELETE FROM student";
					$id = $_SESSION['account_id'];
					$sql2 = "DELETE FROM account WHERE account_id != '$id'";

					$this->conn->query($sql1);
					$this->conn->query($sql2);

					//book
					$sql3 = "DELETE FROM book";
					$this->conn->query($sql3);

					//bookcount
					$sql4 = "DELETE FROM bookcount";
					$this->conn->query($sql4);

					// borrow
					$sql5 = "DELETE FROM borrow";
					$this->conn->query($sql5);

					//notification
					$sql6 = "DELETE FROM notification";
					$this->conn->query($sql6);

					//publisher
					$sql7 = "DELETE FROM publisher";
					$this->conn->query($sql7);

					$success = "All tables have been successfully cleared.";
					$this->successHandler($success);
					
				}
			}


			
		}

		public function errorHandler($error){
			echo "<h4 class = 'text-danger'>$error!</h4>";
		}

		public function successHandler($success){
			echo "<h4 class = 'text-success'>$success</h4>";
			?>
			<script type="text/javascript">
				$("#frmClearData").trigger('reset');
			</script>
			<?php
		}


	}
?>