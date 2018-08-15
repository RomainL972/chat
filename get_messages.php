<?php

sql_connect();

function sql_connect()
{
	$mysqli = new mysqli(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASS"), "chat");
	$now =time();

	if ($mysqli->connect_error) {
		die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	if($stmt = $mysqli->query('SELECT message, user, `time` FROM messages')) {
		$array[0] = $stmt->fetch_all(MYSQLI_ASSOC);
		$array[1] = $_SERVER["PHP_AUTH_USER"];
		echo json_encode($array);
	}
	else
		die("Error : " . $mysqli->error);

}