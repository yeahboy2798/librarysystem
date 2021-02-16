<?php
	class Books{
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


		//note: $this refers to the this class. if you have called another class, do this
		/*
		for example Users class
		create a variable $user and call the class Users
		$user = new Users();
		--access the methods of the Users() class using this
		$user->login();
		$this-> refers to the current class
		$user-> refers to the other class
		*/
		//methods


		//function for date
		public function setTimeToInt(){
			
			return $milliseconds = round(microtime(true) * 1000);

		}

		public function getUniqueTime(){
			//used for id
			$y = date("Y", time());
			$m = date("m", time());
			$d = date("d", time());
			$h = date("H", time());
			$i = date("i", time());
			$s = date("s", time());
			return $unique = $y.$m.$d.$h.$i.$s;

			
		}

		public function getDateToday(){
			$date1 = new DateTime("now", new DateTimeZone('America/New_York'));
			return $date = $date1->format('y-m-d');	
		}

		public function addDueDate($borrowid){
			//get all data from borrow table to get the borrowed date
			$borrow = $this->fetchBorrowTable($borrowid);
			foreach ($borrow as $row) {
				$borrowedDate = $row['borrowedDate'];
			}
			return $dueDate = date('Y-m-d', strtotime($borrowedDate. ' + 5 days'));
		}

		public function adminBorrowAddDueDate($bookid, $studentid){
			//get all data from borrow table to get the borrowed date
			$today= $this->getDateToday();
			return $dueDate = date('Y-m-d', strtotime($today. ' + 5 days'));
		}

		//function to run the due date everytime the system runs.
		public function evaluateDueDate(){
			//instantiate User class to call notification functions
			$user = new Users();

			

			$dateToday = $this->getDateToday();
			$query = "SELECT * FROM borrow WHERE dueDate <= '$dateToday' AND remarks = 'borrowed' AND notified = 0";
			$sql = $this->conn->query($query);
			if(!empty($sql)){
				foreach ($sql as $row) {
					//get borrow id of the record
					$borrowid = $row['borrowid'];
					//update the table and set remarks to overdue
					$queryUpdateRemarks = "UPDATE borrow SET remarks = 'overdue', notified = 1 WHERE borrowid = '$borrowid' AND remarks = 'borrowed'";
					$sqlUpdateRemarks = $this->conn->query($queryUpdateRemarks);
					//call the overdue notification
					$user->studentOverdueNotification($borrowid);
					$user->adminOverdueNotification($borrowid);
				}
			}

			
		}


		
		/*==========FUNCTIONS TO FETCH DATA FROM THE TABLES==============*/

		public function fetchBooks(){
			$query = "SELECT * FROM `book` INNER JOIN `bookcount` ON book.bookid = bookcount.bookid";
			$sql = $this->conn->query($query);
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
		}

		public function fetchSpecificBook($bookid){
			$query = "SELECT * FROM `book` INNER JOIN `bookcount` ON book.bookid = bookcount.bookid WHERE book.bookid = '$bookid'";
			$sql = $this->conn->query($query);
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
		}

		public function adminDisplayBooks(){
			?>
			<table class="table display dataTable" style="width:100%">
				<thead>
					<tr>
						<th>Book ID</th>
						<th>Title</th>
						<th>Subject</th>
						<th>Author</th>
						<th>Books Remaining</th>
						<th>Actions</th>
						
					</tr>
				</thead>
				<tbody>
					<?php
					//php code here to get pending books by the student

					$data = $this->fetchBooks();
					if(!empty($data)){
						foreach ($data as $row) {
							$bookid = $row['bookid'];
							echo "<tr>";
							echo "<td>$bookid</td>";
							echo "<td>".$row['title']."</td>";
							echo "<td>".$row['subject']."</td>";
							echo "<td>".$row['author']."</td>";
							//display logic here to adjust the color of books according to remaining books
							$totalnumber = $row['totalnumber'];
							if($totalnumber >= 20){
								echo "<td class = 'text-success'>$totalnumber</td>";
							}
							else if($totalnumber >= 10 && $totalnumber < 20 ){
								echo "<td class = 'text-warning'>$totalnumber</td>";
							}
							else{
								echo "<td class = 'text-danger'>$totalnumber</td>";
							}

						

							?>
								<td>
									<center>
								<div class="dropdown">
								<button id="dLabel" type="button" class="btn btn-primary" data-toggle="dropdown" 
								aria-haspopup="true" aria-expanded="false">
								Click
								<i class="fas fa-caret-down"></i>
								</button>
								<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel" id="actionul">
								<?php echo "<a class = 'dropdown-action text-success' href = 'adminborrowbook.php?bookid=$bookid'><li>Borrow</li></a>";?>
								<?php echo "<a class = 'dropdown-action text-primary' href = 'adminreturnbook.php?bookid=$bookid'><li>Return</li></a>";?>
								<?php echo "<a class = 'dropdown-action text-info' href = 'admineditbook.php?bookid=$bookid'><li>Edit</li></a>";?>
								<?php echo "<a class = 'dropdown-action text-danger' href = 'scriptdeletebook.php?bookid=$bookid'><li>Delete</li></a>";?>
								</ul>
								</div>
									</center>
								</td>
							<?php

							echo "</tr>";
						}
					}


					?>
				</tbody>

			</table>
			<?php
		}

		public function deleteBook($bookid){
			//deletebook
			//validate book id
			$query = $this->fetchSpecificBook($bookid);
			if($query){
				//delete from book
				$query2 = "DELETE FROM book WHERE bookid = '$bookid'";
				$query3 = "DELETE FROM bookcount WHERE bookid = '$bookid'";
				$sql2 = $this->conn->query($query2);
				$sql3 = $this->conn->query($query3);

				if($sql2 && $sql3){
				$success = "Book has been deleted";
				$redirect = "adminhome.php#allbooks";
				$ms = 1200;
				$this->successHandler($success);
				$this->timeoutRedirectHandler($redirect, $ms);
				}
				else{
				$error = "Error";
				$redirect = "adminhome.php#allbooks";
				$ms = 1200;
				$this->errorHandler($error);
				$this->timeoutRedirectHandler($redirect, $ms);
				}
			}
			else{
				$error = "No data found";
				$redirect = "adminhome.php#allbooks";
				$ms = 1200;
				$this->errorHandler2($error);
				$this->timeoutRedirectHandler($redirect, $ms);
			}

		}

		public function deleteAllBooks(){

		}

		public function studentDisplayBooks($studentid){
			?>
			<table class="table display dataTable" style="width:100%">
				<thead>
					<tr>
						<th>Book ID</th>
						<th>Title</th>
						<th>Subject</th>
						<th>Author</th>
						<th>Publisher ID</th>
						<th>Year Published</th>
						<th>Books Remaining</th>
						<th>Action</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
					

					$data = $this->fetchBooks();
					if(!empty($data)){
						foreach ($data as $row) {
							$bookid = $row['bookid'];
							echo "<tr>";
							echo "<td>$bookid</td>";
							echo "<td>".$row['title']."</td>";
							echo "<td>".$row['subject']."</td>";
							echo "<td>".$row['author']."</td>";
							echo "<td>".$row['publisherId']."</td>";
							echo "<td>".$row['yearPublished']."</td>";

							//display logic here to adjust the color of books according to remaining books
							$totalnumber = $row['totalnumber'];
							if($totalnumber >= 20){
								echo "<td class = 'text-success'>$totalnumber</td>";
							}
							else if($totalnumber >= 10 && $totalnumber < 20 ){
								echo "<td class = 'text-warning'>$totalnumber</td>";
							}
							else{
								echo "<td class = 'text-danger'>$totalnumber</td>";
							}
							

							//php logic here to display whether borrow or cancel
							//see if the book is pending. true means the book is pending, false means the book is not pending
							//fetch the borrow table to look for data that is equal to student it
							// $borrowTable = $this->fetchBorrowTable($studentid);
							$checkbookpending = $this->checkBookPending($bookid, $studentid);
							$checkbookborrowed = $this->checkBookBorrowed($bookid, $studentid);
							$checkbookoverdue = $this->checkBookOverdue($bookid, $studentid);


							if($checkbookpending){
								echo "<td><a class = 'btn btn-danger' href = 'scriptcancel.php?bookid=$bookid'>Cancel</a></td>";
								echo "<td class = 'text-primary'>Pending</td>";
							}
							else if($checkbookborrowed){
								echo "<td class = 'text-success'>To be returned</td>";
								echo "<td class = 'text-primary'>Borrowed</td>";
							}
							else if($checkbookoverdue){
								echo "<td class = 'text-danger'>Return as soon as possible</td>";
								echo "<td class = 'text-danger'>Overdue</td>";
							}
							else{
								echo "<td><a class = 'btn btn-success' href = 'scriptborrow.php?bookid=$bookid'>Borrow</a></td>";
								echo "<td class = 'text-success'>Not Borrowed</td>";
							}

							

							echo "</tr>";
						}
					}


					?>
				</tbody>

			</table>
			<?php
		}

		public function fetchBorrowTable($borrowid){
			$query = "SELECT * FROM borrow WHERE borrowid = '$borrowid'";
			$sql = $this->conn->query($query);
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
		}

		
		//functions for books (borrow)
		/*=============STUDENT=================*/
		//function for student to borrow book
		public function studentBorrow($bookid){
			$borrowerid = $_SESSION['account_id'];
			//check if the book is already in pending request given the student id and bookid
			$checkbookpending = $this->checkBookPending($bookid, $borrowerid);
			//check if the book is already borrowed given the student id and bookid
			$checkbookborrowed = $this->checkBookBorrowed($bookid, $borrowerid);
			//check if the bookcount is zero or not
			$checkbookcount = $this->checkBookCount($bookid);
			//evaluate the result. don't allow the student to create a request if the specific book is already borrowed or in pending, and if the book is already zero

			if($checkbookpending){
				$error = "Book is already in pending. Go to the library and personally get the book.";
				//call the function error handler and pass the error variable content.
				$this->errorHandler($error);
			}
			else if($checkbookborrowed > 0){
				$error = "Book is currently borrowed and not yet returned. Kindly return the book first and then borrow again";
				$this->errorHandler($error);
			}
			else if($checkbookcount < 1){	
				$error = "Book stock is 0. Kindly wait for students to return book of this kind and then borrow again.";
				$this->errorHandler($error);
			}
			else{
			//insert into borrow table
			//get date
			//integer date and time to sort priority borrowers
			
			$dateToday = $this->getDateToday();
			
			//query
			$query = "INSERT INTO borrow 
			(borrowerid, bookid, borrowedDate, remarks) VALUES 
			('$borrowerid', '$bookid', '$dateToday', 'pending')";
			}
			@$sql = $this->conn->query($query);
			if($sql){
				$success = "Success! Go to the library and get the book.";
				$this->successHandler($success);
			}
			else{
			}

		}

		//fetch and display pending books
		public function studentFetchPendingBooks($borrowerid){
			$query = "SELECT * FROM `borrow` INNER JOIN `book` ON borrow.bookid = book.bookid WHERE borrow.borrowerid = '$borrowerid' AND borrow.remarks = 'pending'";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
			
		}
		public function studentDisplayPendingBooks($studentid){
			?>
			<table class="table display dataTable" style="width:100%">
											<thead>
												<tr>
													<th>Title</th>
													<th>Subject</th>
													<th>Author</th>
													<th>Borrowed Date</th>
													<th class="text-danger">Action</th>



												</tr>
											</thead>
											<tbody>
												<?php
												//php code here to get borrowed books by the student
												$book = new Books();
												$borrowerid = $studentid;
												$data = $book->studentFetchPendingBooks($borrowerid);
													if($data){
														foreach ($data as $row) {
														$borrowid = $row['borrowid'];
														$bookid = $row['bookid'];
														echo "<tr>";
														echo "<td>".$row['title']."</td>";
														echo "<td>".$row['subject']."</td>";
														echo "<td>".$row['author']."</td>";
														echo "<td>".$row['borrowedDate']."</td>";
														
														echo "<td><a class = 'btn btn-danger' href = 'scriptcancel.php?bookid=$bookid'>Cancel</a></td>";

														echo "</tr>";
													}
													}

												
												?>
											</tbody>
											
										</table>
							<?php
		}

		//fetch and display borrowed books
		public function studentFetchBorrowedBooks($borrowerid){
			$query = "SELECT * FROM `borrow` INNER JOIN `book` ON borrow.bookid = book.bookid WHERE borrow.borrowerid = '$borrowerid' AND borrow.remarks = 'borrowed'";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
			
		}
		public function studentDisplayBorrowedBooks($studentid){
			?>
			<table class="table display dataTable" id="dtborrowedbooks" style="width:100%">
											<thead>
												<tr>
													<th>Title</th>
													<th>Subject</th>
													<th>Author</th>
													<th>Borrowed Date</th>
													<th class="text-danger">Due Date</th>


												</tr>
											</thead>
											<tbody>
												<?php
												//php code here to get borrowed books by the student
												$book = new Books();
												$borrowerid= $studentid;
												$data = $book->studentFetchBorrowedBooks($borrowerid);
												if(!empty($data)){
													foreach ($data as $row) {
														$borrowid = $row['id'];
														echo "<tr>";
														echo "<td>".$row['title']."</td>";
														echo "<td>".$row['subject']."</td>";
														echo "<td>".$row['author']."</td>";
														echo "<td>".$row['borrowedDate']."</td>";
														echo "<td class = 'text-success'>".$row['dueDate']."</td>";


														echo "</tr>";



													}
												}
												?>
											</tbody>
											
										</table>
							<?php
		}

		//fetch and display returned books

		public function studentFetchReturnedBooks($borrowerid){
			$query = "SELECT * FROM `borrow` INNER JOIN `book` ON borrow.bookid = book.bookid WHERE borrow.borrowerid = '$borrowerid' AND borrow.remarks = 'returned'";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
			
		}
		public function studentDisplayReturnedBooks($studentid){
			?>
			<table class="table display dataTable" style="width:100%">
											<thead>
												<tr>
													<th>Title</th>
													<th>Subject</th>
													<th>Author</th>
													<th>Borrowed Date</th>
													<th class="text-info">Returned Date</th>


												</tr>
											</thead>
											<tbody>
												<?php
												//php code here to get borrowed books by the student
												$book = new Books();
												$borrowerid = $studentid;
												$data = $book->studentFetchReturnedBooks($borrowerid);
												if(!empty($data)){
													foreach ($data as $row) {
														$borrowid = $row['id'];
														echo "<tr>";
														echo "<td>".$row['title']."</td>";
														echo "<td>".$row['subject']."</td>";
														echo "<td>".$row['author']."</td>";
														echo "<td>".$row['borrowedDate']."</td>";
														echo "<td class = 'text-info'>".$row['dateReturned']."</td>";
														
														

														echo "</tr>";
													}
												}
												?>
											</tbody>
											
										</table>
							<?php
		}

		//fetch and display overdue books by student
		public function studentFetchOverDueBooks($borrowerid){
			$query = "SELECT * FROM `borrow` INNER JOIN `book` ON borrow.bookid = book.bookid WHERE borrow.borrowerid = '$borrowerid' AND borrow.remarks = 'overdue'";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
			
		}
		public function studentDisplayOverdueBooks($studentid){
			?>
			<table class="table display dataTable" style="width:100%">
											<thead>
												<tr>
													<th>Title</th>
													<th>Subject</th>
													<th>Author</th>
													<th>Borrowed Date</th>
													<th class="text-danger">Due Date</th>
												</tr>
											</thead>
											<tbody>
												<?php
												//php code here to get borrowed books by the student
												$book = new Books();
												$borrowerid= $studentid;
												$data = $book->studentFetchOverDueBooks($borrowerid);
												if(!empty($data)){
													foreach ($data as $row) {
														$borrowid = $row['id'];
														echo "<tr>";
														echo "<td>".$row['title']."</td>";
														echo "<td>".$row['subject']."</td>";
														echo "<td>".$row['author']."</td>";
														echo "<td>".$row['borrowedDate']."</td>";
														echo "<td class = 'text-danger'>".$row['dueDate']."</td>";
														
														

														echo "</tr>";
													}
												}
												?>
											</tbody>
											
										</table>
							<?php
		}


		//function to cancel book borrow
		public function studentCancel($borrowid){
			//evaluate if the data is valid
			$validatepresent = $this->checkRequestPresent($borrowid);
			//check if request is pending. don't allow if it's not pending
			$validatepending = $this->checkRequestPending($borrowid);

			if($validatepresent == 0){
				$error = "No such data";
				$this->errorHandler($error);
			}
			else if($validatepending == 0){
				$error = "This request is not pending. You can only cancel pending books.";
				$this->errorHandler($error);
			}
			else{
				$query = "DELETE FROM borrow WHERE borrowid = '$borrowid'";
				$sql = $this->conn->query($query);
				$success = "You have cancelled this request.";
				$this->successHandler($success);
			}

			
			
		}



		/*============ADMIN====================*/
		//fetch borrowed book using student id and book
		public function validateBorrowed($bookid, $studentid){
			$query = "SELECT * FROM borrow WHERE borrowerid = '$studentid' AND bookid = '$bookid' AND remarks = 'borrowed'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;		}

		public function validateOverdue($bookid, $studentid){
			$query = "SELECT * FROM borrow WHERE borrowerid = '$studentid' AND bookid = '$bookid' AND remarks = 'overdue'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;		
		}

		public function validatePending($bookid, $studentid){
			$query = "SELECT * FROM borrow WHERE borrowerid = '$studentid' AND bookid = '$bookid' AND remarks = 'pending'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;		
		}

		//function for the admin to create borrow transaction (from all books tab)
		public function adminBorrow($bookid, $studentid){
			$user = new Users();
			
			//validate if studentid is present in database
			$studentidvalidation = $user->fetchUsersTable($studentid);//1 means present
			//validate if bookid is present in database
			$bookidvalidation = $this->fetchSpecificBook($bookid);
			//1 means true
			//validate if the book is already borrowed or overdue
			$validateborrowed = $this->validateBorrowed($bookid, $studentid);
			$validateoverdue = $this->validateOverdue($bookid, $studentid);
			$validatepending = $this->validatePending($bookid, $studentid);

			$validatebookcount = $this->fetchSpecificBook($bookid);
			foreach ($validatebookcount as $rowsql1) {
				$totalnumber = $rowsql1['totalnumber'];
			}

			if(!$studentidvalidation){
				$error = "Student number not found. Please check the student number carefully or tell the student to create an account";
				$this->errorHandler2($error);
			}

			if($validateborrowed){
				$error = "This book has been borrowed by student number $studentid. Return the book first and then reborrow again.";
				$this->errorHandler2($error);
			}

			if($validateoverdue){
				$error = "This book is already overdue and is not returned yet. Please return the book first and then reborrow again";
				$this->errorHandler2($error);
			}

			if($totalnumber == 0){
				$error = "Book stock is 0.";
				$this->errorHandler2($error);
			}

			//all
			if(!$studentidvalidation){
				
			}

			else if($validateborrowed){
				
			}

			else if($validateoverdue){
				
			}
			else if($totalnumber == 0){
				
			}
			else{
				//condition to which sql to run. if pending, update data to
				//and change remarks to borrowed. if not, insert data
				$duedate = $this->adminBorrowAddDueDate($bookid, $studentid);
				$today = $this->getDateToday();

				if($validatepending){
					$query = "UPDATE borrow SET remarks = 'borrowed', borrowedDate = '$today', dueDate = '$duedate' WHERE borrowerid = '$studentid' AND bookid = '$bookid' AND remarks = 'pending'";
				}else{
					$query = "INSERT INTO borrow (borrowerid, bookid, borrowedDate, dueDate, remarks) VALUES ('$studentid', '$bookid', '$today', '$duedate', 'borrowed')";
				}

				$sql = $this->conn->query($query);
				if($sql){
					$this->decreaseBookCount($bookid);
					$user = new Users();
					$user->studentConfirmedNotification($studentid, $bookid);
					$success = "The book has been successfully borrowed";
					$this->successHandler2($success);
					$this->clearForm();
				}
				else{
					$error = "There was an error.";
					$this->errorHandler2($error);
					
				}
			}	
		}

		public function adminReturn($bookid, $studentid){
			$user = new Users();
			
			//validate if studentid is present in database
			$studentidvalidation = $user->fetchUsersTable($studentid);//1 means present
			//validate if bookid is present in database
			$bookidvalidation = $this->fetchSpecificBook($bookid);
			//1 means true
			//validate if the book is already borrowed or overdue
			$validateborrowed = $this->validateBorrowed($bookid, $studentid);//borrowed can be returned
			$validateoverdue = $this->validateOverdue($bookid, $studentid); //overdue can be returned
			$validatepending = $this->validatePending($bookid, $studentid); //pending can't be returned

			if(!$studentidvalidation){
				$error = "Student number not found. Please check the student number carefully or tell the student to create an account";
				$this->errorHandler2($error);
			}

			if($validatepending){
				$error = "This book is still pending.";
				$this->errorHandler2($error);
			}

			if($validateoverdue){

			}
			else if(!$validateborrowed){
				$error = "This book not borrowed.";
				$this->errorHandler2($error);
			}
			
			else{

			}

			//all
			if(!$studentidvalidation){
				
			}

			else if($validatepending){
				
			}
			else if($validateoverdue){
				$today = $this->getDateToday();

				$query = "UPDATE borrow SET remarks = 'returned', dateReturned = '$today', dueDate = 'na' WHERE borrowerid = '$studentid' AND bookid = '$bookid' AND (remarks = 'borrowed' OR remarks = 'overdue')";

				$sql = $this->conn->query($query);
				if($sql){
					$this->increaseBookCount($bookid);
					$this->clearForm();
					$success = "Success. The book has been successfully returned";
					$this->successHandler2($success);
					
				}
				else{
					$error = "There was an error.";
					$this->errorHandler2($error);
					
				}
			}
			else if(!$validateborrowed){
				
			}
			else{
				
				
				$today = $this->getDateToday();

				$query = "UPDATE borrow SET remarks = 'returned', dateReturned = '$today', dueDate = 'na' WHERE borrowerid = '$studentid' AND bookid = '$bookid' AND (remarks = 'borrowed' OR remarks = 'overdue')";

				$sql = $this->conn->query($query);
				if($sql){
					$this->increaseBookCount($bookid);
					$this->clearForm();
					$success = "Success. The book has been successfully returned";
					$this->successHandler2($success);
					
				}
				else{
					$error = "There was an error.";
					$this->errorHandler2($error);
					
				}
			}	
			
		}

		//this return is for the return function return found in borrow page
		public function returnBook($borrowid){
			//fetch bookid using borrowid
			$fetchbookid = $this->fetchBorrowTable($borrowid);
			foreach ($fetchbookid as $rowfetchbookid) {
				$bookid = $rowfetchbookid['bookid'];
			}
			//validate if there is book id
			$validate1 = !$borrowid;

			//validate is book id is either overdue or borrowed.
			$validate2 = $this->checkPendingBorrowId($borrowid);
			$validate3 = $this->checkReturnedBorrowId($borrowid);

			if($validate1){
				$error = "No data.";
				$this->errorHandler2($error);
			}

			if($validate2){
				$error = "This book is pending. You can't return pending books.";
				$this->errorHandler2($error);
			}
			if($validate3){
				$error = "Book with this transaction id is already returned.";
				$this->errorHandler2($error);
			}

			if($validate1){

			}
			else if($validate2){

			}
			else if($validate3){

			}
			else{
				$today = $this->getDateToday();
				//update borrow table
				$query = "UPDATE borrow SET remarks = 'returned', dateReturned = '$today', dueDate = 'na' WHERE borrowid = '$borrowid'";
				$sql = $this->conn->query($query);
				$this->increaseBookCount($bookid);//update book count
				$this->successHandler("Book has been returned");



			}


		}

		//functions for borrow form and return form
		//borrow form

		//functions to validate borrow id
		public function checkPendingBorrowId($borrowid){
			$query = "SELECT * FROM borrow WHERE borrowid = '$borrowid' AND remarks = 'pending'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		public function checkReturnedBorrowId($borrowid){
			$query = "SELECT * FROM borrow WHERE borrowid = '$borrowid' AND remarks = 'returned'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		//fetch and display pending for admin
		public function adminFetchPendingBooks(){
			$query = "SELECT * FROM `borrow` INNER JOIN `book` ON borrow.bookid = book.bookid WHERE borrow.remarks = 'pending'";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
		}

		public function adminDisplayPendingBooks(){
			?>
			<table class="table display dataTable" style="width:100%">
											<thead>
												<tr>
													<th>Borrower ID</th>
													<th>Borrower Name</th>
													<th>Title</th>
													<th>Subject</th>
													<th>Author</th>
													<th>Borrowed Date</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$data = $this->adminFetchPendingBooks();
												if(!empty($data)){
													foreach ($data as $row) {
														$borrowid = $row['borrowid'];
														$borrowerid = $row['borrowerid'];

														//get borrower name
														$user = new Users();
														$getBorrowerName = $user->adminGetBorrowerName($borrowerid);
														foreach ($getBorrowerName as $user) {
															$borrowername = $user['name'];
														}

														echo "<tr>";
														echo "<td>$borrowerid</td>";
														echo "<td>$borrowername</td>";
														echo "<td>".$row['title']."</td>";
														echo "<td>".$row['subject']."</td>";
														echo "<td>".$row['author']."</td>";
														echo "<td>".$row['borrowedDate']."</td>";
														echo "<td><a class = 'btn btn-primary' href = 'scriptconfirm.php?borrowid=$borrowid'>Confirm</a></td>";

														echo "</tr>";
													}
												}
												

												?>
											</tbody>
											
										</table>
			<?php
		}

		//fetch and display borrowed for admin
		public function adminFetchBorrowedBooks(){
			$query = "SELECT * FROM `borrow` INNER JOIN `book` ON borrow.bookid = book.bookid WHERE borrow.remarks = 'borrowed'";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
			
		}

		public function adminDisplayBorrowedBooks(){
			?>
			<table class="table display dataTable" style="width:100%">
											<thead>
												<tr>
													<th>Borrower ID</th>
													<th>Borrower Name</th>
													<th>Title</th>
													<th>Subject</th>
													<th>Author</th>
													<th class="text-success">Borrowed Date</th>
													<th>Due Date</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$data = $this->adminFetchBorrowedBooks();
												if(!empty($data)){
													foreach ($data as $row) {
														$borrowid = $row['borrowid'];
														$borrowerid = $row['borrowerid'];

														//get borrower name
														$user = new Users();
														$getBorrowerName = $user->adminGetBorrowerName($borrowerid);
														foreach ($getBorrowerName as $user) {
															$borrowername = $user['name'];
														}

														echo "<tr>";
														echo "<td>$borrowerid</td>";
														echo "<td>$borrowername</td>";
														echo "<td>".$row['title']."</td>";
														echo "<td>".$row['subject']."</td>";
														echo "<td>".$row['author']."</td>";
														echo "<td>".$row['borrowedDate']."</td>";
														echo "<td class = 'text-success'>".$row['dueDate']."</td>";
														echo "<td><a class = 'btn btn-success' href = 'scriptreturn.php?borrowid=$borrowid'>Return</a></td>";

														echo "</tr>";
													}
												}
												
												?>
											</tbody>
											
										</table>
			<?php
		}

		//fetch and display returned for admin
		public function adminFetchReturnedBooks(){
			$query = "SELECT * FROM `borrow` INNER JOIN `book` ON borrow.bookid = book.bookid WHERE borrow.remarks = 'returned'";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
			
		}

		public function adminDisplayReturnedBooks(){
			?>
			<table class="table display dataTable" style="width:100%">
											<thead>
												<tr>
													<th>Borrower ID</th>
													<th>Borrower Name</th>
													<th>Title</th>
													<th>Subject</th>
													<th>Author</th>
													<th>Borrowed Date</th>
													<th class="text-info">Returned Date</th>
													
												</tr>
											</thead>
											<tbody>
												<?php
												$data = $this->adminFetchReturnedBooks();
												if(!empty($data)){
													foreach ($data as $row) {
														$borrowid = $row['borrowid'];
														$borrowerid = $row['borrowerid'];

														//get borrower name
														$user = new Users();
														$getBorrowerName = $user->adminGetBorrowerName($borrowerid);
														foreach ($getBorrowerName as $user) {
															$borrowername = $user['name'];
														}

														echo "<tr>";
														echo "<td>$borrowerid</td>";
														echo "<td>$borrowername</td>";
														echo "<td>".$row['title']."</td>";
														echo "<td>".$row['subject']."</td>";
														echo "<td>".$row['author']."</td>";
														echo "<td>".$row['borrowedDate']."</td>";
														echo "<td class = 'text-info'>".$row['dateReturned']."</td>";
														

														echo "</tr>";
													}
												}
												
												?>
											</tbody>
											
										</table>
			<?php
		}

		//fetch and display overdue for admin
		public function adminFetchOverdueBooks(){
			$query = "SELECT * FROM `borrow` INNER JOIN `book` ON borrow.bookid = book.bookid WHERE borrow.remarks = 'overdue'";
			if($sql = $this->conn->query($query)){
				while($row = mysqli_fetch_assoc($sql)){
					$data[] = $row;
				}
			}
			return @$data;
			
		}

		public function adminDisplayOverdueBooks(){
			?>
			<table class="table display dataTable" style="width:100%">
											<thead>
												<tr>
													<th>Borrower ID</th>
													<th>Borrower Name</th>
													<th>Title</th>
													<th>Subject</th>
													<th>Author</th>
													<th>Borrowed Date</th>
													<th class="text-danger">Due Date</th>
													<th>Action</th>
													
												</tr>
											</thead>
											<tbody>
												<?php
												$data = $this->adminFetchOverdueBooks();
												if(!empty($data)){
													foreach ($data as $row) {
														$borrowid = $row['borrowid'];
														$borrowerid = $row['borrowerid'];

														//get borrower name
														$user = new Users();
														$getBorrowerName = $user->adminGetBorrowerName($borrowerid);
														foreach ($getBorrowerName as $user) {
															$borrowername = $user['name'];
														}

														echo "<tr>";
														echo "<td>$borrowerid</td>";
														echo "<td>$borrowername</td>";
														echo "<td>".$row['title']."</td>";
														echo "<td>".$row['subject']."</td>";
														echo "<td>".$row['author']."</td>";
														echo "<td>".$row['borrowedDate']."</td>";
														echo "<td class = 'text-danger'>".$row['dueDate']."</td>";
														echo "<td><a class = 'btn btn-success' href = 'scriptreturn.php?borrowid=$borrowid'>Return</a></td>";
														

														echo "</tr>";
													}
												}
												
												?>
											</tbody>
											
										</table>
			<?php
		}


		//function for requests (accept, return)
		/*============STUDENT=================*/

		/*============ADMIN====================*/
		//function to accept students borrow request
		public function adminConfirmBorrowRequest($borrowid){
			//get new borrowed date (Set the borrowed date from the day of confirmation)
			$borrowedDate = $this->getDateToday();
			//get due date
			$duedate = $this->addDueDate($borrowid);
			//validate if the borrowid is present
			$checkrequestpresent = $this->checkRequestPresent($borrowid);
			//validate if the request is already borrowed
			$checkrequestborrowed = $this->checkRequestBorrowed($borrowid);
			//get all the info in borrow table to extract the book id
			$fetchborrowtable = $this->fetchBorrowTable($borrowid);
			foreach ($fetchborrowtable as $row) {
				$bookid = $row['bookid'];
				$borrowerid = $row['borrowerid'];
			}
			//validate if the book count is not zero
			$checkbookcount = $this->checkBookCount($bookid);

			//start confirmation validation
			if($checkrequestpresent < 1){
				//checks if there is no such borrowid in table borrow
				$error = "No such data is present in database";
				$this->errorHandler($error);
			}
			else if($checkrequestborrowed > 0){
				//checks if such request is already borrowed.
				$error = "Request is already confirmed.";
				$this->errorHandler($error);
			}
			else if($checkbookcount < 1){
				//validates if the book is already 0 or out of stock
				$error = "No available book at at the moment. Wait for the students to return the book.";
				$this->errorHandler($error);
			}
			else{
				//with all the validators passed, confirm the borrow request.
				$query = "UPDATE borrow SET remarks = 'borrowed', borrowedDate = '$borrowedDate', dueDate = '$duedate' WHERE borrowid = '$borrowid'";
				$sql = $this->conn->query($query);
				if($sql){
					$user = new Users();
					//notify student that the book request is confirmed
					$user->studentConfirmedNotification($borrowerid, $bookid);
					//decrease bookcount
					$this->decreaseBookCount($bookid);
					//send notification
					//display success message
					$success = "Request Accepted.";
					$this->successHandler($success);
				}
				else{
					echo "An error occured";
				}
			}
		}

		/*============VALIDATORS===============*/
		//book validators
		public function checkBookPending($bookid, $borrowerid){
			$query = "SELECT * FROM borrow WHERE borrowerid = '$borrowerid' AND bookid = '$bookid' AND (remarks = 'pending')";
			$sql = $this->conn->query($query);
			while($row = mysqli_fetch_assoc($sql)){
				$data[] = $row;
			}
			return @$data;
			
		}

		private function checkBookBorrowed($bookid, $borrowerid){
			$query = "SELECT * FROM borrow WHERE borrowerid = '$borrowerid' AND bookid = '$bookid' AND (remarks = 'borrowed')";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		private function checkBookOverdue($bookid, $borrowerid){
			$query = "SELECT * FROM borrow WHERE borrowerid = '$borrowerid' AND bookid = '$bookid' AND (remarks = 'overdue')";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		public function checkBookCount($bookid){
			$query = "SELECT * FROM bookcount WHERE bookid = '$bookid'";
			$sql = $this->conn->query($query);
			while($row = mysqli_fetch_assoc($sql)){
				$totalnumber = $row['totalnumber'];
			}
			return $totalnumber;
		}

		//request validators
		//checks if such request is present in db
		private function checkRequestPresent($borrowid){
			$query = "SELECT * FROM borrow WHERE borrowid = '$borrowid'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}
		//checks if the student already borrowed the book
		private function checkRequestBorrowed($borrowid){
			$query = "SELECT * FROM borrow WHERE borrowid = '$borrowid' AND remarks = 'borrowed'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		//check if the request is pending
		private function checkRequestPending($borrowid){
			$query = "SELECT * FROM borrow WHERE borrowid = '$borrowid' AND remarks = 'pending'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		//check if the request is overdue
		private function checkRequestOverdue($borrowid){
			$query = "SELECT * FROM borrow WHERE borrowid = '$borrowid' AND remarks = 'overdue'";
			$sql = $this->conn->query($query);
			$result = mysqli_num_rows($sql);
			return $result;
		}

		//error and success handlers


		private function errorHandler($error){
			?>
			<script type="text/javascript">
				var error = "<?php echo $error;?>";
				alert(error);
				history.go(-1);
			</script>
			<?php
		}

		private function successHandler($success){
			?>
			<script type="text/javascript">
				var success = "<?php echo $success;?>";
				alert(success);
				history.go(-1);
			</script>
			<?php
		}

		//used in ajax
		private function successHandler2($success){
			?>
			<h5 class="text-success"><?php echo $success;?></h5>
			<?php
		}

		public function errorHandler2($error){
			?>
			<h5 class="text-danger"><?php echo $error;?></h5>
			<?php
		}

		//create books
		public function displayAddBook(){
			$publisher = new Publisher();
			?>
			<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAddBook"><i class="fas fa-plus-circle"></i> Add Book</button>

			<form action="index.php" id="frmAddBook" method="POST">
				
				<!-- Modal to edit admin info -->
				<div class="modal fade" id="modalAddBook" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="staticBackdropLabel">Add Book</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<?php
								//logic here to see if there is publisher or none
								$publisher = new Publisher();
								$sql = $publisher->fetchAllPublisher();
								if($sql){
									?>
									<!--display the inputs-->
									<div class="form-group">
									<label>Title</label>
									<input type="text" name="txtbooktitle" id="txtbooktitle" class="form-control">
								</div>

								
								<div class="form-group">
									<label>Subject</label>
									<input type="text" name="txtsubject"  id="txtsubject" class="form-control">
								</div>


								<div class="form-group">
									<label>Author</label>
									<input type="text" name="txtauthor" id="txtauthor" class="form-control">
								</div>

								<div class="form-group">
									<label>Stock</label>
									<input type="text" name="txtstock" id="txtstock" class="form-control">
								</div>



								<div class="form-group">
									<label>Publisher</label>
									<select class="form-control" id="selpublisher">
										<option value="select">Select publisher</option>
										<?php
										//code to display values of select
										foreach ($sql as $row) {
											$publisherid = $row['publisherid'];
											$publishername = $row['publishername'];
											?>

											<option value="<?php echo $publisherid?>"><?php echo $publishername;?></option>

											<?php
										}
										?>
									</select>
									<?php
									?>


								</div>




								<div class="form-group">
									<label>Year Published</label>
									<input type="number" name="txtyearpublished" id="txtyearpublished" class="form-control">
								</div>
									<?php
								}
								else{
									?>
									<h5 class = "text-danger">No publisher at the moment. Add Publisher first by heading to Publisher Tab and the click <i class="fas fa-plus-circle text-success"></i><span class = "text-success"> Add publisher</span></h5>
									<?php
									
								}
								?>

								
								

								
								
							</div>
							<div class="form-group">
									<span class="addBookMessage"></span>
							</div>
							
							<div class="modal-footer">
								
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

								<?php
								//hide save button if there is no publisher
								$sql2 = $publisher->fetchAllPublisher();
								if($sql2){
									?>
									<button type="submit" id="btnSaveAddBook" value="save" class="btn btn-success">Save</button>
									<?php
								}
								?>

								
							</div>


						</div>

					</div>
				</div>
			</form>
			<?php
		}

		public function validateBooks($booktitle, $subject, $author, $stock, $publisherid, $yearpublished){
				$bookid = $this->getUniqueTime();
				if(!$booktitle){
					$error = "Please input book title";
					$this->errorHandler2($error);
				}
				if(!$subject){
					$error = "Please input subject";
					$this->errorHandler2($error);
				}
				if(!$author){
					$error = "Please input author";
					$this->errorHandler2($error);
				}

				if($publisherid == "select"){
					$error = "Please select publisher";
					$this->errorHandler2($error);
				}
				
				if(!$yearpublished){
					$error = "Please input yearpublished";
					$this->errorHandler2($error);
				}

				if(!$stock){
					$stock = 0;
				}

				

				//all in one validation

				if(!$booktitle){

				}
				else if(!$subject){

				}
				else if(!$author){

				}
				else if(!$publisherid){

				}
				else if($publisherid == "select"){
					
				}
				else if(!$yearpublished){

				}
				
				else{
					$this->saveBook($booktitle, $subject, $author, $stock, $publisherid, $yearpublished, $bookid);
				}

		}
		public function saveBook($booktitle, $subject, $author, $stock, $publisherid, $yearpublished, $bookid){


			$query1 = "INSERT INTO book (bookid, title, subject, author, publisherId, yearPublished) VALUES ('$bookid', '$booktitle', '$subject', '$author', '$publisherid', '$yearpublished')";
			$query2 = "INSERT INTO bookcount (bookid, totalnumber) VALUES ('$bookid', '$stock')";

			$sql1 = $this->conn->query($query1);
			$sql2 = $this->conn->query($query2);
			if($sql1 && $sql2){
				$this->clearForm();
				$success = "Book has been successfully saved.";
				$this->successHandler2($success);
				// $redirect = "adminhome.php";
				// $ms = 1500;
				// $this->timeoutRedirectHandler($redirect, $ms);


			}else{
				$error = "There's an error.";
				$this->errorHandler2($error);
			}
		}

		//edit books

		public function displayEditBook($bookid){
			$publisher = new Publisher();

			?>
			<form action="index.php" id="frmEditBook" method="POST">
				
				<!-- Modal to edit admin info -->
				<?php
				//code here to fetch the book info
				$fetchbookssql = $this->fetchSpecificBook($bookid);
				if($fetchbookssql){
					foreach ($fetchbookssql as $rowfetchbookssql) {

					$title = $rowfetchbookssql['title'];
					$subject = $rowfetchbookssql['subject'];
					$author = $rowfetchbookssql['author'];
					$stock = $rowfetchbookssql['totalnumber'];
					$publisherid = $rowfetchbookssql['publisherId'];
					$yearpublished = $rowfetchbookssql['yearPublished'];

					?>
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="staticBackdropLabel">Edit Book</h5>
								
							</div>
							<div class="modal-body">
									<input type="hidden" id="txthiddeneditbookid" value="<?php echo $bookid;?>" name="">
									<!--display the inputs-->
									<div class="form-group">
									<label>Title</label>
									<input type="text" name="txteditbooktitle" value="<?php echo $title;?>" id= "txteditbooktitle" class="form-control">
								</div>

								
								<div class="form-group">
									<label>Subject</label>
									<input type="text" name="txteditsubject" value="<?php echo $subject;?>" id="txteditsubject" class="form-control">
								</div>


								<div class="form-group">
									<label>Author</label>
									<input type="text" name="txteditauthor" value="<?php echo $author;?>"  id="txteditauthor" class="form-control">
								</div>

								<div class="form-group">
									<label>Stock</label>
									<input type="text" name="txteditstock" id="txteditstock" value="<?php echo $stock;?>"  class="form-control">
								</div>



								<div class="form-group">
									<label>Publisher</label>
									<select class="form-control" id="seleditpublisher">
										<?php
										//code to display the publisher name
										$fetchspecificpublisher = $publisher->fetchSpecificPublisher($bookid);
										if($fetchspecificpublisher){
											foreach ($fetchspecificpublisher as $rowfetchspecificpublisher) {
											$dbpublisherid = $rowfetchspecificpublisher['publisherid'];
											$dbpublishername = $rowfetchspecificpublisher['publishername'];
											?>

											<option value="<?php echo $dbpublisherid?>">Current: <?php echo $dbpublishername;?></option>

											<?php

										}
										}
										else{
											?>
											<span>Publisher data has been deleted. Please select another publisher</span>
											<option value="select">Select Publisher</option>
											<?php
										}

										//code to display values of select or all publishers
										$sqlpublisher = $publisher->fetchAllPublisher();
										foreach ($sqlpublisher as $row) {
											$publisherid = $row['publisherid'];
											$publishername = $row['publishername'];
											?>

											<option value="<?php echo $publisherid?>"><?php echo $publishername;?></option>

											<?php
										}
										?>
									</select>
									<?php
									?>


								</div>




								<div class="form-group">
									<label>Year Published</label>
									<input type="number" name="txtedityearpublished" value="<?php echo $yearpublished;?>" id="txtedityearpublished" class="form-control">
								</div>
									
								
									
								
								

								
								
							</div>
							<div class="form-group">
									<span class="editBookMessage"></span>
							</div>
							
							<div class="modal-footer">
								
								
								
									<button type="submit" id="btnSaveEditBook" value="save" class="btn btn-success">Save</button>
									
							</div>


						</div>

					</div>
					<?php
				}
				}
				else{
					echo "<h5 class = 'text-danger'>No data at the moment</h5>";
				}

				
				?>
					
				
			</form>
			<?php
		}


		public function validateEditBook($bookid, $booktitle, $subject, $author, $stock, $publisherid, $yearpublished){
			
			if(!$booktitle){
				$error = "Please input book title";
				$this->errorHandler2($error);
			}
			if(!$subject){
				$error = "Please input subject";
				$this->errorHandler2($error);
			}
			if(!$author){
				$error = "Please input author";
				$this->errorHandler2($error);
			}

			if($publisherid == "select"){
				$error = "Please select publisher";
				$this->errorHandler2($error);
			}
			
			if(!$yearpublished){
				$error = "Please input yearpublished";
				$this->errorHandler2($error);
			}

			if(!$stock){
				$stock = 0;
			}

			

			//all in one validation

			if(!$booktitle){

			}
			else if(!$subject){

			}
			else if(!$author){

			}
			else if(!$publisherid){

			}
			else if($publisherid == "select"){
				
			}
			else if(!$yearpublished){

			}
			
			else{
				$this->saveEditBook($booktitle, $subject, $author, $stock, $publisherid, $yearpublished, $bookid);
			}	
		}

		public function saveEditBook($booktitle, $subject, $author, $stock, $publisherid, $yearpublished, $bookid){
			
			$querysavebook = "UPDATE book SET title = '$booktitle', subject = '$subject', author = '$author', publisherId = '$publisherid', yearPublished = '$yearpublished' WHERE bookid = '$bookid'";
			$querysavecount = "UPDATE bookcount SET totalnumber = '$stock' WHERE bookid = '$bookid'";
			
			$sql1 = $this->conn->query($querysavebook);
			$sql2 = $this->conn->query($querysavecount);
			

			if($sql1 && $sql2){
				$success = "Book info has been edited successfully";
				$redirect = "adminhome.php#allbooks";
				$ms = 1200;
				$this->successHandler2($success);
				$this->timeoutRedirectHandler($redirect, $ms);
			}
			else{
				$error = "There's an error";
				$this->errorHandler2($error);
			}



		}


		//book modifications (increase book count and decrease book count)
		public function decreaseBookCount($bookid){
			$query = "SELECT * FROM bookcount WHERE bookid = '$bookid'";
			$sql = $this->conn->query($query);
			while($row = mysqli_fetch_assoc($sql)){
				$totalnumber = $row['totalnumber'];
			}

			$updatedcount = $totalnumber - 1;
			$updatequery = "UPDATE bookcount SET totalnumber = '$updatedcount' WHERE bookid = '$bookid'";
			$updatesql = $this->conn->query($updatequery);
		}

		public function increaseBookCount($bookid){
			$query = "SELECT * FROM bookcount WHERE bookid = '$bookid'";
			$sql = $this->conn->query($query);
			while($row = mysqli_fetch_assoc($sql)){
				$totalnumber = $row['totalnumber'];
			}

			$updatedcount = $totalnumber + 1;
			$updatequery = "UPDATE bookcount SET totalnumber = '$updatedcount' WHERE bookid = '$bookid'";
			$updatesql = $this->conn->query($updatequery);
		}

		//book counter
		public function allBooksCounter(){
			$query = "SELECT * FROM book";
			$sql = $this->conn->query($query);
			$totalnumber = mysqli_num_rows($sql);
				return $totalnumber;
		}


		//redirect 3 seconds
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

		public function clearForm(){
			?>
			<script type="text/javascript">
				$("#frmAddBook").trigger('reset');
				$("#frmAdminReturn").trigger('reset');
				$("#frmAdminBorrow").trigger('reset');
				
			</script>
			<?php
		}
	}



?>