<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('DB.php');


authenticateUser();


// credit to: http://www.phpsnips.com/4/Simple-User-Login#.VpkiUlMrKRt
function authenticateUser()
{
	if( isset($_SESSION['authenticationFlag']) === false )
	{
		$fnameLname = login( $_POST['email'], $_POST['password'] );

		if( 
			isset($_POST["email"]) === false || 
			isset($_POST["password"]) === false ||
			count( $fnameLname ) === 0
		  )
		{
			header('Location: index.php');
			exit;
		}
		else
		{
			$_SESSION['authenticationFlag'] = $fnameLname;
		}
	}
}

?>

<html>
<head>
	<style type="text/css">
		
		body 
		{
			  margin:0;
			  font-family: Perpetua, Baskerville, "Big Caslon", "Palatino Linotype", Palatino, "URW Palladio L", "Nimbus Roman No9 L", serif; font-size: 16px;
			  /*height:100%;*/
			  /*overflow:hidden;*/
		      background-color: #EEE;
		}

		.leftmenu
		{ 
			padding: 10px 0px 0px 10px;

			background: #373E40;
			color:#fff;
			
			width: 15%; 
			height: 100%;

			overflow: hidden;
			float: left;
        }

        .main
        {
        	width: 50%;
        	height: 100%;
        	padding: 0px 0px 0px 20px;
        	float: left;
        }

        #infoArea 
        {
		    background-color: white;
		    border-style: solid;
		    border-width: 1px;
		    border-color: #999999;
		    margin:0 auto;

		    padding: 10px 10px 10px 10px;
		}

	</style>
</head>
<body>

<div class="leftmenu">
Channels:
<hr>





Direct Messages:
<hr>
</div>

<div class="main">
	<?php
		if( isset($_SESSION['authenticationFlag']) )
		{
			if( count($_SESSION['authenticationFlag']) !== 0 )
			{
				echo '<h1>Welcome ' . $_SESSION['authenticationFlag']['fname'] . '!</h1>';
			}
			else
			{
				echo '<h1>Welcome!</h1>';
			}
		}
		else
		{
			echo '<h1>Welcome!</h1>';
		}
	?>
</div>


</body>
</html>