<?php
	include('services.php');
	session_start();
?>


<html>

<head>
	<script src="common.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">

</head>

<body>

	
	<div style="text-align:center; font-size: 40px; color: #3B0029;">
		<strong>Type in security code you received from your email to proceed</strong>
	</div>

	<hr class="style13">
	
	<div style="text-align:center; font-size: 40px; color: #3B0029;">
		<form class="pure-form" action="main.php?channel=general" method="post">
			<fieldset>
				<p>Code is case sensitive</p>
			</fieldset>

		 	<fieldset>
				
				<?php
					//this statement MUST be place before security code input incase session has stale security code, to allow newest state to be set by submit
					foreach($_SESSION['curPost'] as $key => $value) 
					{
						echo "<input type='hidden' name='$key' value='$value'>";
					}
				?>
				<input type="text" placeholder="security code" name="security_code">

				<button type="submit" class="pure-button pure-button-primary">Submit</button>
			</fieldset>
		   
		</form>
	</div>

</body>
</html>