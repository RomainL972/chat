<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Chat Louis-Romain</title>
	<script src="jquery-3.3.1.min.js"></script>
	<script src="utils.js"></script>
	<script src="script.js"></script>
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
		<form action="#" id="text_box" class="child_box">
			<input id="input_box">
			<input type="submit" id="button_box" onclick="send_message()" value="⥣">
		</form>
	</section>
</body>
</html>