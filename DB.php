<?php
//http://www.elated.com/articles/mysql-for-absolute-beginners
date_default_timezone_set('America/New_York');

	$serverName = 'localhost';
	#$userName = 'unweke';
	#$password = 'gaccess123';

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
*/


/*
 * Responsible for login action: Check if user supplied email/password pair is in DB
 *
 * @param string email
 * @param string password
 * @return (array) - user user_id, fname, lname array
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
function genericGetAll($table, $optionalWhereClause='')
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
		$sqlQuery = 'SELECT * FROM ' . $table . ' ' . $optionalWhereClause;

		$result = $conn -> query($sqlQuery);
		$payload = mysqli_fetch_all ($result, MYSQLI_ASSOC);

	}
	catch(Exception $e) 
	{
		echo 'Message: ' . $e -> getMessage();
	}

	return $payload;
}

?>