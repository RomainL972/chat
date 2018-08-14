<?php
if (!isset($_POST["mode"])) {
	header("HTTP/1.0 200 OK");
}
$messages = [
	[
		"user" => "louis",
		"message" => "Salut Romain",
		"time" => 1534232700
	],
	[
		"user" => "romain",
		"message" => "Salut Louis, ça va?",
		"time" => 1534232760
	],
	[
		"user" => "louis",
		"message" => "Oui",
		"time" => 1534232820
	],
	[
		"user" => "louis",
		"message" => "Je suis content",
		"time" => 1534232820
	]
];

echo json_encode($messages);