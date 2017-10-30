<?php
	include('services.php');
	session_start();
	
	if( isset($_SESSION['authenticationFlag']) === false )
	{
		header('Location: index.php');
		exit;
	}

	processRequest();
	
	function newChannelRequest()
	{
		$msgId = 'new.php.newchannel.msg';

		//remove possible hash
		$_POST['Channel-name'] = strtolower(trim($_POST['Channel-name']));
		if( strlen($_POST['Channel-name']) > 1 )
		{
			if( $_POST['Channel-name'][0] == '#' )
			{
				$_POST['Channel-name'] = substr($_POST['Channel-name'], 1);
			}
		}

		//for public channel membership can be acquired by:
		//a. clicking join
		//b. by invitation

		//for private
		//a. by invitation

		$prevPost = $_POST;
		validatePost();

		//check if validate post removed special characters, notify user of bad input
		foreach ($_POST as $key => $value) 
		{
			if( $_POST[$key] !== $prevPost[$key] )
			{
				$_SESSION[$msgId] = 'Invalid characters for: ' . $key;
				return;
			}
		}

		//check for channel name length
		if( strlen($_POST['Channel-name']) > 21 )
		{
			$_SESSION[$msgId] = 'Channel length must be at most 21 characters not: ' . strlen($_POST['Channel-name']);
			return;
		}

		if( strlen($_POST['Channel-name']) == 0 )
		{
			$_SESSION[$msgId] = 'No channel name supplied';
			return;
		}

		//check for channel name spaces
		if( strpos($_POST['Channel-name'], ' ') != false )
		{
			$_SESSION[$msgId] = 'Channel name must not contain spaces';
			return;
		}

		//check if channel name exists
		if( count(genericGetAll('Channel', 'WHERE name="' . $_POST['Channel-name'] . '" AND type="' . $_POST['type'] . '";')) != 0 )
		{
			$_SESSION[$msgId] = 'Channel name: "' . $_POST['Channel-name'] . '" already exists';
			return;
		}

		//add channel details to db
		$success = addChannel(
			$_POST['Channel-name'], 
			$_POST['Purpose'], 
			$_POST['type'], 
			$_SESSION['authenticationFlag']['user_id']
		);

		if( $success )
		{
			$_SESSION[$msgId] = 'go';
		}
		else
		{
			$_SESSION[$msgId] = 'An error occured. Please report to the admin';
		}
	}

	function processInviteRequest()
	{
		$msgId = 'new.php.invite.msg';

		//check if membership exists
		$memb = genericGetAll('Channel_Membership', 'WHERE user_id=' . $_POST['user_id'] . ' AND channel_id=' . $_POST['channel_id']);

		if( count($memb) == 0 )
		{
			if( setChannelMembership($_POST['channel_id'], $_POST['user_id']) )
			{
				$_SESSION[$msgId] = 'go';
			}
			else
			{
				$_SESSION[$msgId] = 'An error occured. Please report to the admin';
			}
		}
		else
		{
			$_SESSION[$msgId] = 'The user is already a member of the channel.';
		}
	}

	function processRequest()
	{
		if( isset($_POST['Channel-name']) == true && isset($_POST['Purpose']) == true && isset($_POST['Channel-name']) == true )
		{
			newChannelRequest();
		}
		else if( isset($_POST['user_id']) == true &&  isset($_POST['channel_id']) == true )
		{
			processInviteRequest();
		}
	}

	function printMsg($id, $successMsg)
	{
		if( isset($_SESSION[$id]) )
		{
			if( $_SESSION[$id] == 'go' )
			{
				unset( $_SESSION[$id] );
				echo '<strong><p style="color: green">' . $successMsg . '</p></strong>';
			}
			else
			{
				echo '<strong><p style="color: red">' . $_SESSION[$id] . '</p></strong>';
			}
		}
	}
?>


<html>

<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">

</head>

