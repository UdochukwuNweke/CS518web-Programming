<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('services.php');

/*
$user = 17;
$two_factor_active = 1;
$token = 'RSgz';
#setTwoFactor($user, $two_factor_active, 2);
if( validate2FAChallenge($user, $token) )
{
  echo 'Good';
}
else
{
  echo 'bad';
}
*/



#$query = 'UPDATE Settings SET user_id = 17, WHERE condition;'
#var_dump( genericQuery($query) );

/*

$users = getStatsUsers();

for($i = 0; $i<count($users); $i++)
{
	print_r( $users[$i] );
	echo '<br>';
}

$post = 'The quick brown fox jumped https://pbs.twimg.com/profile_images/694021299891343360/CVACQUXm_400x400.jpg, over the lazy http://php.net/manual/en/function.stream-context-create.php https://pbs.twimg.com/media/DO3SOJNX0AM0h_x.jpg';
print_r(getImgLinksFromText($post));
*/


/*
#SELECT * FROM Post LIMIT 10 OFFSET 0;
$posts = genericQuery('SELECT * FROM Post WHERE parent_id=-1 LIMIT 10 OFFSET 20');
for($i = 0; $i<count($posts); $i++)
{
	echo $posts[$i]['datetime'];
	echo '<br>';
}
*/


/*
$channel_id = 3;
$archiveState = 'ARCHIVE';
//$archiveState = 'ACTIVE';
setChannelArchiveState($channel_id, $archiveState);
*/

/*
$channel_id = 4;
$user_id = 7;
removeChannelMembership($channel_id, $user_id);
*/

/*
$user_id = 16;
$role_type = 'DEFAULT';
setRole($user_id, $role_type);
*/


/*
$user_id = -1;
$channel_id = 2;

if( setChannelMembership($channel_id, $user_id) )
{
	echo 'Good';
}
else
{
	echo 'Bad';
}
*/

	
/*
$reaction_type_id = 1;
$post_id = 71;
$user_id = 1;
$fname = 'Tow';
$lname = 'Mater';
postReaction($reaction_type_id, $post_id, $user_id, $fname, $lname);
*/


//echo deleteReaction($reaction_id);


/*
$email = 'mater@rsprings.gov';
$password = '@mater';
var_dump( login($email, $password) );
*/



/*
$user_id = 2;
$fname = 'Sally';
$lname = 'Carrera';
$channel_id = 1;
$parent_id = 1;
$content = 'reply to hello db';
post($user_id, $fname, $lname, $channel_id, $parent_id, $content)
*/


/*
$post_id = 10;
$user_id = 1;
if( deletePost($post_id, $user_id) )
{
	echo 'Yes';
}
else
{
	echo 'No';
}
*/

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>CS418 Docker deployment system</title>
<link rel="stylesheet" type="text/css" href="cs418deployment.css" />
<script type="text/javascript" src="cs418deployment.js"></script>
<script>
  function authenticateWithGitHub()
  {
    //alert("hi there " + document.getElementById('csusername').value + "!");
    //alert("https://github.com/login/oauth/authorize?client_id=c79fd74073885f907596&scope=repo&state="
                  //                      +document.getElementById('csusername').value);
    window.location.href = "https://github.com/login/oauth/authorize?client_id=3492f83caf07f246d432&scope=repo&state="
          +document.getElementById('csusername').value;
  }
</script>
</head>
<body>
<!-- TODO: add license for this code/html -->
<h1>CS418 Docker Deployment System</h1>

<p>This system is setup for students' use in testing the development of their projects for CS418 on a system that uses Docker (which is also used on Demo Day). To use the system:</p>

<dl>
  <dt>Enter your CS user id.</dt>
  <dd>This is used to pull the repository you specified in Assignment 1 from the users directory in the class repository.</dd>

  <dt>Authenticate</dt>
  <dd>This will allow the system to deploy your code from GitHub.</dd>

  <dt>Deploy your code</dt>
  <dd>If authentication is successful, a "Dockerize my code" button will appear. Press it to deploy the code from your master branch.</dd>

  <dt>Access &amp; Test your code</dt>
  <dd>The system will return a URI where your deployed code can be accessed and a means to kill the instance.</dd>
</dl>

<form>
  <label for="csusername">CS Username:</label>
  <input type="text" value="unweke" placeholder="CS username" id="csusername" name="csusername" autocomplete="off" /><br>
  <input type="submit" value="Find my repo" onclick="updateRepoFromCSUsername()" />
</form>


