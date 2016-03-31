<?php
	error_reporting(0);

	if($_COOKIE['c_user'] && $_COOKIE['c_salt']) {
		setcookie("c_user", "", time() - 3600, "/");
		setcookie("c_salt", "", time() - 3600, "/");
	}
	header("Location: index.html");
?>