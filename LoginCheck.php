<?php
	error_reporting(0);

	$connection = mysqli_connect("localhost", "root", "", "accommodation_database") or die("Failed to connect to server : " . mysqli_connect_error());

	//$logged = false;
	if($_COOKIE['c_user'] && $_COOKIE['c_salt']) {
		$cuser = mysqli_real_escape_string($connection, $_COOKIE['c_user']);
		$csalt = mysqli_real_escape_string($connection, $_COOKIE['c_salt']);
		$user = mysqli_fetch_array(mysqli_query($connection, "SELECT Salt, StudentNumber FROM students WHERE Salt = '$csalt'"));
		
		if($user != false) {
			if(hash("sha512", $user['StudentNumber']) == $cuser && $csalt = $user['Salt']) {
				//$logged = true;
				header("Location: LoggedIn.php");
			} else {
				header("Location: index.html");
			}
		} else {
			header("Location: index.html");
		}
	} else {
		header("Location: index.html");
	}
?>
