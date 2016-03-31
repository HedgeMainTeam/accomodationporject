<?php
	error_reporting(0);
	$connection = mysqli_connect("localhost", "root", "", "accommodation_database") or die("Failed to connect to server : " . mysqli_connect_error());
	
	$lastBuildingIndex = mysqli_fetch_array(mysqli_query($connection, "SELECT BuildingID FROM buildings_data ORDER BY BuildingID DESC LIMIT 1"));

	for($i = 1; $i <= $lastBuildingIndex['BuildingID']; $i++) {
		$buildingRowData = mysqli_fetch_array(mysqli_query($connection, "SELECT BuildingID, RoomType, OccupiedRooms, MaxRooms, BuildingName, HasFemaleRooms, HasMaleRooms FROM buildings_data WHERE BuildingID = '$i'"));
		
		//Store the building data
		$buildingId = $buildingRowData['BuildingID'];
		$roomType = $buildingRowData['RoomType'];
		$occupiedRooms = $buildingRowData['OccupiedRooms'];
		$maxRooms = $buildingRowData['MaxRooms'];
		$buildingName = $buildingRowData['BuildingName'];
		$hasFemaleRooms = $buildingRowData['HasFemaleRooms'];
		$hasMaleRooms = $buildingRowData['HasMaleRooms'];
		$maxOccupantsPerRoom = 4;
		
		if($maxRooms == $occupiedRooms) {
			if($i == $lastBuildingIndex['BuildingID']) {
				die("Unfortunately there are no more free rooms <a href = LoggedIn.php>&larr; back </a>");
			}
			continue;
		} else {
			//Get the sex of the current user and determine if the current building takes this sex
			$currentUser = $_COOKIE['c_salt'];
			$query = "SELECT Sex FROM students WHERE Salt = '$currentUser'";
			$sex = mysqli_fetch_array(mysqli_query($connection, $query));
			
			$validBuilding;
			switch($sex['Sex']) {
				case "male":
					if($hasMaleRooms) {
						$validBuilding = true;
					} else {
						$validBuilding = false;
					}
					break;
				case "female":
					if($hasFemaleRooms) {
						$validBuilding = true;
					} else {
						$validBuilding = false;
					}
					break;
				default:
					die("Invalid sex <a href = LoggedIn.php>&larr; back</a>");
					break;
			}
			
			if($validBuilding == true) {
				$buildingTableName = $buildingName.$roomType;
				$lastRoomIndex = mysqli_fetch_array(mysqli_query($connection, "SELECT RoomNumber, Occupants FROM $buildingTableName ORDER BY RoomNumber DESC LIMIT 1"));

				if($lastRoomIndex == false) {
					$query = "INSERT INTO $buildingTableName (Occupants, RoomLetter) VALUES ('0', '$roomType')";
					$test = mysqli_query($connection, $query);

					$lastRoomIndex = mysqli_fetch_array(mysqli_query($connection, "SELECT RoomNumber, Occupants FROM $buildingTableName ORDER BY RoomNumber DESC LIMIT 1"));
				}
				
				$currentOccupants = $lastRoomIndex['Occupants'];
				$roomNumber = $lastRoomIndex['RoomNumber'];

				if($currentOccupants < $maxOccupantsPerRoom) {
					//Insert all the data into the database
					$currentOccupants++;
					$query = "UPDATE $buildingTableName SET Occupants = '$currentOccupants', RoomLetter = '$roomType' WHERE RoomNumber = '$roomNumber'";
					$test = mysqli_query($connection, $query);

					$query = "UPDATE students SET HasRoom = '1', RoomNumber = '$roomNumber', Building = '$buildingName', RoomType = '$roomType' WHERE Salt = '$currentUser'";
					$test = mysqli_query($connection, $query);
					
					if($currentOccupants == $maxOccupantsPerRoom) {
						$occupiedRooms++;
						$query = "UPDATE $buildingTableName SET Status = 'Full' WHERE RoomNumber = $roomNumber";
						$test = mysqli_query($connection, $query);

						$query = "UPDATE buildings_data SET OccupiedRooms = '$occupiedRooms' WHERE BuildingID = $buildingId";
						$test = mysqli_query($connection, $query);
					}
					
					header("Location: LoggedIn.php");
				} else {
					if($roomNumber + 1 <= $maxRooms) {
						$roomNumber++;
						$query = "INSERT INTO $buildingTableName (Occupants, RoomLetter) VALUES ('1', '$roomType')";
						$test = mysqli_query($connection, $query);

						$query = "UPDATE students SET HasRoom = '1', RoomNumber = '$roomNumber', Building = '$buildingName', RoomType = '$roomType' WHERE Salt = '$currentUser'";
						$test = mysqli_query($connection, $query);

						header("Location: LoggedIn.php");
					}
				}
			} else {
				continue;
			}
			break;
		}
	}
?>