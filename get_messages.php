<?php

while (sql_connect() != 1) {
	sleep(0.1);
}
// sql_connect();

function sql_connect()
{
	$mysqli = new mysqli(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASS"), "chat");

	if ($mysqli->connect_error) {
		die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	$query = "SELECT id, message, user, `time` FROM messages";
	if(isset($_POST["id"])) {
		$id = (int)$_POST["id"];
		$stmt = $mysqli->prepare($query . " WHERE id > ?");
		$stmt->bind_param("d", $id);
		$stmt->execute();

		$result = $stmt->get_result();
	}
	else {
		$result = $mysqli->query($query);
	}
	$array[0] = $result->fetch_all(MYSQLI_ASSOC);
	if(!$array[0])
		return 0;
	// foreach ($array[0] as $msg) {
	// 	echo htmlspecialchars($msg["message"]) . "\n";
	// }
	$array[1] = $_SERVER["PHP_AUTH_USER"];
	// die(print_r($array));
	echo json_encode($array);
	return 1;
}