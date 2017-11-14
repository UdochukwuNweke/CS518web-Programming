<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('services.php');

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
