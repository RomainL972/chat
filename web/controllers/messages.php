<?php

class MessagesController
{
	private $mysqli/*, $context, $socket*/;

	function __construct()
	{
		$this->mysqli = sql_connect();
		// $this->$context = new ZMQContext();
  //   	$this->$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
  //   	$this->$socket->connect("tcp://localhost:5555");
	}

	function GET(){
		global $data;
		$user = $_SERVER["PHP_AUTH_USER"];
		if(empty($data["other"]))
			error(200, "No other person specified");

		// $params = array($user, $data["other"], $data["other"], $user);
		// $query = "SELECT id, `from`, `text`, `time` FROM messages WHERE ((`from`=? AND `to`=?) OR (`from`=? AND `to`=?))";

		// if(!empty($data["before"])) {
		// 	$query .= " AND id < ?";
		// 	$params[4] = $data["before"];
		// }

		// $query .= " ORDER by id DESC LIMIT 20";

		// $result = sql_query($this->mysqli, $query, $params);

		// $data = $result->fetch_all(MYSQLI_ASSOC);
		// $json = array("new" => $data);
		// $result->close();

		$query = "SELECT id, `from`, `time`, `change`, `text` FROM messages WHERE ((`from`=? AND `to`=?) OR (`from`=? AND `to`=?))";
		$params = array($user, $data["other"], $data["other"], $user);
		if(!empty($data["lastCheck"])) {
			$query .= " AND last_modified > ?";
			$params[] = date("Y-m-d H:i:s",$data["lastCheck"]);
		}
		// die(print_r($params,1));

		$result = sql_query($this->mysqli, $query, $params);
		$data = $result->fetch_all(MYSQLI_ASSOC);
		$json = $data;
		$result->close();
		

		echo json_encode($json);
	}

	function POST(){
		global $data;
		$from = $_SERVER["PHP_AUTH_USER"];
		if(empty($data["text"]))
			error(200, "No message");
		if(strlen($data["text"]) > 500)
			error(200, "Message too long");
		if(empty($data["to"]))
			error(200, "No recipient");

		$result = sql_query($this->mysqli, "INSERT INTO messages(`from`, `to`, `text`) VALUES (?, ?, ?)", array($from, $data["to"], $data["text"]));
		// $socket->send(array(""))
		die(print_r($result));
	}

	function PATCH(){
		$from = $_SERVER["PHP_AUTH_USER"];
		global $data;
		if(empty($data["id"]))
			error(200, "No ID");
		if(empty($data["text"]))
			error(200, "No text");

		$result = sql_query($this->mysqli, "UPDATE messages SET `text`=?, `change`=1 WHERE id=? AND `from`=?", array($data["text"], $data["id"], $from));
	}

	function DELETE(){
		$from = $_SERVER["PHP_AUTH_USER"];
		global $data;
		if(empty($data["id"]))
			error(200, "No ID");

		$result = sql_query($this->mysqli, "UPDATE messages SET `text`='', `change`=2 WHERE id=? AND `from`=?", array($data["id"], $from));
	}
}