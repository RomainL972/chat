<?php

class UsersController
{
	private $mysqli;

	function __construct()
	{
		$this->mysqli = sql_connect();
	}

	function GET()
	{
		$result = sql_query($this->mysqli, "SELECT username FROM users");
		$data = $result->fetch_all(MYSQLI_ASSOC);
		$result->close();
		return json_encode($data);
	}

	function POST()
	{
		global $data;

		//Check for errors in query
		if(empty($data["username"]))
			error(400, "No username provided");
		if(strlen($data["username"]) > 50)
			error(400, "Username too long");
		if(empty($data["password"]))
			error(400, "No password");

		//Password hash
		$password = password_hash($data["password"], PASSWORD_BCRYPT);

		sql_query($this->mysqli, "INSERT INTO users(username, password) VALUES (?, ?)", array($data["username"], $password))->close();
	}

	function DELETE()
	{
		$user = $_SERVER["PHP_AUTH_USER"];

		sql_query($this->mysqli, "DELETE FROM users WHERE username=?", array($user))->close();
	}
}