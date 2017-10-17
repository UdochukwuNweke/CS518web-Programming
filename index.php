<?php
	include('services.php');
?>


<html>

<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

	<h1> Welcome to Slack </h1>


	<!--
	See for form validation:
	https://www.w3schools.com/PhP/showphp.asp?filename=demo_form_validation_complete
	-->

	<h3> Login </h3>
	<form action="main.php" method="post">
		E-mail: <input type="text" name="email"><br>
		Password: <input type="password" name="password"><br>
		<input type="submit">
	</form>

	


	<div id='infoArea'>
		<?php
			echo '<h3>General</h3>';
			getMessages(1, 0, 3);
			echo '<h3>Random</h3>';
			getMessages(2, 0, 3);
		 ?>
	</div>

</body>
</html>