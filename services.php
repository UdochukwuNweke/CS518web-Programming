<?php
//http://www.elated.com/articles/mysql-for-absolute-beginners
date_default_timezone_set('America/New_York');

$serverName = 'localhost';
$dbUserName = 'unweke';
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
function post($user_id, $fname, $lname, $channel_id, $parent_id, $content)
{
	$datetime = date('Y-m-d H:i:s');
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
					post_id
					user_id
					channel_id
					parent_id
				datetime datetime
				string content
			*/

			$sqlQuery = $conn -> prepare('INSERT INTO  Post (user_id, fname, lname, channel_id, parent_id, datetime, content) VALUES (?, ?, ?, ?, ?, ?, ?)');
			$sqlQuery -> bind_param(
				'issiiss', 
				$user_id,
				$fname,
				$lname,
				$channel_id, 
				$parent_id,
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
				//delete prev reaction
				if( deleteReaction($doesReactionExist[0]['reaction_id']) === false )
				{
					//delete unsuccessful, abort
					return false;
				}
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

			$sqlQuery = $conn -> prepare('INSERT INTO  Reaction (reaction_type_id, post_id, user_id, fname, lname) VALUES (?, ?, ?, ?, ?)');
			$sqlQuery -> bind_param(
				'iiiss', 
				$reaction_type_id,
				$post_id,
				$user_id,
				$fname,
				$lname
			);


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
	//https://stackoverflow.com/a/37274332
	stream_context_set_default( [
	    'ssl' => [
	        'verify_peer' => false,
	        'verify_peer_name' => false,
	    ],
	]);

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




function getMsgDiv($post_id, $user_id, $fname, $lname, $datetime, $content, $parent_id, $auth_user_id, $channel_id, $msgExtraParams)
{
	$replyCount = count(genericGetAll('Post', 'WHERE parent_id=' . $post_id));
	$reactions = genericGetAll('Reaction', 'WHERE post_id=' . $post_id);
	$reactionDetails = array();

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
	echo '<strong>' . $fname . '<br>' . $lname . ' - ' . $post_id . ' </strong> <br>(' . $datetime . ')<br><br>';
	
	$content = '<div class="msgContent">' . $content . '</div>';

	echo $content;
	if( strpos($content, '<pre>') == -1 )
	{
		echo '<br><br>';
	}

	echo '<br><input type="submit" onclick="replyCounterClick(' . $post_id . ')" class="pure-button" class="replyCounter" value="'. $replyCount . ' Replies"><br>';
	echo '<form class="pure-form" enctype="multipart/form-data" method="post">';
		echo '<input value="'. $post_id . '" type="hidden" name="post_id">';//used for knowing post to delete
		echo '<input value="'. $parent_id . '" type="hidden" name="parent_id">';//used to show if this msg is a reply
		echo '<input value="'. $channel_id . '" type="hidden" name="channel_id">';//used to know channel for a reply msg
		
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
				//echo '<input class="pure-button" type="hidden" value="' . $reacType['reaction_type_id'] . '" name="' . $reacType['emoji'] . '">';
				//echo '<input class="pure-button" type="hidden" value="' . $reacType['reaction_type_id'] . '" name="reaction_type_id-' . $reacType['reaction_type_id'] . '">';

				if( isset($reactionDetails[$reacType['reaction_type_id']]) )
				{
					echo '<input class="pure-button" type="submit" value="' . $reacType['emoji'] . ': ' . count($reactionDetails[$reacType['reaction_type_id']]) . '" name="reaction-' . $reacType['reaction_type_id'] . '">';
				}
				else
				{
					echo '<input class="pure-button" type="submit" value="' . $reacType['emoji'] . ': 0" name="reaction-' . $reacType['reaction_type_id'] . '">';
				}
			}

			echo '<input class="pure-button" type="hidden" name="reaction">';
		}
		
		echo '<input type="hidden" name="MAX_FILE_SIZE" value="1048576">';
		echo '<input onclick="scrollToTop()" type="file" id="upload-photo1" name="mkfile" style="opacity: 0;position: absolute;z-index: -1;" />';
		//generate reaction fields - end
		echo '<br><input type="checkbox" name="pre_tag"> Pre-formated';
		echo '<label for="upload-photo1" style="cursor: pointer;">   &#128247; Upload image (1MB)</label>';
	echo '</form>';
	
	echo '<br>';

	echo '</div>';
}

function getHTMLForMessages($posts, $channel_id, $auth_user_id, $max=0, $msgExtraParams=array())
{	
	$index = 1;

	for($i = count($posts)-1; $i !== -1 ; $i--)
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

		if( $index === $max )
		{
			break;
		}

		$index = $index + 1;
	}
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

function getMessages($channel_id, $auth_user_id, $parent_id=-1, $msgExtraParams=array())
{
	$max = 0;
	if( isset($msgExtraParams['max']) )
	{
		$max = $msgExtraParams['max'];
	}

	//echo 'To get msges for channel: ' . $channel_id . '<br><br>';
	$posts = genericGetAll('Post', 'WHERE channel_id=' . $channel_id  .' AND ' . 'parent_id=' . $parent_id);
	getHTMLForMessages($posts, $channel_id, $auth_user_id, $max, $msgExtraParams);
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

function getHTMLForUser($user)
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

	$html = '<a style="color: inherit; text-decoration: none;" href="main.php?user=' 
		. $user['user_id'] 
		. '">' 
		. $onlineFlag . ' ' . $user['fname'] . ' ' . $user['lname']
		. '</a> <br>';

	return $html;
}

function uploadImage($files, $expectedType, $destName)
{
	$type = explode('/', $files['mkfile']['type'])[0];
	$response = '';

	if ( !$files['mkfile']['error'] ) 
	{
		if( $type == $expectedType )
		{
			if( move_uploaded_file($files['mkfile']['tmp_name'], $destName) )
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
	elseif($files['mkfile']['error'])
	{
		$response = 'Error ' . $files['mkfile']['error'] . '. Make sure file size is under 1MB.';
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

?>