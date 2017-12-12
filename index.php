<?php
	include('services.php');
	session_start();


	if( isset($_SESSION['authenticationFlag']) === true )
	{
		header('Location: main.php?channel=general');
		exit;
	}

	gitLogin();

	//credit: https://gist.github.com/asika32764/b204ff4799d577fd4eef
	function gitLogin()
	{
		define('OAUTH2_CLIENT_ID', '46645dec093d50de6f5b');
		define('OAUTH2_CLIENT_SECRET', '1c397789d0056cad8172505a0d5dfdb7faef10be');

		$authorizeURL = 'https://github.com/login/oauth/authorize';
		$tokenURL     = 'https://github.com/login/oauth/access_token';
		$apiURLBase   = 'https://api.github.com/';


		// Start the login process by sending the user to Github's authorization page
		if(get('action') == 'login') 
		{
		    // Generate a random hash and store in the session for security
		    $_SESSION['state'] = hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']);
		    unset($_SESSION['access_token']);
		    $params = array(
		        'client_id' => OAUTH2_CLIENT_ID,
		        'redirect_uri' => 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'],
		        'scope' => 'user',
		        'state' => $_SESSION['state']
		    );
		    // Redirect the user to Github's authorization page
		    header('Location: ' . $authorizeURL . '?' . http_build_query($params));
		    die();
		}

		// When Github redirects the user back here, there will be a "code" and "state" parameter in the query string
		if (get('code')) 
		{
		    // Verify the state matches our stored state
		    if (!get('state') || $_SESSION['state'] != get('state')) {
		        header('Location: ' . $_SERVER['PHP_SELF']);
		        die();
		    }
		    // Exchange the auth code for a token
		    $token                    = apiRequest($tokenURL, array(
		        'client_id' => OAUTH2_CLIENT_ID,
		        'client_secret' => OAUTH2_CLIENT_SECRET,
		        'redirect_uri' => 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'],
		        'state' => $_SESSION['state'],
		        'code' => get('code')
		    ));
		    $_SESSION['access_token'] = $token->access_token;
		    header('Location: ' . $_SERVER['PHP_SELF']);
		}

		if (session('access_token')) 
		{
		    $_SESSION['authenticationFlag'] = array('fname' => 'admin', 'lname' => '', 'email' => 'admin@slack.com');
		} 
	}


	function apiRequest($url, $post = FALSE, $headers = array())
	{
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    if ($post)
	        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
	    $headers[] = 'Accept: application/json';
	    if (session('access_token'))
	        $headers[] = 'Authorization: Bearer ' . session('access_token');
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    $response = curl_exec($ch);
	    return json_decode($response);
	}

	function get($key, $default = NULL)
	{
	    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
	}

	function session($key, $default = NULL)
	{
	    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
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