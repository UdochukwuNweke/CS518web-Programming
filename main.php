<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('services.php');


validatePost();
authenticateUser();
parsePost();

//http://www.cs.odu.edu/~jbrunelle/cs518/assignments/milestone1.html
// credit to: http://www.phpsnips.com/4/Simple-User-Login#.VpkiUlMrKRt
function validatePost()
{
	if( $_SERVER['REQUEST_METHOD'] == 'POST' ) 
	{
		foreach ($_POST as $key => $value) 
		{
			if( is_numeric($value) == false )
			{
				$_POST[$key] = sanitizeInput( $value );
			}
		}
	}
}

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
			$newLocation = 'index.php';

			if( isset($_GET['channel']) )
			{
				$newLocation = $newLocation . '?channel=' . $_GET['channel'];
				if( isset($_GET['post']) )
				{
					$newLocation = $newLocation . '&post=' . $_GET['post'];
				}
			}
			
			
			header('Location: ' . $newLocation);
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
	$fname = $_SESSION['authenticationFlag']['fname'];
	$lname = $_SESSION['authenticationFlag']['lname'];
	
	if( isset($_POST['delete']) ) 
	{
		//delete
		$post_id = $_POST['post_id'];
		deletePost($post_id, $user_id);
	}
	else if( isset($_POST['post']) )
	{
		$content = trim($_POST['post']);
		if( strlen($content) !== 0 )
		{
			//post at a channel
			$channel_id = $_POST['channel_id'];
			$parent_id = -1;

			if( isset($_POST['post_id']) )
			{
				//this is a reply
				$parent_id = $_POST['post_id'];
			}
			
			post($user_id, $fname, $lname, $channel_id, $parent_id, $content);
			focusOnPost( $_POST['post_id'] );
		}
		else if( isset($_POST['reaction']) )
		{
			$post_id = $_POST['post_id'];
			$user_id = $_POST['user_id'];
			$reaction_type_id = -1;
			
			//consider review (tight coupling) - start
			//the reaction type is the value of the emoji key, but the emoji key is of form "emoji key: count"
			//so this code attempts to remove the count in order to retrieve the reaction type by using the emoji key
			$reaction_type_id = explode(': ', $_POST['reaction']);
			if( count($reaction_type_id) != 0 )
			{
				$reaction_type_id = $reaction_type_id[0];
				if( isset($_POST[$reaction_type_id]) )
				{
					$reaction_type_id = $_POST[$reaction_type_id];
				}
			}
			//consider review (tight coupling) - end

			if( $reaction_type_id !== -1 )
			{
				postReaction($reaction_type_id, $post_id, $user_id, $fname, $lname);
			}

			focusOnPost($post_id);
		}
	}
}

function focusOnPost($post_id)
{
	//set URL to current post replied
	if( isset($_GET['post']) === false )
	{
		//does not have &post= in URL
		$newLocation = $_SERVER['REQUEST_URI'] . '&post=' . $post_id;
	}
	else
	{
		//has &post= in URL, but update with latest post after removing old id
		$newLocation = str_replace('&post=' . $_GET['post'], '', $_SERVER['REQUEST_URI']);
		$newLocation = $newLocation . '&post=' . $post_id;
	}

	header('Location: ' . $newLocation);
	exit;
}

function getCurChannel()
{
	//attempt to extract channel id from session channel dict using channel supplied from url param
	$channelInfo = array('channelName' => 'General', 'channelId' =>  1, 'post' => -1);

	if( isset($_GET['channel']) && isset($_SESSION['channels']) )
	{
		$urlChannel = $_GET['channel'];
		if( isset($_SESSION['channels'][$urlChannel]) )
		{
			$channelInfo['channelName'] = $urlChannel;
			$channelInfo['channelId'] = $_SESSION['channels'][$urlChannel];
		}
	}

	if( isset($_GET['post']) )
	{
		if( is_numeric($_GET['post']) )
		{
			$channelInfo['post'] = $_GET['post'];	
		}
	}
	
	/*
	if( isset($_POST['channel_id']) )
	{
		return $_POST['channel_id'];
	}*/
	
	
	return $channelInfo;
}

