<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('services.php');


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

<html>
  <head>
    <title>reCAPTCHA demo: Simple page</title>
     <script src="https://www.google.com/recaptcha/api.js" async defer></script>
     <script type='text/javascript'>
 	function reCaptchad(){
		document.getElementById("myButton").disabled = false;
	}
     </script>
  </head>
  <body>
    <form action="?" method="POST">
      <div class="g-recaptcha" data-sitekey="6Le0vAgUAAAAAH_ZWM8tw3It6jkrqLHkFFTMOW-J" 
		data-callback="reCaptchad"></div>
      <br/>
      <input id="myButton" type="submit" value="Submit" disabled='true'>
    </form>
  </body>
</html>

