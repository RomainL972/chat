<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Chat Louis-Romain</title>
	<script src="script.js"></script>
</head>
<body>
	<!-- <h1 id="title_conv">
		<span class="status_connect disconnect"></span>
		Romain
	</h1> -->
	<section id="global_box">
		<section id="msg_box" class="child_box">
			<ul id="scroll_box">
				
			</ul>
		</section>
		<section id="text_box" class="child_box">
			<input id="input_box" type="" name="">
			<button id="button_box" >⥣</button>
		</section>
	</section>
	<script>
		var elDiv =document.getElementById("scroll_box");
		elDiv.scrollTop = elDiv.scrollHeight-elDiv.offsetHeight;
	</script>
</body>
</html>