<?php
function sql_connect()
{
	$mysqli = new mysqli(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASS"), getenv("DATABASE"));
	if ($mysqli->connect_error) {
		die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	return $mysqli;
}

function sql_query($mysqli, $query, $params=array())
{
	try {
		if(empty($params)) {
			return $mysqli->query($query);
		}

		$types = "";
		foreach ($params as $value) {
			if(is_string($value))
				$types .= "s";
			elseif(is_double($value))
				$types .= "d";
			elseif(is_int($value))
				$types .= "i";
		}

		array_unshift($params, $types);

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param(...$params);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$stmt->close();

		if($mysqli->errno)
			error(500, $mysqli->error);
		return $result;
	}
	catch(Exception $e) {
		echo $e;
	}
}