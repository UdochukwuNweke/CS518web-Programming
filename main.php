<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('services.php');

validatePost();
authenticateUser();
parsePost();

// http://www.cs.odu.edu/~jbrunelle/cs518/assignments/milestone1.html
// credit to: http://www.phpsnips.com/4/Simple-User-Login#.VpkiUlMrKRt

function authenticateUser()
{
	if( isset($_SESSION['authenticationFlag']) === false )
	{
		$userDetails = array();
		$shouldRedirectFlag = false;

		if( isset($_POST["email"]) === false || isset($_POST["password"]) === false )
		{
			$_SESSION['index.php.msg'] = 'Please login';
			$shouldRedirectFlag = true;
		}
		else
		{
			if( strlen(trim($_POST["email"])) === 0 || strlen(trim($_POST["password"])) === 0 )
			{
				$_SESSION['index.php.msg'] = 'Please login';
				$shouldRedirectFlag = true;
			}
			else
			{
				$userDetails = login( $_POST['email'], $_POST['password'] );
				if( count($userDetails) === 0 )
				{
					$_SESSION['index.php.msg'] = 'Incorrect email or password';
					$shouldRedirectFlag = true;
				}
			}
			
		}

		if( $shouldRedirectFlag === true )
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
			unset($_SESSION['index.php.msg']);
		}
	}
}