<body>

	
	<div style="text-align:center; font-size: 40px; color: #3B0029;">
		<?php
			echo '<strong>Current user: ' . $_SESSION['authenticationFlag']['fname'] . ' '  . $_SESSION['authenticationFlag']['lname'] . '</strong><br><br>';
			echo '<a style="color: inherit; text-decoration: none; font-size: 40px;" href="main.php?channel=General">  <  </a>';
			echo '<strong>Create new channel</strong>';
		?>
	</div>

	<hr class="style13">
	
	<!--
	See for form validation:
	https://www.w3schools.com/PhP/showphp.asp?filename=demo_form_validation_complete
	-->

	<table style="width: 60%; cellpadding: 10px; margin: 0 auto;">
	  <tr>
	    <td align="center">
	    	<div style="padding: 10px 0px 0px 10px;width: 80%; height: 20%;">
				
				<?php
					if( isset($_POST['Channel-name']) )
					{
						$successMsg = 'Successfully created channel: "' . $_POST['Channel-name'] . '"';	
						printMsg('new.php.newchannel.msg', $successMsg);
					}
				?>

				<form class="pure-form" action="new.php" method="post">
					<fieldset>
						<p>Channel name: must be lowercase, with no spaces, and unique.</p>
						<p>Purpose: What's this channel about?</p>
					</fieldset>

				 	<fieldset>
            			<input type="text" placeholder="channelname" name="Channel-name">
            			<input type="text" placeholder="Purpose" name="Purpose">

            			<select name="type">
						  <option value="PUBLIC">Public</option>
						  <option value="PRIVATE">Private</option>
						</select>

						<button type="submit" class="pure-button pure-button-primary">Create</button>
					</fieldset>
				   
				</form>

			</div>
	    </td>
	  </tr>
	</table>

	<div style="text-align:center; font-size: 40px; color: #3B0029;">
		<?php
			echo '<a style="color: inherit; text-decoration: none; font-size: 40px;" href="main.php?channel=General">  <  </a>';
			echo '<strong>Invite someone to channel</strong>';
		?>
	</div>
	<hr class="style13">

	<table style="width: 60%; cellpadding: 10px; margin: 0 auto;">
	  <tr>
	    <td align="center">
	    	<div style="padding: 10px 0px 0px 10px;width: 80%; height: 20%;">
				
				<?php
					$successMsg = 'Invitation Successful';
					printMsg('new.php.invite.msg', $successMsg);
				?>

				<form class="pure-form" action="new.php" method="post">
				 	<fieldset>

            			<select name="user_id">
            				<?php
	            				for($i = 0; $i<count($_SESSION['users']); $i++)
	            				{
	            					$user = $_SESSION['users'][$i];
	            					if( $_POST['user_id'] === $user['user_id'] )
	            					{
	            						//set last selection
	            						echo '<option selected="selected" value="' . $user['user_id'] . '">' . $user['fname'] . ' ' . $user['lname'] . '</option>';
	            					}
	            					else
	            					{
	            						echo '<option value="' . $user['user_id'] . '">' . $user['fname'] . ' ' . $user['lname'] . '</option>';
	            					}
	            				}
            				?>
						</select>

						<select name="channel_id">
            				<?php
	            				for($i = 0; $i<count($_SESSION['channels']); $i++)
	            				{
	            					$privateFlag = '';
	            					$selectedFlag = '';
	            					$channel = $_SESSION['channels'][$i];

	            					if( $_POST['channel_id'] === $channel['channel_id'] )
	            					{
	            						//set last selection
	            						$selectedFlag = 'selected="selected" ';
	            					}

	            					if( $channel['type'] === 'PRIVATE' )
	            					{
	            						$privateFlag = '&#128274;';
	            					}

	            					echo '<option ' . $selectedFlag . 'value="' . $channel['channel_id'] . '">' . $privateFlag . ' ' . $channel['name'] . '</option>';
	            				}
            				?>
						</select>

						<button type="submit" class="pure-button pure-button-primary">Invite</button>
					</fieldset>
				</form>

			</div>
	    </td>
	  </tr>
	</table>



</body>
</html>