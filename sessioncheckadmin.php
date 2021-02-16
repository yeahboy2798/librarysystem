<?php
	@$accountid = $_SESSION['account_id'];
	if($_SESSION['account_id']){
		$field = "account_id";
		$rows = $user->getUserInfo($field, $accountid);
		if(!empty($rows)){
			foreach($rows as $row){
				$accountType = $row['accountType'];
			}

			if($accountType == "admin"){
				
			}
			else{
				header("location: home.php");
			}
		}
	}
	else{
		header("location: index.php");
	}
?>