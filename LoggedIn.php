<?php
	error_reporting(0);

	$connection = mysqli_connect("localhost", "root", "", "accommodation_database") or die("Failed to connect to server : " . mysqli_connect_error());

	if(isSet($_COOKIE['c_user']) && isSet($_COOKIE['c_salt'])) {
		$salt = $_COOKIE['c_salt'];
		$user = mysqli_fetch_array(mysqli_query($connection, "SELECT StudentNumber, FullName, HasRoom, RoomNumber, Building, RoomType FROM students WHERE Salt = '$salt'"));
		$fullName = $user['FullName'];
		$hasRoom = $user['HasRoom'];
		$building = $user['Building'];
		$roomNumber = $user['RoomNumber'];
		$roomType = $user['RoomType'];
		
		//replace underscores in building name
		$aReplace = array("_");
		$sbuildingName = $building;
		$sbuildingName = str_replace($aReplace, " ", $sbuildingName);
	} else {
		die("Plase try and login <a href = 'index.html'> &larr; back </a>");
	}
?>

<!DOCTYPE html>
<!-- Template by html.am -->
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Accomodation Application</title>
		<link rel="stylesheet" type="text/css" href = "style.css"/>
	</head>

	<body>
		<div id="container">
			<main id="center" class="column">
				<header id="header">
					<p>
						<h1>ACCOMODATION</h1>
					</p>
				</header>
				<article>
					<br/>
						<center>
							<h2><?php print($fullName); ?></h2>
							<p>
								<h4>
									<?php
										if($hasRoom == false) {
											echo "You Currently have no room
											<p><a href = 'tandC.html'><button id= 'logoutButton'>Get Room</button></a></p>"; 
										} else {
											echo"Your room number is ".strtoupper($roomType).$roomNumber." in $sbuildingName";
										}
									?>
								</h4>
							</p>
						</center>
					<center>
						<a href = "logout.php">
							<button id= "logoutButton">Logout</button>
						</a>
					</center>
				</article>
			</main>

			<nav id="left" class="column">
				<center><h4> Welcome </h4>
					<img src = "images/logo.png" /><br/><br/>
				</center>
			</nav>

			<div id="right" class="column">
				<br/><br/><br/><br/><br/><br/><div id = "images">
				<img src= "images/first.png"/><br/><br/>
				<img src= "images/sec.png"/><br/><br/>
				<img src= "images/third.png"/><br/><br/>
				</div>
			</div>
		</div>
	</body>
</html>