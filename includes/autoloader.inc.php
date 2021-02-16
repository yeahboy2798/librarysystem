<?php
	spl_autoload_register('classAutoLoader');
	session_start();
	$publisher = new Publisher();
	$book = new Books();
	$user = new Users();
	$element = new Elements();
	$system = new System();
	
	$book->evaluateDueDate();

	

	function classAutoLoader($className){
		$path = 'classes/';
		$extension = ".class.php";
		$fullPath = $path . $className . $extension;

		if(!file_exists($fullPath)){
			return false;
		}
		else{
			include_once $fullPath;
			// $user = new Users();
			// $message = "Yey";
			// $user->testFunction($message);
		}
		
	}
?>

