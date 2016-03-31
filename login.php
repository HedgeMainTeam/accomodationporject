<?php
	error_reporting(0);

	$connection = mysqli_connect("localhost", "root", "", "accommodation_database") or die("Failed to connect to server : " . mysqli_connect_error());

	if($_POST['login']) {
		if($_POST['stdNumber'] && $_POST['stdPass']) {
			
			$studentNumber = mysqli_real_escape_string($connection, $_POST['stdNumber']);
			$password = mysqli_real_escape_string($connection, hash("sha512", $_POST['stdPass']));
			
			$user = mysqli_fetch_array(mysqli_query($connection, "SELECT StudentNumber, Password, ID FROM students WHERE StudentNumber = '$studentNumber'"));
			
			if($user == false) {
				die("Student Number not found or doesn't exist! <a href = 'index.html'> &larr; Back</a>");
			}
			if($user['Password'] != $password) {
				die("Incorrect password! <a href = 'index.html'> &larr; Back</a>");
			}
			
			$salt = hash("sha512", rand() . rand() . rand());
			setcookie("c_user", hash("sha512", $studentNumber), time() + 24 * 60 * 60, "/");
			setcookie("c_salt", $salt, time() + 24 * 60 * 60, "/");
			$userID = $user['ID'];
			mysqli_query($connection, "UPDATE students SET Salt = '$salt' WHERE ID = '$userID'");
			//$fullName = $user['FullName'];
			header('Location: LoggedIn.php');
			//die("You are now logged in as : $fullName");
		} else {
			die("Please fill in the required data <a href = 'index.html'> &larr; Back</a>");
		}
	}
	//include "LoginCheck.php";
	
	//if($logged == true) {
		//die("You are already logged in");
	//}
?>