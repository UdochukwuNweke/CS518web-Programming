<?php
session_start();
include('services.php');

$prevPost = $_POST;
validatePost();
$shouldRedirectFlag = false;

//check if validate post removed special characters, notify user of bad input
foreach ($_POST as $key => $value) 
{
	if( $_POST[$key] !== $prevPost[$key] )
	{
		$_SESSION['register.php.msg'] = 'Invalid characters for: ' . $key;
		
		header('Location: index.php');
		exit;
	}
}

//check for empty strings
foreach ($_POST as $key => $value) 
{
	if( strlen($_POST[$key]) == 0 )
	{
		$_SESSION['register.php.msg'] = 'Empty input for: ' . $key;	
		
		header('Location: index.php');
		exit;
	}
}

//check for password match
if( $_POST['Password'] != $_POST['Re-Password'] )
{
	$_SESSION['register.php.msg'] = 'Password mismatch';
	header('Location: index.php');
	exit;
}


//check if email already exists
if( count(genericGetAll('User', 'WHERE email="' . $_POST['Email'] . '";')) != 0 )
{
	$_SESSION['register.php.msg'] = 'Email already exists';
	header('Location: index.php');
	exit;
}

//check if user name exists - start
$users = genericGetAll('User');

for($i = 0; $i<count($users); $i++)
{
	if
	(
		strtolower($_POST['First-name']) == strtolower($users[$i]['fname']) &&
		strtolower($_POST['Last-name']) == strtolower($users[$i]['lname']) 
	)
	{
		$_SESSION['register.php.msg'] = 'First name: "' . $_POST['First-name'] . '" and Last name: "' . $_POST['Last-name'] . '" already exist';
		header('Location: index.php');
		exit;
	}
}

//add user details to db
$success = register($_POST['First-name'], $_POST['Last-name'], $_POST['Email'], $_POST['Password']);
if( $success )
{
	$_SESSION['register.php.msg'] = 'go';	
}
else
{
	$_SESSION['register.php.msg'] = 'An error occured. Please report to the admin';
}

$_SESSION['channels'] = array();
$_SESSION['pub-memb-channels'] = array();
header('Location: index.php');
exit;
//check if user name exists - end
?>