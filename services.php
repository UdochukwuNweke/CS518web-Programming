<?php
//http://www.elated.com/articles/mysql-for-absolute-beginners
date_default_timezone_set('America/New_York');

$serverName = 'localhost';
$dbUserName = 'admin';
$dbPassword = 'M0n@rch$';
$dbname = 'CS518DB';

/*
CS518 Tables

	User
		int user_id
		string
			fname
			lname
			email
			password

	Channel
		channel_id
		name
		purpose
		type
		creator_id

	Post
		int
			post_id
			user_id
			channel_id
			parent_id
		datetime datetime
		string content

	Reaction_Type
		reaction_type_id
		name
		emoji

	Reaction
		reaction_id
		post_id
		user_id
		reaction_type_id

	Role
		role_id
		user_id
		role_type
*/


//https://stackoverflow.com/a/37274332
stream_context_set_default( [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
]);


//web service - start
if( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	$request = file_get_contents('php://input');
	$request = json_decode($request, true);
	
	if( isset($request['getPost']) )
	{
		processGetPostWebServiceRequest($request['getPost']);
	}
	elseif( isset($request['getUserProfile']) )
	{
		processUserProfileWebServiceRequest($request['getUserProfile']);
	}
}

function processUserProfileWebServiceRequest($request)
{
	$response = array(
		'request' => $request
	);
	
	if( isset($request['fname']) && isset($request['lname']) )
	{
		$query = 'SELECT user_id, fname, lname FROM User WHERE fname LIKE "%' . $request['fname'] .  '%" AND lname LIKE "%' . $request['lname'] . '%" ';
		$response['response'] = genericQuery($query);

		for($i = 0; $i<count($response['response']); $i++)
		{
			$avatar = './profileImgs/' . $response['response'][$i]['user_id'] . '.jpg';
			if( file_exists($avatar) == false )
			{
				$avatar = 'https://www.w3schools.com/tags/smiley.gif';
			}

			$response['response'][$i]['channel_memb'] = genericQuery('SELECT * FROM Channel C, Channel_Membership  CM WHERE C.type="PUBLIC" AND C.channel_id=CM.channel_id AND CM.user_id='. $response['response'][$i]['user_id'] );#genericQuery('SELECT * FROM Channel_Membership WHERE user_id='. $response['response'][$i]['user_id']);
			$response['response'][$i]['avatar'] = $avatar;
		}
	}

	echo json_encode($response);
}

function processGetPostWebServiceRequest($request)
{
	$response = array(
		'request' => $request
	);
	
	ob_start();
	
	/*getMessages(
		$request['channel_id'],
		$request['auth_user_id'],
		$request['parent_id'],
		$request['msg_extra_params']
	);*/

	printChannelMsg(
		$request['channel_info'],
		$request['msg_extra_params'],
		$request['user_id'],
		$request['fname'],
		$request['lname'] 
	);

	$output = ob_get_clean();
	$response['response'] = $output;

	echo json_encode($response);
}
//web service - end

