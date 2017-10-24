<?php
	include('services.php');
	session_start();

	
	if( isset($_SESSION['authenticationFlag']) === true )
	{
		header('Location: main.php?channel=General');
		exit;
	}
?>


<html>

<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
</head>

<body>

	<div style="width: 200px; height: 100px; margin: 20 auto; text-align:center; font-size: 40px; color: #3B0029;">
		<strong>ODU CS Slack</strong>
	</div>

	<hr class="style13">
	<!--
	See for form validation:
	https://www.w3schools.com/PhP/showphp.asp?filename=demo_form_validation_complete
	-->

	<table style="width: 60%; cellpadding: 10px; margin: 0 auto;">
	  <tr>

	    <td align="center">
	    	<div style="padding: 10px 0px 0px 10px; width:80%; height: 20%;">
				<h3> Login </h3>
				
				<form id='loginForm' class="pure-form pure-form-aligned" action="" method="post">
				    <fieldset>
				        <div class="pure-control-group">
				            <input name="email" type="email" placeholder="Email Address">
				        </div>

				        <div class="pure-control-group">
				            <input name="password" type="password" placeholder="Password">
				        </div>

				        <div class="pure-control-group">
				            <button type="submit" class="pure-button pure-button-primary">Login</button>
				        </div>
				    </fieldset>
				</form>

			</div>
	    </td>

	    <td align="center">
	    	<div style="padding: 10px 0px 0px 10px;width: 80%; height: 20%;">
				<h3> Register </h3>
				<form class="pure-form" action="main.php" method="post">

				 	<fieldset>
            			<input type="text" placeholder="First name">
            			<input type="text" placeholder="Last name">
					</fieldset>	

				    <fieldset>
				        
				        <input type="password" placeholder="Password">
				        <input type="password" placeholder="Re-type password">
				    </fieldset>

				    <fieldset>
				    	<input type="email" placeholder="Email">
				    	<button type="submit" class="pure-button pure-button-primary">Register</button>
					</fieldset>
				   
				</form>

			</div>
	    </td> 
	    
	  </tr>
	</table>


	<div id='infoArea' style="width: 50%; height: 50%;">
		<?php
			$msgExtraParams = array('max' => 3);
			
			echo '<h3>General</h3>';
			getMessages(1, 0, -1, $msgExtraParams);
			echo '<h3>Random</h3>';
			getMessages(2, 0, -1, $msgExtraParams);
		 ?>
	</div>

	<script type="text/javascript">
		main();

		function main() 
		{
			var aTag = document.createElement('a');
			aTag.href = window.location.href;

			if( aTag.search.length === 0 )
			{
				aTag.search = '?channel=General';
			}

			document.getElementById('loginForm').setAttribute('action', 'main.php' + aTag.search);
		}
	</script>

</body>
</html>