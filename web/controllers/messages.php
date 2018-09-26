<?php

class MessagesController
{
	private $mysqli, $context, $socket;

	function __construct()
	{
		$this->mysqli = sql_connect();
	}

	function GET(){
		global $data;
		$user = $_SERVER["PHP_AUTH_USER"];

		//Check for errors in query
		if(empty($data["room"]))
			error(400, "No room specified");
		if(!empty($data["before"]) && !empty($data["after"]))
			error(400, "Before and After can't be specified and the same time");
		if(!empty($data["before"]) && (empty($data["id"]) || !empty($data["time"])))
			error(400, "Before can only and must go with id");

		//Init query and args
		$query = "SELECT id, `from`, text, created_at, changed, created_at, modified_at FROM messages WHERE room=?";
		$args = array($data["room"]);

		//Set condition sign
		if(!empty($data["before"]))
			$sign = "<";
		elseif (!empty($data["after"]))
			$sign = ">";

		//Add conditions
		if(!empty($data["id"])) {
			$query .= " AND id $sign ?";
			$args[] = $data["id"];
		}
		if(!empty($data["time"])) {
			$query .= " AND time $sign ?";
			$args[] = $data["time"];
		}

		$result = sql_query($this->mysqli, $query, $args);

		$json = $result->fetch_all(MYSQLI_ASSOC);
		$result->close();

		return json_encode($json);
	}

	function POST(){
		global $data;
		$user = $_SERVER["PHP_AUTH_USER"];

		//Check for errors in request
		if(empty($data["text"]))
			error(400, "No message");
		if(strlen($data["text"]) > 500)
			error(400, "Message too long");
		if(empty($data["room"]))
			error(400, "No room");

		sql_query($this->mysqli, "INSERT INTO messages(`from`, room_id, text) VALUES (?, ?, ?)", array($user, $data["room"], $data["text"]))->close();
	}

	function PATCH(){
		$user = $_SERVER["PHP_AUTH_USER"];
		global $data;

		//Check for errors in request
		if(empty($data["id"]))
			error(400, "No ID");
		if(empty($data["text"]))
			error(400, "No text");

		sql_query($this->mysqli, "UPDATE messages SET text=?, changed=1 WHERE id=? AND `from`=?", array($data["text"], $data["id"], $user))->close();
	}

	function DELETE(){
		$user = $_SERVER["PHP_AUTH_USER"];
		global $data;

		//Check for errors in request
		if(empty($data["id"]))
			error(400, "No ID");

		sql_query($this->mysqli, "UPDATE messages SET text='', changed=1 WHERE id=? AND `from`=?", array($data["id"], $user))->close();
	}
}