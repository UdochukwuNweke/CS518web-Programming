<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('services.php');

//unset( $_SESSION['authenticationFlag'] );
authenticateUser();
parsePost();

//http://www.cs.odu.edu/~jbrunelle/cs518/assignments/milestone1.html
// credit to: http://www.phpsnips.com/4/Simple-User-Login#.VpkiUlMrKRt
function authenticateUser()
{
	if( isset($_SESSION['authenticationFlag']) === false )
	{
		$userDetails = login( $_POST['email'], $_POST['password'] );

		if( 
			isset($_POST["email"]) === false || 
			isset($_POST["password"]) === false ||
			count( $userDetails ) === 0
		  )
		{
			header('Location: index.php');
			exit;
		}
		else
		{
			$_SESSION['authenticationFlag'] = $userDetails;
		}
	}
}

function parsePost()
{
	if( isset($_POST) === false )
	{
		return;
	}

	//var_dump( $_POST );

	$user_id = $_SESSION['authenticationFlag']['user_id'];
	if( isset($_POST['post']) )
	{
		//post at a channel
		$fname = $_SESSION['authenticationFlag']['fname'];
		$lname = $_SESSION['authenticationFlag']['lname'];
		$channel_id = $_POST['channel_id'];

		$parent_id = -1;#reply not implemented

		$content = trim($_POST['post']);
		if( strlen($content) !== 0 )
		{
			post($user_id, $fname, $lname, $channel_id, $parent_id, $content);
		}
	}
	elseif( isset($_POST['delete']) ) 
	{
		//delete
		$post_id = $_POST['post_id'];
		deletePost($post_id, $user_id);
	}
}

function getCurChannel()
{
	if( isset($_POST['channel_id']) )
	{
		return $_POST['channel_id'];
	}
	else
	{
		return 1;
	}
}

?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="leftmenu">
Channels:
<hr>


<?php
	$channels = genericGetAll('Channel', '');

	if( count($channels) !== 0 )
	{
		//check a default channel
		$defaultCheckedID = 1;
		if( isset($_POST['channel_id']) )
		{
			$defaultCheckedID = $_POST['channel_id'];
		}

		echo '<form action="#" method="post">';
		
		for($i = 0; $i < count($channels); $i++)
		{
			$checkFlag = '';
			if( $channels[$i]['channel_id'] == $defaultCheckedID )
			{
				//restore last check option due to page refresh
				$checkFlag = 'checked';
			}

			echo '<input ' . $checkFlag . ' type="radio" name="channel_id" value="' . $channels[$i]['channel_id'] . '">#' . $channels[$i]['name'] . '<br>';
		}
		
		echo '<br><input placeholder="Enter Message" type="text" name="post"><br><br>';
		echo '<input type="submit" value="Submit/View Channel">';

		echo '</form>';
	}
?>


Direct Messages:
<hr>
</div>

<div class="main">

	<?php
		if( isset($_SESSION['authenticationFlag']) )
		{
			if( count($_SESSION['authenticationFlag']) !== 0 )
			{
				echo '<h1>Welcome ' . $_SESSION['authenticationFlag']['fname'] . ' ' . $_SESSION['authenticationFlag']['lname'] . '!</h1>';
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
	

	<div id='infoArea'>
	Posts:
	<?php 
		getMessages( getCurChannel(), $_SESSION['authenticationFlag']['user_id'] );
	?>
	</div>
</div>


</body>
</html>