function parsePost()
{
	if( isset($_POST) === false )
	{
		return;
	}

	var_dump( $_POST );
	if( $_FILES )
	{
		var_dump($_FILES);
		$uploadfile = './postImgs/' . $_SESSION['authenticationFlag']['user_id'] . '.temp.jpg';
		uploadImage($_FILES, 'image', $uploadfile);
	}
	return;
	

	if( isset($_POST['channel_state']) )
	{
		if( $_POST['channel_state'] != 'ACTIVE' )
		{
			return;
		}
	}

	$user_id = $_SESSION['authenticationFlag']['user_id'];
	$fname = $_SESSION['authenticationFlag']['fname'];
	$lname = $_SESSION['authenticationFlag']['lname'];
	
	if( isset($_POST['delete']) ) 
	{
		//delete
		if( $_SESSION['authenticationFlag']['role_type'] == 'ADMIN' )
		{
			$post_id = $_POST['post_id'];	
			deletePost($post_id, $_POST['post_user_id']);
		}
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

			if( isset($_POST['pre_tag']) )
			{
				$content = '<pre>' . $content . '</pre>';
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
			foreach ($_POST as $key => $value) 
			{
				if( strpos($key, 'reaction-') !== false )
				{
					$reaction_type_id = explode('reaction-', $key);
					$reaction_type_id = $reaction_type_id[1];
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
	$channelInfo = array('channelName' => 'general', 'channelId' =>  1, 'post' => -1, 'state' => 'ACTIVE');

	if( isset($_GET['channel']) && isset($_SESSION['channels']) )
	{
		$channels = array_merge( 
			$_SESSION['channels']['pub-memb'], 
			$_SESSION['channels']['priv-memb'],
			$_SESSION['channels']['pub-non-memb']
		);
		
		$channel = genericGetFromArr($channels, $_GET['channel'], 'name');

		if( count($channel) != 0 )
		{
			$channelInfo['channelName'] = $channel['name'];
			$channelInfo['channelId'] = $channel['channel_id'];
			$channelInfo['state'] = $channel['state'];
		}
		else
		{
			//bad channel address, set default
			//header('Location: main.php?channel=general');
			header('Location: main.php');
		}
	}

	if( isset($_GET['post']) )
	{
		if( is_numeric($_GET['post']) )
		{
			$channelInfo['post'] = $_GET['post'];	
		}
	}
	
	return $channelInfo;
}

function genericGetFromArr($allChannels, $key, $type='channel_id')
{
	
	for($i = 0; $i < count($allChannels); $i++)
	{
		if( isset($allChannels[$i][$type]) )
		{
			if( $allChannels[$i][$type] == $key )
				return $allChannels[$i];
		}
	}

	return array();
}

function getChannelPartitions($allChannels, $memberChannels)
{
	/*
		Create channel partitions:
		1. public channels user is a member of -- display in channel panel/profiles
		2. private channels user is a member of -- display in channel panel with lock sign logo
		2. public channels user is NOT a member -- display in browse
	*/

	$channelPartition = array();
	$channelPartition['pub-memb'] = array();
	$channelPartition['priv-memb'] = array();
	$channelPartition['pub-non-memb'] = array();

	if( count($allChannels) == 0 )
		return $channelPartition;
	
	$skipChannels = array();
	for($i = 0; $i < count($memberChannels); $i++)
	{
		$channel = genericGetFromArr($allChannels, $memberChannels[$i]['channel_id'], 'channel_id');
		if( count($channel) != 0 )
		{
			array_push($skipChannels, $channel['channel_id']);

			if( $channel['type'] == 'PUBLIC' )
			{
				array_push($channelPartition['pub-memb'], $channel);
			}
			else
			{
				array_push($channelPartition['priv-memb'], $channel);
			}
		}
	}

	for($i = 0; $i < count($allChannels); $i++)
	{
		if( $allChannels[$i]['type'] == 'PUBLIC' )
		{
			if( in_array($allChannels[$i]['channel_id'], $skipChannels) == false )
			{
				array_push($channelPartition['pub-non-memb'], $allChannels[$i]);
			}
		}
	}

	return $channelPartition;
}

function printChannelMsg($channelInfo, $msgExtraParams)
{
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
}

?>

<html>
<head>
	<script src="common.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
</head>
<body style="margin: 0; height: 100%; overflow: hidden;">

<div id="menuDiv" style="background-color: gray; padding: 10px 0px 10px 0px; border-radius: 0px;">
	<table style="width: 100%;" align="center">
		<tr>

			<td width="20%" style="text-align:center;">
				<a style="color: inherit; text-decoration: none;" href="main.php?channel=general">Home</a>
			</td>

			<td width="20%" style="text-align:center;">
				<a style="color: inherit; text-decoration: none;" href="logout.php">Logout</a>
			</td>

			<td width="20%" style="text-align:center;">
				<?php
					echo '<a style="color: inherit; text-decoration: none;" href="./profile.php?user=' . $_SESSION['authenticationFlag']['user_id'] . '" >My Profile</a>';
				?>
			</td>

			<td width="20%" style="text-align:center;">
				<a style="color: inherit; text-decoration: none;" href="help.php">Help</a>
			</td>

		</tr>
	</table>
</div>

<div class="leftmenu">
Channels:
<hr>


<?php
	$channels = genericGetAll('Channel');
	$memberChannels = genericGetAll('Channel_Membership', 'WHERE user_id=' . $_SESSION['authenticationFlag']['user_id']);
	$channelPartition = getChannelPartitions($channels, $memberChannels);

	$channels = array_merge( $channelPartition['pub-memb'], $channelPartition['priv-memb'] );
	echo '<ul style="list-style-type:none;">';//credit: https://stackoverflow.com/a/9709788
		echo '<li><a style="color: inherit; text-decoration: none;" href="./new.php"> + New Channel/Invite</a></li>';
		for( $i = 0; $i<count($channels); $i++ )
		{
			echo '<li>' . getHTMLForChannel( $channels[$i] ) . '</li>';
		}
	
		if( count($channelPartition['pub-non-memb']) != 0 )
		{
			echo '<br><li>Post to Subscribe to these channels:</li>';
			echo '<ul style="list-style-type:none;">';
				for( $i = 0; $i<count($channelPartition['pub-non-memb']); $i++ )
				{
					echo '<li>' . getHTMLForChannel( $channelPartition['pub-non-memb'][$i] ) . '</li>';
				}
			echo '</ul>';
		}
	echo '</ul>';

	$_SESSION['channels'] = array();
	$_SESSION['channels']['pub-memb'] = $channelPartition['pub-memb'];
	$_SESSION['channels']['priv-memb'] = $channelPartition['priv-memb'];
	$_SESSION['channels']['pub-non-memb'] = $channelPartition['pub-non-memb'];
	

	//patch:
	/*
		//Delete post testing all aspects of channel visibility
		if( count($channels) !== 0 )
		{
			echo '<br>';
			for($i = 0; $i < count($channels); $i++)
			{
				//create key value pair with channel name as key and channel id as value
				$_SESSION['channels'][$channels[$i]['name']] = $channels[$i]['channel_id'];
				echo '<a style="color: inherit; text-decoration: none;" href="?channel=' . $channels[$i]['name'] . '"> # ' . $channels[$i]['name'] . '</a> <br>';
			}
			echo '<a style="color: inherit; text-decoration: none;" href="./new.php"> + New Channel</a> <br>';
			echo '<br>';
		}
	*/
?>


Direct Messages:
<hr>

<?php
	$users = genericGetAll('User', 'WHERE user_id!=' . $_SESSION['authenticationFlag']['user_id'], 'user_id, fname, lname');
	$_SESSION['users'] = $users;

	echo '<ul style="list-style-type:none;">';//credit: https://stackoverflow.com/a/9709788
	for($i = 0; $i<count($users); $i++)
	{
		echo '<li>' . getHTMLForUser( $users[$i] ) . '</li>';
	}
	echo '</ul>';
?>

</div>


<br>
<div class="main">	
	
	<?php
		echo '<div id="infoArea">';
			
			$channelInfo = getCurChannel();
			$reactionTypes = genericGetAll('Reaction_Type');

			$msgExtraParams = array();
			$msgExtraParams['reactionTypes'] = $reactionTypes;
			$msgExtraParams['role_type'] = $_SESSION['authenticationFlag']['role_type'];
			$msgExtraParams['state'] = $channelInfo['state'];

			if( isset($_GET['channel']) )
			{
				printChannelMsg($channelInfo, $msgExtraParams);
			}
			else if( isset($_GET['user']) )
			{
				//direct msg
				$user = genericGetFromArr($users, $_GET['user'], $type='user_id');

				echo '<h3>' 
				. $_SESSION['authenticationFlag']['fname'] 
				. ' ' 
				. $_SESSION['authenticationFlag']['lname'] 
				. ' and ' 
				. $user['fname'] . ' ' . $user['lname']
				. ' (direct messages)'
				. '</h3>';
				
				echo '<br><br>';
				echo '<hr class="style13">';
			}
		
		echo '</div>';

		
		if( $channelInfo['state'] == 'ACTIVE' )
		{
			echo '<form class="pure-form" enctype="multipart/form-data" action="main.php?channel=' . $channelInfo['channelName'] . '" method="post">';
			echo '<fieldset>';
			echo '<input type="hidden" name="channel_id" value="' . $channelInfo['channelId'] . '">';
			echo '<textarea type="text" size="90%" name="post" placeholder="Enter message here" style="margin-top: 0px; margin-bottom: 0px; width: 380px; height: 35px;"></textarea>';
			echo '<input type="submit" class="pure-button pure-button-primary">';
			echo '<br><input type="checkbox" name="pre_tag"> Pre-formated';
			
			echo '<input type="hidden" name="MAX_FILE_SIZE" value="1048576">';
			echo '<input type="file" id="upload-photo" name="mkfile" style="opacity: 0;position: absolute;z-index: -1;" />';
			echo '<label for="upload-photo" style="cursor: pointer;">   &#128247; Upload image (1MB)</label>';
			
			echo '</fieldset>';
			echo '</form>';
		}
		else
		{
			echo '<strong>This channel has been archived, if you need it unarchived, please contact the administrator.</strong>';
		}
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

</script>
</body>
</html>