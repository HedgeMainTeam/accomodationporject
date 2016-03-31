<?php
	error_reporting(0);

	$connection = mysqli_connect("localhost", "root", "", "accommodation_database") or die("Failed to connect to server : " . mysqli_connect_error());
	
	if($_POST['signup']) {
		if($_POST['stdname'] && $_POST['number'] && $_POST['stdpass'] && $_POST['stdpass2']) {

			$fullname = mysqli_real_escape_string($connection, $_POST['stdname']);
			$sex = mysqli_real_escape_string($connection, $_POST['sex']);
			
			$password = mysqli_real_escape_string($connection, hash("sha512", $_POST['stdpass']));
			$password2 = mysqli_real_escape_string($connection, hash("sha512", $_POST['stdpass2']));

			$studentNumber = mysqli_real_escape_string($connection, $_POST['number']);
			
			//Verify fullname
			$aValid = array(" ");
			$sVerify = $fullname;
			$sVerify = str_replace($aValid, "", $sVerify);
			if(!ctype_alnum($sVerify)) {
				die("Full name contains special characters only numbers and letters are permitted! <a href = 'signup.html'>&larr; Back</a>");
			}
			if(strlen($fullname) > 40) {
				die("Full name must contain less than 40 characters! <a href = 'signup.html'>&larr; Back</a>");
			}

			//Verify password
			if($password != $password2) {
				die("Password fields don't match! <a href = 'signup.html'>&larr; Back</a>");
			}
			
			//Varify studentNumber
			if(!ctype_digit($studentNumber)) {
				die("Please enter a valid student number! <a href = 'signup.html'>&larr; Back</a>");
			}

			$check = mysqli_fetch_array(mysqli_query($connection, "SELECT StudentNumber FROM students WHERE StudentNumber = '$studentNumber'"));
			if($check != false) {
				die("The specified student number already exists! Please try again. <a href = 'signup.html'>&larr; Back</a>");
			}
			
			//make sure the sex is valid
			switch($sex) {
				case "male":
					break;
				case "female":
					break;
				default:
					die("Please pick a valid sex <a href = 'signup.html'>&larr; Back</a>");
					break;
			}
			
			$salt = hash("sha512", rand() . rand() . rand());
			
			//Insert all the data into the database
			$query = "INSERT INTO students (FullName, Sex, Password, StudentNumber, Salt) VALUES ('$fullname', '$sex', '$password', '$studentNumber', '$salt')";
			mysqli_query($connection, $query);
			
			//Set cookies to keep the user logged in
			setcookie("c_user", hash("sha512", $studentNumber), time() + 24 * 60 * 60, "/");
			setcookie("c_salt", $salt, time() + 24 * 60 * 60, "/");
			header("Location: LoggedIn.php");
			print("End reached");
		} else {
			die("Please fill in all the fields! <a href = 'signup.html'>&larr; Back</a>");
		}
	}
;?>