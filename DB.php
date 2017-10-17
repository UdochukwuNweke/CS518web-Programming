<?php
	date_default_timezone_set('America/New_York');

	$serverName = 'localhost';
	#$userName = 'unweke';
	#$password = 'gaccess123';

	$userName = 'admin';
	$password = 'M0n@rch$';
	$dbname = 'CS518DB';



	function login($email, $password)
	{
		$fnameLname =  array();

		try
		{
			$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['userName'], $GLOBALS['password'], $GLOBALS['dbname']);
			
			// Check connection
			if( $conn -> connect_error ) 
			{
				// consider logging error
	    		echo 'Connection failed: ' . $conn->connect_error;
			}
			else
			{
				// credit to: http://stackoverflow.com/a/60496
				$sqlQuery = $conn -> prepare("SELECT fname, lname FROM Users WHERE email=? AND password=?");
				$sqlQuery -> bind_param('ss', $email, $password);
				$sqlQuery -> execute();

				$sqlQuery -> bind_result($fname, $lname);
				if( $sqlQuery -> fetch() )
				{
			        $fnameLname['fname'] = $fname;
			        $fnameLname['lname'] = $lname;
				}
				
				$sqlQuery -> close();
				$conn -> close();
			}

		}
		catch(Exception $e)
		{
			echo 'Message: ' . $e -> getMessage();
		}


		return $fnameLname;
	}

	


	function post ($channel_id, $parent_id, $content, $fname, $lname, $user_id)
	{
		$numRows = 0;
		try
		{
			$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['userName'], $GLOBALS['password'], $GLOBALS['dbname']);
			$datetime = date("Y-m-d H:i:s");
			
			// Check connection
			if( $conn -> connect_error ) 
			{
				// consider logging error
	    		echo 'Connection failed: ' . $conn->connect_error;
			}
			else 
			{
				$sqlQuery = $conn -> prepare("INSERT INTO Post( datetime, channel_id, parent_id, content, fname, lname, user_id) VALUE(?, ?, ?, ?, ?, ?, ?)");
				$sqlQuery -> bind_param('siisssi', $datetime, $channel_id, $parent_id, $content, $fname, $lname, $user_id);	
				$sqlQuery -> execute();

				$numRows = $conn -> affected_rows;

				$sqlQuery -> close();
				$conn -> close();

			}
		}
		catch(Exception $e){
			echo 'Message: ' . $e -> getMessage();
		}

		return $numRows;

	}

	function deletePost($post_id, $user_id)
	{
		$numRows = 0;

		try
		{
			$conn = new mysqli($GLOBALS['serverName'], $GLOBALS['userName'], $GLOBALS['password'], $GLOBALS['dbname']);
			// Check connection
			if( $conn -> connect_error ) 
			{
				// consider logging error
	    		echo 'Connection failed: ' . $conn->connect_error;
			}
			else 
			{
				$sqlQuery = $conn -> prepare("DELETE FROM  Post WHERE post_id =? AND user_id = ?");
				$sqlQuery -> bind_param('ii', $post_id, $user_id);	
				$sqlQuery -> execute();

				$numRows = $conn -> affected_rows;

				$sqlQuery -> close();
				$conn -> close();

			}
		}
		catch(Exception $e){
			echo 'Message: ' . $e -> getMessage();
		}
		

		return $numRows;

	}


?>

