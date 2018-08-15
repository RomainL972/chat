<?php
if (!isset($_POST["message"])) {
	header("HTTP/1.0 400 Bad Request");
	die("There is no message");
}
if (empty($_POST["message"])) {
	header("Location: /index.php?error=1");
}
if (strlen($_POST["message"]) > 50) {
	header("Location: /index.php?error=2");
}
if (strlen($_SERVER["PHP_AUTH_USER"]) > 20) {
	header("Location: /index.php?error=3");
}

sql_connect();

function sql_connect()
{
	$mysqli = new mysqli(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASS"), "chat");
	$now =time();

	if ($mysqli->connect_error) {
		die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	if($stmt = $mysqli->prepare('INSERT INTO messages (`message`, `user`, `time`) VALUES (?, ?, ?)')) {
		$stmt->bind_param("ssi", $_POST["message"], $_SERVER["PHP_AUTH_USER"], $now);

		$stmt->execute();
	}
	else
		die("Error : " . $mysqli->error);

	header("Location: /index.php");
}