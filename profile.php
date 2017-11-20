<?php
	include('services.php');
	session_start();
	
	if( isset($_SESSION['authenticationFlag']) === false )
	{
		header('Location: index.php');
		exit;
	}

	if( isset($_GET['user']) == false )
	{
		header('Location: profile.php?user=' . $_SESSION['authenticationFlag']['user_id'] );
	}
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
		<?php
			echo '<a style="color: inherit; text-decoration: none; font-size: 40px;" href="main.php?channel=general">  <  </a>';
			echo '<strong>Profile for: ' . $_SESSION['authenticationFlag']['fname'] . ' ' . $_SESSION['authenticationFlag']['lname'] . '</strong>';
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
	    	<div style="padding: 10px 0px 0px 10px; width:80%; height: 20%;">

	    		<h3> Profile Image </h3>
				<?php
					if( isset($_SESSION['profileOps.php.msg']) )
					{
						if( $_SESSION['profileOps.php.msg'] == 'go' )
						{
							//unset( $_SESSION['profileOps.php.msg'] );
							echo '<strong><p style="color: green">Successfully uploaded file!</p></strong>';
						}
						else
						{
							echo '<strong><p style="color: red">' . $_SESSION['profileOps.php.msg'] . '</p></strong>';
						}
					}

					$avatar = './profileImgs/' . $_SESSION['authenticationFlag']['user_id'] . '.jpg';
					if( file_exists($avatar) )
					{
						echo '<img src="'. $avatar .'" alt="avatar" class="avatar" style="float: inherit; width: 200px; height: 200px;">';	
					}
					else
					{
						echo '<img src="https://www.w3schools.com/tags/smiley.gif" alt="avatar" width="200" height="200" style="border-radius: 5px; border: 1px solid #999999;">';
					}
				?>
				<form class="pure-form" action="profileOps.php" enctype="multipart/form-data" method="post">
				    <fieldset>
				    	<input type="file" name="mkfile" style="opacity: 0;position: absolute;z-index: -1;"/>
				    	
				    	<label for="upload-photo1" style="cursor: pointer;">   &#128247; Upload image (1MB)</label>
				    	<input type="hidden" name="MAX_FILE_SIZE" value="1048576">
				    	
				    	<button type="submit" class="pure-button">Upload new image</button>
					</fieldset>
				</form>

			</div>
	    </td>

	    <td>
	    	<div style="padding: 10px 0px 0px 10px;width: 80%; height: 20%;">
				
				<h3 align="center"> Reputation </h3>
				
				<ul>
		  			<?php

		  				function getCount($table)
		  				{
		  					$query = 'SELECT count(*) count FROM '. $table . ' WHERE user_id=' . $_SESSION['authenticationFlag']['user_id'];
							$response = genericQuery($query);
							
							if( count($response) != 0 )
							{
								if( isset($response[0]['count']) )
								{
									return $response[0]['count'];
								}
							}
							
							return 0;
		  				}

		  				function getFirstMsg()
		  				{
		  					$query = 'SELECT * FROM Post WHERE user_id=' . $_SESSION['authenticationFlag']['user_id'] . ' LIMIT 1';
							$response = genericQuery($query);
							if( count($response) != 0 )
							{
								return explode(' ', $response[0]['datetime'])[0];
							}

							return '';
		  				}

		  				function getChannelsOwnership()
		  				{
		  					$query = 'SELECT * FROM Channel WHERE creator_id=' . $_SESSION['authenticationFlag']['user_id'];
							$response = genericQuery($query);

							echo '<ol>';
							for($i = 0; $i<count($response); $i++)
							{
								echo '<li>' . $response[$i]['name'] . ' (' . $response[$i]['type'] . ' - ' . $response[$i]['state'] . ')' . '</li>';
							}
							echo '</ol>';
		  				}
		  				
		  				//$_SESSION['authenticationFlag'];

		  				echo '<li><strong>Public channel count: </strong>' . count($_SESSION['channels']['pub-memb']) . '</li>';
		  				echo '<li><strong>Private channel count: </strong>' . count($_SESSION['channels']['priv-memb']) . '</li>';
		  				echo '<li><strong>Post count: </strong>' . getCount('Post') . '</li>';
		  				echo '<br>';
		  				echo '<li><strong>Reaction count: </strong>' . getCount('Reaction') . '</li>';
		  				echo '<li><strong>First Message date: </strong>' . getFirstMsg() . '</li>';
		  				echo '<br>';
		  				echo '<li><strong>Channels I own: </strong></li>';
		  				getChannelsOwnership();
		  			?>
				</ul>

			</div>
	    </td> 

	  </tr>

	  <tr>
	  	<td align="center">
	  		
	  		<div style="padding: 10px 0px 0px 10px; width:80%; height: 20%;">
	  			
	  			<h3>Public Channel Membership</h3>
	  			<ol>
		  			<?php
		  				for($i = 0; $i<count($_SESSION['channels']['pub-memb']); $i++)
		  				{
		  					$channel = $_SESSION['channels']['pub-memb'][$i];
		  					echo '<li>' . getHTMLForChannel($channel) . '</li>';
		  				}
		  			?>
				</ol>

	  		</div>

	  	</td>


	  	<td align="center">
	  		<div style="padding: 10px 0px 0px 10px; width:80%; height: 20%;">
				<input type="checkbox" name="twoFA">
	  			Two-Factor Authentication
			</div>
	  	</td>

	  </tr>

	  <tr>
	  	<td align="center">

	  		<div style="padding: 10px 0px 0px 10px; width:80%; height: 20%;">
	  			
	  			<?php

	  				function setMembershipForUsers($memberChannels, $users)
	  				{
	  					for($i = 0; $i<count($users); $i++)
	  					{
	  						$users[$i]['channel_membership'] = array();

	  						for($j = 0; $j < count($memberChannels); $j++)
	  						{
	  							if( $memberChannels[$j]['user_id'] === $users[$i]['user_id'] )
	  							{
	  								$channel_id = $memberChannels[$j]['channel_id'];
	  								$users[$i]['channel_membership'][$channel_id] = true;
	  							}

	  						}
	  					}

	  					return $users;
	  				}

	  				function createChannelDict($channels)
	  				{
	  					$channelDict = array();

	  					for($i = 0; $i<count($channels); $i++)
	  					{
	  						$channelDict[ $channels[$i]['channel_id'] ] = $channels[$i];
	  					}

	  					return $channelDict;
	  				}

	  				$edit_user = array();
	  				if( $_SESSION['authenticationFlag']['role_type'] === 'ADMIN' )
	  				{
	  					$memberChannels = genericGetAll('Channel_Membership');
	  					$_SESSION['users'] = setMembershipForUsers($memberChannels, $_SESSION['users']);
	  					$channels = createChannelDict(genericGetAll('Channel'));
	  					
	  					if( isset($_GET['edit_user']) == true )
						{
							$edit_user = $_GET['edit_user'];
						}
	
	  					echo '<h3>Edit User Channel Membership</h3>';
	  					
	  					if( isset($_SESSION['admin.profileOps.php.msg.usermemb']) )
						{
							echo '<strong><p style="color: blue">' . $_SESSION['admin.profileOps.php.msg.usermemb'] . '</p></strong>';
						}

	  					echo '<select onchange="editMembershipForUser(this)">';
	  					echo '<option value="">Select a user</option>';
	  					
	  					for($i = 0; $i<count($_SESSION['users']); $i++)
	  					{
	  						$user = $_SESSION['users'][$i];
	  						$selectedFlag = '';
	  						if( $edit_user == $user['user_id'] )
	  						{
	  							$selectedFlag = 'selected';
	  							$edit_user = $user;
	  						}

	  						echo '<option '. $selectedFlag .' value="' . $user['user_id'] . '">' . $user['fname'] . ' ' . $user['lname'] . '</option>';
	  					}
	  					echo '</select>';
					}
	  			?>
	  			
	  			<div style="text-align: left;">
		  			<form class="pure-form" action="profileOps.php" method="post">
		  				<fieldset>
		  					<?php
			  					if( $_SESSION['authenticationFlag']['role_type'] === 'ADMIN' )
			  					{
			  						if( isset($edit_user['user_id']) )
			  						{
			  							echo '<input type="hidden" name="user_id" value="'. $edit_user['user_id'] .'">';
			  							echo '<input type="hidden" name="fname" value="'. $edit_user['fname'] .'">';
			  							echo '<input type="hidden" name="lname" value="'. $edit_user['lname'] .'">';

			  							foreach($edit_user['channel_membership'] as $channel_id => $onOffFlag)
			  							{
			  								echo '<input type="hidden" name="channel_id_memb[]" value="' . $channel_id . '">';
			  							}

			  							foreach ($channels as $channel_id => $channelDetails) 
			  							{
			  								if( isset($edit_user['channel_membership'][$channel_id]) )
			  								{
			  									echo '<input checked value="'. $channel_id .'" type="checkbox" name="channel_id_mod[]"> ' . getHTMLForChannel($channelDetails, false) . '<br>';
			  								}
			  								else
			  								{
			  									$edit_user['channel_membership'][$channel_id] = false;
			  									echo '<input value="'. $channel_id .'" type="checkbox" name="channel_id_mod[]"> ' . getHTMLForChannel($channelDetails, false) . '<br>';
			  								}
										}
										echo '<input type="hidden" name="edit_user_memb">';
			  							echo '<br>';
			  							echo '<button type="submit" class="pure-button pure-button-primary">Submit</button>';
			  						}
			  						
			  					}
		  					?>

						</fieldset>
		  			</form>
	  			</div>
	  			
				
	  		</div>
	  	</td>

	  	<td align="center">
	  		<div style="padding: 10px 0px 0px 10px; width:80%; height: 20%;">
		  			<?php
		  				if( $_SESSION['authenticationFlag']['role_type'] === 'ADMIN' )
		  				{
		  					echo '<div style="text-align: right;">';
		  					echo '<h3>Current Archived Channels</h3>';							
		  					
		  					foreach ($channels as $channel_id => $channel) 
		  					{
		  						$checkedFlag = '';
		  						if( $channel['state'] == 'ARCHIVE' )
		  						{
		  							echo getHTMLForChannel($channel, false). ' <input onchange="editArchive(this)" value="'. $channel_id .'" type="checkbox" checked name="channel_archive[]"> <br>';
		  						}
		  						else
		  						{
		  							echo getHTMLForChannel($channel, false). ' <input onchange="editArchive(this)" value="'. $channel_id .'" type="checkbox" name="channel_active[]"> <br>';
		  						}
		  						
		  					}
		  					echo '<br>';
		  					echo '</div>';
		  				}
		  			?>
			</div>
	  	</td>

	  </tr>

	  <tr>
	  	<td align="center" colspan="2">
	  		<div style="padding: 10px 0px 0px 10px; width:80%; height: 20%;">

	  			<h3> Search User Profiles </h3>
	  			<form class="pure-form">
				    <fieldset>
				        <legend></legend>
				        
				        <?php
							echo '<input onkeyup="searchForUser(this)" name="email" type="text" placeholder="firstname lastname">';
				        ?>
				    </fieldset>
			    </form>

			    <div id="singleUserResTemplate" style="display: none">
				    <?php
				    	echo '<h3 class="userName">'. $_SESSION['authenticationFlag']['fname'] . ' ' . $_SESSION['authenticationFlag']['lname'] . '</h3>';
						$avatar = './profileImgs/' . $_SESSION['authenticationFlag']['user_id'] . '.jpg';
						if( file_exists($avatar) )
						{
							echo '<img src="'. $avatar .'" alt="avatar" class="avatar" style="float: inherit; width: 200px; height: 200px;">';	
						}
						else
						{
							echo '<img src="https://www.w3schools.com/tags/smiley.gif" alt="avatar" class="avatar" width="200" height="200" style="border-radius: 5px; border: 1px solid #999999;">';
						}
					?>

					<h3>Public Channel Membership</h3>
		  			<ol class="channelMemb" style="width: 30%;">
			  			<?php
			  				for($i = 0; $i<count($_SESSION['channels']['pub-memb']); $i++)
			  				{
			  					$channel = $_SESSION['channels']['pub-memb'][$i];
			  					echo '<li>' . getHTMLForChannel($channel) . '</li>';
			  				}
			  			?>
					</ol>
				</div>

			    <div id="userSearchRes">
				</div>
	  		
			</div>
	  	</td>
	  </tr>

	</table>

	<script type="text/javascript">
		var globalTaskRunning = false;
		function editMembershipForUser(sender)
		{
			var uriParams = processURL();
			if( uriParams.edit_user == undefined )
			{
				window.location.href += '&edit_user=' + sender.value;
			}
			else
			{
				if( sender.value.length == 0 )
				{
					sender.value = 1;
				}
				window.location.href = window.location.href.replace('edit_user=' + uriParams.edit_user, 'edit_user=' + sender.value);
			}
		}

		function editArchive(sender)
		{
			var state = '';
			if( sender.checked === true )
			{
				state = 'ARCHIVE';
			}
			else
			{
				state = 'ACTIVE';
			}

			window.location.href = './profileOps.php?channel_archive_state=' + sender.value + '&archive_state=' + state;

		}

		function searchForUser(sender)
		{
			if( globalTaskRunning == true )
			{
				return;
			}

			var userSearchRes = document.getElementById('userSearchRes');
			userSearchRes.innerHTML = '';

			sender = sender.value.split(' ');
			var fname = sender[0].trim();
			var lname = '';

			if( sender.length > 1 )
			{	
				lname = sender[1].trim();
			}

			if( fname.length == 0 && lname.length == 0 )
			{
				return;
			}

			var payload = {getUserProfile: {}};
			payload.getUserProfile.fname = fname;
			payload.getUserProfile.lname = lname;

			httpPost(payload, './services.php', function(response)
		    {
		        response = JSON.parse(response);
		        if( response.response.length != 0 )
		        {
		        	globalTaskRunning = true;
		        	updateSearchResPanel(response);
		        }
		        else
		        {
		        	//try swap names
		        	payload.getUserProfile.fname = lname;
					payload.getUserProfile.lname = fname;

		        	httpPost(payload, './services.php', function(response)
					{
						response = JSON.parse(response);
						if( response.response.length != 0 )
				        {
				        	globalTaskRunning = true;
				        	updateSearchResPanel(response);
				        }
					});
		        }
		    });
		}

		function updateSearchResPanel(userDetails)
		{
			var template = document.getElementById('singleUserResTemplate');
			var userSearchRes = document.getElementById('userSearchRes');
			userSearchRes.innerHTML = '';
			
			for(var i=0; i<userDetails.response.length; i++)
			{
				var user = userDetails.response[i];
			
				var copy = template.cloneNode(true);
				copy.getElementsByClassName('userName')[0].innerText = user.fname + ' ' + user.lname;
				copy.style.display = 'block';
				copy.getElementsByClassName('avatar')[0].setAttribute('src', user.avatar);
			
				copy.getElementsByClassName('channelMemb')[0].innerHTML = '';
				//add channel membership
				for(var j=0; j<user.channel_memb.length; j++)
				{
					var li = document.createElement('li');
					li.innerText = '# ' + user.channel_memb[j].name;

					copy.getElementsByClassName('channelMemb')[0].appendChild(li);
				}
				
				userSearchRes.appendChild(copy);
			}
			
			userSearchRes.scrollIntoView();
			globalTaskRunning = false;
		}
	</script>
</body>
</html>