?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
</head>
<body style="margin: 0; height: 100%; overflow: hidden;">

<div class="leftmenu">
Channels:
<hr>


<?php
	$channels = genericGetAll('Channel', '');
	$_SESSION['channels'] = array();

	if( count($channels) !== 0 )
	{
		echo '<br>';
		for($i = 0; $i < count($channels); $i++)
		{
			//create key value pair with channel name as key and channel id as value
			$_SESSION['channels'][$channels[$i]['name']] = $channels[$i]['channel_id'];
			echo '<a style="color: inherit;" href="?channel=' . $channels[$i]['name'] . '"> #' . $channels[$i]['name'] . '</a> <br>';
		}
		echo '<br>';
	}
?>


Direct Messages:
<hr>
</div>

<div class="pure-g">
	
	<div class="pure-u-1-5">
    	<a href="main.php?channel=General">Home</a>
    </div>

    <div class="pure-u-1-5">
    	<a href="logout.php">Logout</a>
    </div>
    
    <div class="pure-u-1-5">
    	<a href="">New Channel (NO OP)</a>
    </div>
    <div class="pure-u-1-5">
    	<a href="">My Profile (NO OP)</a>
    </div>
    <div class="pure-u-1-5">
    	<a href="">Invite (NO OP)</a>
    </div>
</div>

<br>

<div class="main">	
	<?php
		echo '<div id="infoArea">';
			
			$msgExtraParams = array();
			$channelInfo = getCurChannel();
			$reactionTypes = genericGetAll('Reaction_Type');
			$msgExtraParams['reactionTypes'] = $reactionTypes;


			$threadFlag = '';
			if( $channelInfo['post'] != -1 )
			{
				$threadFlag = ' (Replies to post: ' . $channelInfo['post'] . ')';
			}

			echo '<h3>' . $_SESSION['authenticationFlag']['fname'] . ' ' . $_SESSION['authenticationFlag']['lname'] . ' @ ' . $channelInfo['channelName'] . $threadFlag . '</h3>';

			if( $channelInfo['post'] != -1 )
			{
				//extract parent message which was clicked
				$msgExtraParams['max'] = 1;
				getSingleMessage(
					$channelInfo['post'], 
					$channelInfo['channelId'], 
					$_SESSION['authenticationFlag']['user_id'], 
					$msgExtraParams
				);
			}

			echo '<br><br>';
			echo '<hr class="style13">';

			$msgExtraParams['max'] = 0;
			getMessages( 
				$channelInfo['channelId'], 
				$_SESSION['authenticationFlag']['user_id'],
				$channelInfo['post'],
				$msgExtraParams
			);
		echo '</div>';

		echo '<form class="pure-form" action="main.php?channel=' . $channelInfo['channelName'] . '" method="post">';
			echo '<fieldset>';
					echo '<input type="hidden" name="channel_id" value="' . $channelInfo['channelId'] . '">';
					echo '<input type="text" size="90%" name="post" placeholder="Enter message here">';
					echo '<input type="submit" class="pure-button pure-button-primary">';
			echo '</fieldset>';
		echo '</form>'
	?>
	
</div>

<!--
<div class="nextMain">
	<h1>Next</h1>
</div>
-->

<script type="text/javascript">
	
	function main()
	{	
	}

	function replyCounterClick(post_id)
	{
		post_id = 'post=' + post_id;
		var uriParams = processURL();

		console.log('\nreplyCounterClick(), params:', uriParams);
		if( uriParams.post == undefined )
		{
			window.location.href += '&' + post_id;
		}
		else
		{
			window.location.href = window.location.href.replace('post=' + uriParams.post, post_id);
		}
	}

	/*https://stackoverflow.com/a/979996*/
	function processURL()
	{
		var params = {};

		var aTag = document.createElement('a');
		aTag.href = window.location.href;

	    if ( aTag.search.length != 0 ) 
	    {
		    var parts = aTag.search.substring(1).split('&');

		    for (var i = 0; i < parts.length; i++) 
		    {
		        var nv = parts[i].split('=');
		        if (!nv[0]) continue;
		        params[nv[0]] = nv[1] || true;
		    }
		}

		return params;
	}

</script>
</body>
</html>