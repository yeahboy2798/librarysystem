<?php
	include("includes/autoloader.inc.php");
	include_once("sessionchecknouser.php");
	include_once("sessioncheckadmin.php");
	// echo $_SESSION['account_id'];
	

	
	
?>

<?php
@$bookid = $_GET['bookid'];
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>




<div class="container">
 <?php
 //display nav
 $element->displayNav();

 //display main body
echo "<br>";
echo "<br>";
echo "<br>";



//php code here get book title and fetch book id  if present
$book = new Books();
$fetchspecificbook = $book->fetchSpecificBook($bookid);







if($bookid AND $fetchspecificbook){
		foreach ($fetchspecificbook as $rowfetchspecificbook) {
		$title = $rowfetchspecificbook['title'];
		}
	?>
	<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Borrow book</h4>
       
      </div>
      <div class="modal-body">
        <form action="" id="frmAdminBorrow" method="POST">
        	
	 			<label><h5>Book title: <span class="text-primary"><?php echo $title;?></span></h5></label>
	 			<br>
	 			<br>
	 			<input type="hidden" id="txtadminborrowbookid" name="" value="<?php echo $bookid;?>">
	 			<input type="text" class="form-control" name="txtborrowstudentid" id="txtborrowstudentid" placeholder="Enter student id"><br>
	 			
	 			<input type="submit" name="btnadminborrow" id="btnadminborrow" class="btn btn-primary" value="Borrow">

	 			<span class="adminborrowmessage"></span>

	 	</form>
      </div>
     
    </div>
  </div>
	<?php
}
else{
	$book = new Books();
	$error = "Book number not found.";
	$book->errorHandler2($error);
	$book->timeoutRedirectHandler("index.php", 1300);
}

 ?>






</div>
<!--End main body-->
<?php
	
	$element->getComponents();
?>
  
</body>
</html>


