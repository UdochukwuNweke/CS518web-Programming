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

//check captcha
if( isset($_POST['g-recaptcha-response']) == false )
{
	$_SESSION['register.php.msg'] = 'Failed CAPTCHA, please solve CAPTCHA';
	
	header('Location: index.php');
	exit;
}
else
{
	//credit: https://stackoverflow.com/a/6609181
	$url = 'https://www.google.com/recaptcha/api/siteverify';

	$data = array(
        'secret' => '6LcOXTwUAAAAAAO3x6270dz3W1Y9Jto21rK4xt98', 
        'response' => $_POST['g-recaptcha-response']
    );

    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);	

	if ($result === FALSE ) 
    { 
      	$_SESSION['register.php.msg'] = 'Error1 processing CAPTCHA, please contact admin';

		header('Location: index.php');
		exit;
    }

    $result = json_decode($result, true);
    if( isset($result['success']) == false )
    {
    	$_SESSION['register.php.msg'] = 'Error2 processing CAPTCHA, please contact admin';

		header('Location: index.php');
		exit;
    }
    else
    {
    	if( $result['success'] != true )
    	{
    		$_SESSION['register.php.msg'] = 'Failed CAPTCHA, please solve CAPTCHA';

			header('Location: index.php');
			exit;
    	}
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
header('Location: index.php');
exit;
//check if user name exists - end
?>