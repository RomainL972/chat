<?php

while (sql_connect() != 1) {
	sleep(0.1);
}

function sql_connect()
{
	//Connect to database using environment variables (set in /etc/apache2/envvars)
	$mysqli = new mysqli(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASS"), getenv("DATABASE"));

	if ($mysqli->connect_error) {
		die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}

	//Define the SQL query
	$query = "SELECT * FROM messages";
	if(isset($_POST["mode"]))
		$query .= " WHERE id < ?";
	else if(isset($_POST["id"]))
		$query .= " WHERE id > ?";
	$query .= " ORDER BY id DESC LIMIT 20 ";
	
	//Execute the query
	if(isset($_POST["id"])) {
		$id = (int)$_POST["id"];
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("d", $id);
		$stmt->execute();

		$result = $stmt->get_result();
	}
	else {
		$result = $mysqli->query($query);
	}

	//Get the result
	$array[0] = $result->fetch_all(MYSQLI_ASSOC);
	if(!$array[0])
		return 0;
	
	//Send the result
	$array[1] = $_SERVER["PHP_AUTH_USER"];
	echo json_encode($array);
	return 1;
}