<?php
	include('services.php');
	session_start();


	if( isset($_SESSION['authenticationFlag']) === true )
	{
		header('Location: main.php?channel=general');
		exit;
	}

?>


<html>

<head>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
</head>

<body>

	<div style="text-align:center; font-size: 40px; color: #3B0029;">
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

				<?php
					if( isset($_SESSION['index.php.msg']) )
					{
						echo '<strong><p style="color: red">' . $_SESSION['index.php.msg'] . '</p></strong>';
					}
				?>
				
				<form id='loginForm' class="pure-form pure-form-aligned" action="" method="post">
				    <fieldset>
				        <div class="pure-control-group">
				            <input name="email" type="email" placeholder="Email Address">
				        </div>

				        <div class="pure-control-group">
				            <input id="loginPass" name="password" type="password" placeholder="Password">
				        </div>

				        <div class="pure-control-group">
				        	<input onchange="togglePass()" value="git_login" type="checkbox" name="git_login"> Git Login
				            <button type="submit" class="pure-button pure-button-primary">Login</button>
				        </div>

				    </fieldset>
				</form>


			</div>
	    </td>

	    <td align="center">
	    	<div style="padding: 10px 0px 0px 10px;width: 80%; height: 20%;">
				<h3> Register </h3>
				
				<?php 
					if( isset($_SESSION['register.php.msg']) )
					{
						if( $_SESSION['register.php.msg'] == 'go' )
						{
							unset( $_SESSION['register.php.msg'] );
							echo '<strong><p style="color: green">Registration successful, please login.</p></strong>';
						}
						else
						{
							echo '<strong><p style="color: red">' . $_SESSION['register.php.msg'] . '</p></strong>';
						}
					}
				?>

				<form class="pure-form" action="register.php" method="post">

				 	<fieldset>
            			<input type="text" placeholder="First name" name="First-name">
            			<input type="text" placeholder="Last name" name="Last-name">
					</fieldset>	

				    <fieldset>
				        <input type="password" placeholder="Password" name="Password">
				        <input type="password" placeholder="Re-type password" name="Re-Password">
				    </fieldset>

				    <fieldset>
				    	<div class="g-recaptcha" data-sitekey="6LcOXTwUAAAAAGgmDXzUSZMCzjrkTy25gfaah-_e"></div>
				    	<input type="email" placeholder="Email" name="Email">
				    	<button type="submit" class="pure-button pure-button-primary">Register</button>
					</fieldset>
				   
				</form>

			</div>
	    </td> 
	    
	  </tr>
	</table>


	<div id='infoArea' style="width: 50%; height: 50%;">
		<?php
			$msgExtraParams = array(
				'max' => 3, 
				'role_type' => 
				'DEFAULT', 
				'state' => 'ACTIVE',
				'page_size' => 3,
				'page' => 1
			);
			
			echo '<h3># general</h3>';
			getMessages(1, 0, -1, $msgExtraParams);
			echo '<h3># random</h3>';
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
				aTag.search = '?channel=general';
			}

			document.getElementById('loginForm').setAttribute('action', 'main.php' + aTag.search);
		}

		function togglePass()
		{
			if( document.getElementById('loginPass').disabled == true )
			{
				document.getElementById('loginPass').disabled = false;
			}
			else
			{
				document.getElementById('loginPass').disabled = true;
			}
		}

	</script>

</body>
</html>