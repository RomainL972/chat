<?php 

class RoomsController
{
	private $mysqli;

	function __construct()
	{
		$this->mysqli = sql_connect();
	}

	function GET() {
		$user = $_SERVER["PHP_AUTH_USER"];
		
		$result = sql_query($this->mysqli, "SELECT id, name FROM rooms");
		$data = $result->fetch_all(MYSQLI_ASSOC);
		return json_encode($data);
	}

	function POST() {
		global $data, $response_code;
		$user = $_SERVER["PHP_AUTH_USER"];

		if(empty($data["name"]))
			error(400, "No name specified");

		sql_query($this->mysqli, "INSERT INTO rooms(name) VALUES (?)", array($data["name"]));
		$response_code = 201;
	}
}