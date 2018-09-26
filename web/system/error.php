<?php
function error($code, $message)
{
	answer(json_encode($message), $code);
}

function answer($answer="", $code=200) {
	global $content_type;
	header("HTTP/1.0 $code");
	header("Content-type: $content_type; charset=UTF-8");

	if(!empty($answer))
		echo $answer;
	die();
}