/*https://www.w3schools.com/php/php_form_validation.asp*/
function sanitizeInput($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

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

/*
 * Responsible for login action: Check if user supplied email/password pair is in DB
 *
 * @param string email
 * @param string password
 * @return (array) - user user_id, fname, lname, role_type array
 */
function login($email, $password)
{
	$names = array();

	try
	{
		$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['dbUserName'], $GLOBALS['dbPassword'], $GLOBALS['dbname']);

		// Check connection
		if( $conn->connect_error ) 
		{
			// consider logging error
			echo 'Connection failed: ' . $conn -> connect_error;
		} 
		else
		{
			$sqlQuery = $conn -> prepare('SELECT user_id, fname, lname from User WHERE email=? AND password=?');
			$sqlQuery -> bind_param('ss', $email, $password );
			$sqlQuery -> execute();

			$sqlQuery -> bind_result($user_id, $fname, $lname);
			if( $sqlQuery -> fetch() )
			{
		        $names['user_id'] = $user_id;
		        $names['fname'] = $fname;
		        $names['lname'] = $lname;
		        $names['role_type'] = 'DEFAULT';

		        $role = genericGetAll('Role', 'WHERE user_id=' . $user_id);
		        if( count($role) !== 0 )
		        {
		        	$names['role_type'] = $role[0]['role_type'];
		        }
			}

			$sqlQuery -> close();
			$conn -> close();
		}
	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $names;
}

/*
 * Responsible for deleting a post for a user-supplied post_id and user_id pair
 *
 * @param int user_id: the identifier for the user who composed the post
 * @param int post_id: the identifier for the post to be deleted
 * @return (bool) true - if delete operation successful, false - otherwise
 */
function deletePost($post_id, $user_id)
{
	$hasRows = false;

	try
	{
		$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['dbUserName'], $GLOBALS['dbPassword'], $GLOBALS['dbname']);
		// Check connection
		if( $conn -> connect_error ) 
		{
			// consider logging error
			echo 'Connection failed: ' . $conn -> connect_error;
		} 
		else
		{
			$sqlQuery = $conn -> prepare('DELETE FROM Post WHERE post_id=? AND user_id=?');
			$sqlQuery -> bind_param('ii', $post_id, $user_id);

			$sqlQuery -> execute();
			if( $conn -> affected_rows !== 0 )
			{
				$hasRows = true;
			}

			$sqlQuery -> close();
			$conn -> close();
		}
	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $hasRows;
}


function deleteReaction($reactionID)
{
	$hasRows = false;

	try
	{
		$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['dbUserName'], $GLOBALS['dbPassword'], $GLOBALS['dbname']);
		// Check connection
		if( $conn -> connect_error ) 
		{
			// consider logging error
			echo 'Connection failed: ' . $conn -> connect_error;
		} 
		else
		{
			$sqlQuery = $conn -> prepare('DELETE FROM Reaction WHERE reaction_id=?');
			$sqlQuery -> bind_param('i', $reactionID);

			$sqlQuery -> execute();
			if( $conn -> affected_rows !== 0 )
			{
				$hasRows = true;
			}

			$sqlQuery -> close();
			$conn -> close();
		}
	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $hasRows;
}

/*
 * Responsible for adding a post msg to the DB
 *
 * @param int user_id: the identifier for the user who composed the post
 
 * @param string fname: 
 * @param string lname: 

 * @param int channel_id: the identifier for the channel associated with this post
 * @param int parent_id: the identifier for a post that this post replies (if any). If one doesn't exist set to -1
 * @param string content: the post content
 * @return (bool) true - if post successful, false - otherwise
 */
function post($user_id, $fname, $lname, $channel_id, $parent_id, $content, $extra=array())
{
	$datetime = date('Y-m-d H:i:s');
	$hasRows = false;

	if( isset($extra['pair_user_id']) == false )
	{
		$extra['pair_user_id'] = "";
	}

	try
	{
		$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['dbUserName'], $GLOBALS['dbPassword'], $GLOBALS['dbname']);

		// Check connection
		if( $conn -> connect_error ) 
		{
			// consider logging error
			echo 'Connection failed: ' . $conn -> connect_error;
		} 
		else
		{
			/*
				int
					post_id
					user_id
					channel_id
					parent_id
				datetime datetime
				string content
			*/

			$sqlQuery = $conn -> prepare('INSERT INTO  Post (user_id, fname, lname, channel_id, parent_id, pair_user_id, datetime, content) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
			$sqlQuery -> bind_param(
				'issiisss', 
				$user_id,
				$fname,
				$lname,
				$channel_id, 
				$parent_id,
				$extra['pair_user_id'],
				$datetime,
				$content
			);

			$sqlQuery -> execute();
			if( $conn -> affected_rows !== 0 )
			{
				$hasRows = true;
			}

			//add membership if user is not already a member of channel - start
			//consider moving to main.php
			$memb = genericGetAll('Channel_Membership', 'WHERE user_id=' . $user_id . ' AND channel_id=' . $channel_id);
			if( count($memb) == 0 )
			{
				setChannelMembership($channel_id, $user_id);
			}
			//add membership if user is not already a member of channel - end

			$sqlQuery -> close();
			$conn -> close();
		}
	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $hasRows;
}

function postReaction($reaction_type_id, $post_id, $user_id, $fname, $lname)
{
	//avoid duplicate reaction - start
	$doesReactionExist = genericGetAll('Reaction', 'WHERE post_id=' . $post_id . ' AND user_id='. $user_id);
	$updateReactionFlag = -1;
	if( count($doesReactionExist) != 0 )
	{
		//ensure reaction retrieved belongs to user attempting to send reaction
		if( $doesReactionExist[0]['user_id'] == $user_id )
		{
			if( $doesReactionExist[0]['reaction_type_id'] === $reaction_type_id )
			{
				//this reaction already exists
				return false;
			}
			else
			{
				//delete prev reaction: bad, update old reaction 
				/*if( deleteReaction($doesReactionExist[0]['reaction_id']) === false )
				{
					//delete unsuccessful, abort
					return false;
				}*/
				$updateReactionFlag = $doesReactionExist[0]['reaction_id'];
			}
		}
	}
	//avoid duplicate reaction - end

	$hasRows = false;
	try
	{
		$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['dbUserName'], $GLOBALS['dbPassword'], $GLOBALS['dbname']);

		// Check connection
		if( $conn -> connect_error ) 
		{
			// consider logging error
			echo 'Connection failed: ' . $conn -> connect_error;
		} 
		else
		{
			/*
				int
					reaction_id (auto key)
					reaction_type_id
					post_id
					user_id
					fname
					lname
			*/

			if( $updateReactionFlag == -1 )
			{
				//new reaction
				$sqlQuery = $conn -> prepare('INSERT INTO  Reaction (reaction_type_id, post_id, user_id, fname, lname) VALUES (?, ?, ?, ?, ?)');
				$sqlQuery -> bind_param(
					'iiiss', 
					$reaction_type_id,
					$post_id,
					$user_id,
					$fname,
					$lname
				);
			}
			else
			{
				//update old reaction
				$sqlQuery = $conn -> prepare('UPDATE Reaction SET reaction_type_id = ? WHERE reaction_id = ?');
				$sqlQuery -> bind_param(
					'ii', 
					$reaction_type_id,
					$updateReactionFlag
				);
			}


			$sqlQuery -> execute();
			if( $conn -> affected_rows !== 0 )
			{
				$hasRows = true;
			}

			$sqlQuery -> close();
			$conn -> close();
		}
	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $hasRows;
}

function setRole($user_id, $role_type)
{
	$hasRows = false;

	try
	{
		$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['dbUserName'], $GLOBALS['dbPassword'], $GLOBALS['dbname']);

		// Check connection
		if( $conn -> connect_error ) 
		{
			// consider logging error
			echo 'Connection failed: ' . $conn -> connect_error;
		} 
		else
		{
			$sqlQuery = $conn -> prepare('INSERT INTO  Role (user_id, role_type) VALUES (?, ?)');
			$sqlQuery -> bind_param(
				'is', 
				$user_id,
				$role_type
			);

			$sqlQuery -> execute();
			if( $conn -> affected_rows !== 0 )
			{
				$hasRows = true;
			}
		}
	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $hasRows;
}

function removeChannelMembership($channel_id, $user_id)
{
	$hasRows = false;

	try
	{
		$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['dbUserName'], $GLOBALS['dbPassword'], $GLOBALS['dbname']);
		// Check connection
		if( $conn -> connect_error ) 
		{
			// consider logging error
			echo 'Connection failed: ' . $conn -> connect_error;
		} 
		else
		{
			$sqlQuery = $conn -> prepare('DELETE FROM Channel_Membership WHERE channel_id=? AND user_id=?');
			$sqlQuery -> bind_param('ii', $channel_id, $user_id);

			$sqlQuery -> execute();
			if( $conn -> affected_rows !== 0 )
			{
				$hasRows = true;
			}

			$sqlQuery -> close();
			$conn -> close();
		}
	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $hasRows;
}

function setChannelMembership($channel_id, $user_id)
{
	$hasRows = false;

	try
	{
		$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['dbUserName'], $GLOBALS['dbPassword'], $GLOBALS['dbname']);

		// Check connection
		if( $conn -> connect_error ) 
		{
			// consider logging error
			echo 'Connection failed: ' . $conn -> connect_error;
		} 
		else
		{
			$sqlQuery = $conn -> prepare('INSERT INTO Channel_Membership (channel_id, user_id) VALUES (?, ?)');
			$sqlQuery -> bind_param(
				'ii', 
				$channel_id,
				$user_id
			);

			$sqlQuery -> execute();
			if( $conn -> affected_rows !== 0 )
			{
				$hasRows = true;
			}
		}
	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $hasRows;
}

function setChannelArchiveState($channel_id, $archiveState)
{
	$hasRows = false;

	if( $archiveState != 'ARCHIVE' && $archiveState != 'ACTIVE' )
	{
		return false;
	}

	try
	{
		$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['dbUserName'], $GLOBALS['dbPassword'], $GLOBALS['dbname']);

		// Check connection
		if( $conn -> connect_error ) 
		{
			// consider logging error
			echo 'Connection failed: ' . $conn -> connect_error;
		} 
		else
		{
			$sqlQuery = $conn -> prepare('UPDATE Channel SET state=? WHERE channel_id=?');
			$sqlQuery -> bind_param(
				'si', 
				$archiveState,
				$channel_id
			);

			$sqlQuery -> execute();
			if( $conn -> affected_rows !== 0 )
			{
				$hasRows = true;
			}
		}
	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $hasRows;
}

function addChannel($name, $purpose, $type, $creator_id)
{
	$hasRows = false;

	try
	{
		$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['dbUserName'], $GLOBALS['dbPassword'], $GLOBALS['dbname']);

		// Check connection
		if( $conn -> connect_error ) 
		{
			// consider logging error
			echo 'Connection failed: ' . $conn -> connect_error;
		} 
		else
		{
			$state = 'ACTIVE';
			$sqlQuery = $conn -> prepare('INSERT INTO Channel (name, purpose, type, creator_id, state) VALUES (?, ?, ?, ?, ?)');
			$sqlQuery -> bind_param(
				'sssis', 
				$name,
				$purpose,
				$type, 
				$creator_id,
				$state
			);

			$sqlQuery -> execute();
			if( $conn -> affected_rows !== 0 )
			{
				$channel_id = $conn -> insert_id;
				//add new membership for creator of channel
				if( setChannelMembership($channel_id, $creator_id) )
				{
					$hasRows = true;
				}
			}
		}
	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $hasRows;
}

function validate2FAChallenge($user_id, $inputToken)
{
	$settings = genericQuery("SELECT * FROM Settings WHERE user_id=$user_id");
	$status = false;

	if( count($settings) == 0 )
	{
		//user is not enrolled in 2FA
		$status = True;
	}
	else
	{
		$settings = $settings[0];
		if( isset($settings['two_factor_active']) )
		{
			if( $settings['two_factor_active'] == 1 )
			{
				if( strtotime($settings['challenge_expr']) > strtotime(date('Y-m-d H:i:s')) )
				{
					//check if input
					if( trim($settings['two_factor_challenge']) == trim($inputToken) )
					{
						$status = True;
					}
				}
			}
		}
	}

	return $status;
}

function is2FAUser($user_id)
{
	$settings = genericQuery("SELECT two_factor_active FROM Settings WHERE user_id=$user_id");
	$status = false;

	if( count($settings) != 0 )
	{
		$settings = $settings[0];
		if( isset($settings['two_factor_active']) )
		{
			if( $settings['two_factor_active'] == 1 )
			{
				$status = true;
			}
		}
	}

	return $status;
}

function setTwoFactor($user_id, $two_factor_active, $two_factor_challenge='', $challengeMinsOffset=10)
{
	$previousSetting = genericQuery("SELECT settings_id FROM Settings WHERE user_id=$user_id LIMIT 1");
	$hasRows = false;

	$challenge_expr = date(
		'Y-m-d H:i:s', 
		strtotime("+$challengeMinsOffset minutes", 
			strtotime(date('Y-m-d H:i:s'))
		)
	);

	$two_factor_challenge = trim($two_factor_challenge);
	if( strlen($two_factor_challenge) == 0 )
	{
		$two_factor_challenge = getKRandStr(4);	
	}
	
	
	try
	{
		$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['dbUserName'], $GLOBALS['dbPassword'], $GLOBALS['dbname']);

		// Check connection
		if( $conn -> connect_error ) 
		{
			// consider logging error
			echo 'Connection failed: ' . $conn -> connect_error;
		} 
		else
		{
			if( count($previousSetting) == 0 )
			{
				echo 'insert';
				//insert
				$sqlQuery = $conn -> prepare('INSERT INTO  Settings (user_id, two_factor_active, challenge_expr, two_factor_challenge) VALUES (?, ?, ?, ?)');
				$sqlQuery -> bind_param(
					'iiss',
					$user_id,
					$two_factor_active,
					$challenge_expr,
					$two_factor_challenge
				);
			}
			else
			{
				echo 'update';
				//update
				$sqlQuery = $conn -> prepare('UPDATE Settings SET two_factor_active = ?, challenge_expr = ?, two_factor_challenge = ? WHERE user_id = ?');
				$sqlQuery -> bind_param(
					'issi',
					$two_factor_active,
					$challenge_expr,
					$two_factor_challenge,
					$user_id
				);
			}
			

			$sqlQuery -> execute();
			if( $conn -> affected_rows !== 0 )
			{
				$hasRows = true;
			}

			$sqlQuery -> close();
			$conn -> close();
		}
	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $hasRows;
}

function register($fname, $lname, $email, $password)
{
	
	$hasRows = false;

	try
	{
		$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['dbUserName'], $GLOBALS['dbPassword'], $GLOBALS['dbname']);

		// Check connection
		if( $conn -> connect_error ) 
		{
			// consider logging error
			echo 'Connection failed: ' . $conn -> connect_error;
		} 
		else
		{
			$sqlQuery = $conn -> prepare('INSERT INTO  User (fname, lname, email, password) VALUES (?, ?, ?, ?)');
			$sqlQuery -> bind_param(
				'ssss', 
				$fname,
				$lname,
				$email, 
				$password
			);

			$sqlQuery -> execute();
			if( $conn -> affected_rows !== 0 )
			{
				$user_id = $conn -> insert_id;
				//add new membership for new user to default channels
				if( setChannelMembership(1, $user_id) && setChannelMembership(2, $user_id) )
				{
					$hasRows = true;
				}

				setRole($user_id, 'DEFAULT');
			}

			$sqlQuery -> close();
			$conn -> close();
		}
	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $hasRows;
}

/*
 * Responsible for selecting all records from a user-supplied table
 *
 * @param string table: the name of the table to extract all records from
 * @param string optionalWhereClause: custom defined where clause
 * @return (array)
 */
function genericGetAll($table, $optionalWhereClause='', $optionalSelect='*')
{
	$table = trim($table);
	if( strlen($table) == 0 )
	{
		return array();
	}

	$payload = array();

	try
	{
		$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['dbUserName'], $GLOBALS['dbPassword'], $GLOBALS['dbname']);
		$sqlQuery = 'SELECT '. $optionalSelect . ' FROM ' . $table . ' ' . $optionalWhereClause;

		$result = $conn -> query($sqlQuery);
		//$payload = mysqli_fetch_all ($result, MYSQLI_ASSOC);
		while( $row = $result->fetch_assoc() )
		{
			array_push($payload, $row);
		}

		$result -> close();

	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $payload;
}

function genericQuery($sqlQuery)
{
	$payload = array();

	try
	{
		$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['dbUserName'], $GLOBALS['dbPassword'], $GLOBALS['dbname']);

		$result = $conn -> query($sqlQuery);
		//$payload = mysqli_fetch_all ($result, MYSQLI_ASSOC);
		while( $row = $result->fetch_assoc() )
		{
			array_push($payload, $row);
		}

		$result -> close();

	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $payload;
}




function getImgLinksFromText($post)
{
	$links = getLinksFromText($post);
	$imgLinks = array();

	for($i = 0; $i < count($links); $i++)
	{
		try
		{
			$response = get_headers( $links[$i] );
			for($j = 0; $j<count($response); $j++)
			{
				$response[$j] = strtolower(trim($response[$j]));
				if( strpos($response[$j], 'content-type:') === 0 )
				{
					$type = explode('content-type:', $response[$j]);
					if( count($type) > 1 )
					{
						$type = explode('/', $type[1]);
						if( count($type) > 0 )
						{
							if( trim($type[0]) == 'image' )
							{								
								array_push($imgLinks, trim($links[$i]));
							}
						}
					}
				}
			}
		}
		catch(Exception $e) 
		{

		}
		
	}

	return $imgLinks;
}

function getLinksFromText($post)
{
	//credit: https://stackoverflow.com/a/36564776
	preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $post, $match);
	return $match[0]; 
}

function addImgLinksToPost($content, $links)
{
	if( count($links) != 0 )
	{
		$content .= '<div><br>';	
	}	

	for($i = 0; $i<count($links); $i++)
	{
		$imgTag = '<img src="' . $links[$i] . '" alt="postImg" class="postImg">';
		if( strpos($content, $links[$i]) )
		{
			$content = str_replace(
				$links[$i], 
				$imgTag, 
				$content
			);
		}
		else
		{
			$content .= $imgTag;
		}
	}

	if( count($links) != 0 )
	{
		$content .= '</div>';
	}
	
	return $content;
}

function addDownloadLinkToPost($content, $filename, $link, $optImg='')
{
	$link = trim($link);
	if( strlen($link) == 0 )
	{
		return $content;
	}	

	$content .= '<div><br>';		
	$download = "<a class='postFile' src='$link' target='_blank' href='$link'> <strong>Download $filename</strong><br>$optImg</a>";

	if( strpos($content, $link) )
	{
		$content = str_replace(
			$link, 
			$download, 
			$content
		);
	}
	else
	{
		$content .= $download;
	}
	

	
	$content .= '</div>';
	
	return $content;
}


function getMsgDiv($post_id, $user_id, $fname, $lname, $datetime, $content, $parent_id, $auth_user_id, $channel_id, $msgExtraParams)
{
	$replyCount = count(genericGetAll('Post', 'WHERE parent_id=' . $post_id));
	$reactions = genericGetAll('Reaction', 'WHERE post_id=' . $post_id);
	$reactionDetails = array();

	if( isset($msgExtraParams['pair_user_id']) == false )
	{
		$msgExtraParams['pair_user_id'] = "";
	}

	//set reaction statistics - start
	for($i = 0; $i < count($reactions); $i++)
	{
		$reactionTypeId = $reactions[$i]['reaction_type_id'];
		$reactionUserName = $reactions[$i]['fname'] . ' ' . $reactions[$i]['lname'];

		if( isset($reactionDetails[$reactionTypeId]) )
		{
			array_push($reactionDetails[$reactionTypeId], $reactionUserName);
		}
		else
		{
			$reactionDetails[$reactionTypeId] = array($reactionUserName);
		}
	}
	//set reaction statistics - end


	echo '<div class="msgArea" id="post-' . $post_id . '">';
	
	$avatar = './profileImgs/' . $user_id . '.jpg';
	if( file_exists($avatar) )
	{
		echo '<img src="'. $avatar .'" alt="avatar" class="avatar">';
	}
	else
	{
		echo '<img src="https://www.w3schools.com/tags/smiley.gif" alt="avatar" class="avatar">';
	}
	echo '<strong>' . $fname . '<br>' . $lname . ' - ' . $post_id . ' </strong> <br><span value="' . $datetime . '" class="timestamp">' . $datetime . '</span><br><br>';
	
	$content = '<div class="msgContent">' . $content . '</div>';

	echo $content;
	if( strpos($content, '<pre>') == -1 )
	{
		echo '<br><br>';
	}

	echo '<br><input type="submit" onclick="replyCounterClick(' . $post_id . ')" class="pure-button replyInputCounter" class="replyCounter" value="'. $replyCount . ' Replies"><br>';
	echo '<form class="pure-form" enctype="multipart/form-data" method="post">';
		echo '<input value="'. $post_id . '" type="hidden" name="post_id">';//used for knowing post to delete
		echo '<input value="'. $parent_id . '" type="hidden" name="parent_id">';//used to show if this msg is a reply
		echo '<input value="'. $channel_id . '" type="hidden" name="channel_id">';//used to know channel for a reply msg
		echo '<input value="'. $msgExtraParams['pair_user_id'] . '" type="hidden" name="pair_user_id">';//for reply in direct msg
		
		//echo '<input placeholder="Enter reply" type="text" name="post">';
		echo '<textarea type="text" placeholder="Enter reply" name="post" style="margin-top: 0px; margin-bottom: 0px; height: 30px;"></textarea>';
		echo '<input type="hidden" name="channel_state" value="' . $msgExtraParams['state'] . '">';
		
		//if( $user_id == $auth_user_id )
		if( $msgExtraParams['role_type'] === 'ADMIN' )
		{
			echo '<input class="pure-button" type="submit" value="Delete" name="delete">';
			echo '<input value="'. $user_id . '" type="hidden" name="post_user_id">';
		}

		echo '<input class="pure-button" type="submit" value="Reply" name="reply">';
		
		//generate reaction fields - start
		if( isset($msgExtraParams['reactionTypes']) )
		{
			for($i = 0; $i < count($msgExtraParams['reactionTypes']); $i++)
			{
				$reacType = $msgExtraParams['reactionTypes'][$i];
				if( $i == 0 )
				{
					echo '<input value="'. $auth_user_id . '" type="hidden" name="user_id">';					
				}

				if( isset($reactionDetails[$reacType['reaction_type_id']]) )
				{
					echo '<input class="pure-button reaction-input" type="submit" value="' . $reacType['emoji'] . ': ' . count($reactionDetails[$reacType['reaction_type_id']]) . '" name="reaction-' . $reacType['reaction_type_id'] . '">';
				}
				else
				{
					echo '<input class="pure-button reaction-input" type="submit" value="' . $reacType['emoji'] . ': 0" name="reaction-' . $reacType['reaction_type_id'] . '">';
				}
			}

			echo '<input class="pure-button" type="hidden" name="reaction">';
		}
		//generate reaction fields - end
		echo '<input type="hidden" name="MAX_FILE_SIZE" value="5048576">';
		echo '<input onclick="scrollToTop()" type="file" id="upload-photo-' . $post_id . '" name="mkfile" style="opacity: 0;position: absolute;z-index: -1;" />';
		echo '<br><input type="checkbox" name="pre_tag"> Pre-formated';
		echo '<label for="upload-photo-' . $post_id . '" style="cursor: pointer;">   &#128247; Upload image (5MB)</label>';

		echo '<input onclick="scrollToTop()" type="file" id="upload-file-' . $post_id . '" name="mkfile-gen" style="opacity: 0;position: absolute;z-index: -1;" />';
		echo '<label for="upload-file-' . $post_id . '" style="cursor: pointer;">   &#128194; Upload file (5MB)</label>';
	
	echo '</form>';
	
	echo '<br>';

	echo '</div>';
}

function getHTMLForMessages($posts, $channel_id, $auth_user_id, $max=0, $msgExtraParams=array())
{	
	for($i = 0; $i < count($posts); $i++)
	{
		getMsgDiv(
			$posts[$i]['post_id'],
			$posts[$i]['user_id'],
			$posts[$i]['fname'],
			$posts[$i]['lname'],
			$posts[$i]['datetime'],
			$posts[$i]['content'],
			$posts[$i]['parent_id'],
			$auth_user_id,
			$channel_id,
			$msgExtraParams
		);

		if( $i+1 === $max )
		{
			break;
		}
	}
}

function setPagination($limit, $offset)
{
	$offset = ($offset - 1) * $limit;
	return " LIMIT $limit OFFSET $offset";
}

function getSingleMessage($post_id, $channel_id, $auth_user_id, $msgExtraParams=array())
{
	$max = 0;
	if( isset($msgExtraParams['max']) )
	{
		$max = $msgExtraParams['max'];
	}

	$posts = genericGetAll('Post', 'WHERE post_id=' . $post_id);
	getHTMLForMessages($posts, $channel_id, $auth_user_id, $max, $msgExtraParams);
}

function printChannelMsg($channelInfo, $msgExtraParams, $user_id, $fname, $lname)
{
	if( isset($msgExtraParams['pair_user_id']) == false )
	{
		$msgExtraParams['pair_user_id'] = "";
	}

	$threadFlag = '';
	$directMsgFlag = '';
	if( $channelInfo['post'] != -1 )
	{
		$threadFlag = ' (Replies to post: ' . $channelInfo['post'] . ')';
	}

	if( strlen($msgExtraParams['pair_user_id']) == 0 )
	{
		$directMsgFlag = $fname . ' ' . $lname . ' @ ' . $channelInfo['channelName'];
	}

	echo '<h3>' . $directMsgFlag . $threadFlag . '</h3>';

	if( $channelInfo['post'] != -1 )
	{
		//extract parent message which was clicked
		$msgExtraParams['max'] = 1;
		getSingleMessage(
			$channelInfo['post'], 
			$channelInfo['channelId'], 
			$user_id, 
			$msgExtraParams
		);
	}

	echo '<br><br>';
	echo '<hr class="style13">';

	$msgExtraParams['max'] = 0;
	getMessages( 
		$channelInfo['channelId'], 
		$user_id,
		$channelInfo['post'],
		$msgExtraParams
	);
}

function getMessages($channel_id, $auth_user_id, $parent_id=-1, $msgExtraParams=array())
{
	if( isset($msgExtraParams['pair_user_id']) == false )
	{
		$msgExtraParams['pair_user_id'] = "";
	}

	if( isset($msgExtraParams['max']) == false )
	{
		$msgExtraParams['max'] = 0;
	}

	
	$pagination = setPagination( $msgExtraParams['page_size'], $msgExtraParams['page'] );
	$query = 'SELECT * FROM Post' . 
	' WHERE channel_id=' . $channel_id  
	. ' AND ' . 'parent_id=' . $parent_id
	. ' AND ' . 'pair_user_id="' . $msgExtraParams['pair_user_id'] . '"'
	. ' ORDER BY post_id DESC'
	. $pagination
	;	
	
	//$posts = genericGetAll('Post', $orderbyClause . 'WHERE channel_id=' . $channel_id  . ' AND ' . 'parent_id=' . $parent_id);
	$posts = genericQuery($query);
	getHTMLForMessages(
		$posts, 
		$channel_id, 
		$auth_user_id, 
		$msgExtraParams['max'], 
		$msgExtraParams
	);

	if( count($posts) == 0 )
	{
		return false;
	}
	else
	{
		return true;
	}
}

function getHTMLForChannel($channel, $linkFlag=true)
{
	$privateFlag = '';
	$archiveFlag = '';
	if( $channel['type'] == 'PRIVATE' )
	{
		$privateFlag = '&#128274;';
	}

	if( $channel['state'] == 'ARCHIVE' )
	{
		$archiveFlag = '&#9688;';
	}

	if( $linkFlag == true )
	{
		$html = '<a style="color: inherit; text-decoration: none;" href="main.php?channel=' 
		. $channel['name'] 
		. '"> # ' 
		. $privateFlag . $archiveFlag . $channel['name'] 
		. '</a> <br>';
	}
	else
	{
		$html = '<span> # ' . $privateFlag . $archiveFlag . $channel['name'] . '</span>';
	}
	

	return $html;
}

function getStatsUsers()
{
	$users = genericQuery('SELECT user_id, fname, lname from User');

	for($i = 0; $i<count($users); $i++)
	{
		$thumbsUpCountQuery = 'SELECT count(*) post_like_count FROM Post P, Reaction R WHERE P.user_id=' . $users[$i]['user_id'] . ' AND P.post_id=R.post_id AND R.reaction_type_id=1';

		$postCountQuery = 'SELECT count(*) post_count FROM Post WHERE user_id=' . $users[$i]['user_id'];

		$users[$i]['post_like_count'] = genericQuery($thumbsUpCountQuery)[0]['post_like_count'];
		$users[$i]['post_count'] = genericQuery($postCountQuery)[0]['post_count'];
	}

	return $users;
}

function getPairUserID($pair)
{
	if( count($pair) < 2 )
	{
		return '';
	}

	sort($pair);
	return $pair[0] . '.' . $pair[1];
}

function getHTMLForUser($curUser, $user, $userGetParam='user')
{
	$onlineFlag = '';
	if( strlen($user['fname']) > 4  )
	{
		//online not implemented
		$onlineFlag = '&#128309;';
	}
	else
	{
		$onlineFlag = '&#9711;';
	}

	$html = '<a style="color: inherit; text-decoration: none;" href="main.php?' . $userGetParam . '=' 
		. getPairUserID(array($curUser['user_id'], $user['user_id']))
		. '">' 
		. $onlineFlag . ' ' . $user['fname'] . ' ' . $user['lname']
		. '</a> <br>';

	return $html;
}

function uploadImage($files, $expectedType, $destName, $accessor='mkfile')
{
	$type = explode('/', $files[$accessor]['type'])[0];
	$response = '';

	if ( !$files[$accessor]['error'] ) 
	{
		if( $type == $expectedType )
		{
			if( move_uploaded_file($files[$accessor]['tmp_name'], $destName) )
			{
				$response = 'go';	
				chmod($destName, 0644);
			}
			else
			{
				$response = 'Sorry processing error, please try again';	
			}
		}
		else
		{
			$response = 'Error: bad file format - ' . $type;	
		}
	} 
	elseif($files[$accessor]['error'])
	{
		$response = 'Error ' . $files[$accessor]['error'] . '. Make sure file size is under 1MB.';
	} 
	else 
	{
		$response = 'Error during upload';
	}

	return $response;
}

function getKRandStr($k)
{
	$alpha = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$alpha = str_shuffle($alpha);
	$randStr = '';

	for($i = 0; $i<strlen($alpha); $i++)
	{
		$randStr .= $alpha[$i];
		if( $i == $k-1 )
		{
			break;
		}
	}

	return $randStr;
}

function getResponseCode($url)
{
	$response = '404';
	
	$header = get_headers($url, 1);
	if( count($header) != 0 )
	{
		$header = explode(' ', $header[0]);
		if( count($header) > 1 )
		{
			return $header[1];
		}
	}

	return $response;
}

function sendEmail($to, $email, $subject='subject', $from='unweke@cs.odu.edu')
{
	//credit: https://www.w3schools.com/php/func_mail_mail.asp
	$headers = "From: $from" . "\r\n";
	mail($to, $subject, $email, $headers);
}

?>