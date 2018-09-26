<?php
/**
 * 
 */
class DefaultController
{
	
	function GET()
	{
		global $content_type;
		$content_type = "text/html";
		return <<<_END
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="public/css/style.css">
	<title>Chat Louis-Romain</title>
	<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
  	<script src="public/js/socket.io.js"></script>
	<script src="public/js/Hyphenator_Loader.js"></script>
	<script src="public/js/linkify.min.js"></script>
	<script src="public/js/linkify-jquery.min.js"></script>
	<script src="public/js/utils.js"></script>
	<script src="public/js/parse.js"></script>
	<script src="public/js/script.js"></script>
</head>
<body>
	<!-- <h1 id="title_conv">
		<span class="status_connect disconnect"></span>
		Romain
	</h1> -->
	<section id="global_box">
		<section id="msg_box" class="child_box">
			<ul id="scroll_box"></ul>
		</section>
		<form id="text_box" class="child_box" method="post">
			<input id="input_box">
			<input type="submit" id="button_box" value="⥣">
		</form>
	</section>
</body>
</html>
_END;
	}
}