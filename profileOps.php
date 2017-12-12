<?php
include('services.php');
session_start();

$_SESSION['profileOps.php.msg'] = '';
if( $_FILES )
{
	$_SESSION['profileOps.php.msg'] = json_encode($_POST);
	$gravatarFlag = '';

	if( isset($_POST['image_src']) )
	{
		if( $_POST['image_src'] == 'gravatar' )
		{
			$gravatarFlag = 'grav-';
		}
	}
	
	$uploaddir = "./profileImgs/$gravatarFlag";
	$uploadfile = $uploaddir . $_SESSION['authenticationFlag']['user_id'] . '.jpg';
	$uploadfile = str_replace('.php', '.txt', $uploadfile);//prevent .php files from being uploaded

	$_SESSION['profileOps.php.msg'] = uploadImage($_FILES, 'image', $uploadfile);
}
else if( isset($_POST['edit_user_memb']) )
{
	if( isset($_POST['channel_id_memb']) == false )
	{
		$_POST['channel_id_memb'] = array();
	}
	
	if( isset($_POST['channel_id_mod']) == false )
	{
		$_POST['channel_id_mod'] = array();
	}
	
	$toAdd = array_diff( $_POST['channel_id_mod'], $_POST['channel_id_memb'] );
	$toRemove = array_diff( $_POST['channel_id_memb'], $_POST['channel_id_mod'] );
	//$_SESSION['admin.profileOps.php.msg.usermemb'] = array('add' => $toAdd, 'remove' => $toRemove, 'user_id' => $_POST['user_id']);
	
	foreach($toAdd as $index => $channel_id)
	{
		setChannelMembership($channel_id, $_POST['user_id']);
	}

	foreach($toRemove as $index => $channel_id)
	{
		removeChannelMembership($channel_id, $_POST['user_id']);
	}

	$_SESSION['admin.profileOps.php.msg.usermemb'] = 'Last activity for: ' . $_POST['fname'] . ' ' . $_POST['lname'] . '<br>added - ' . count($toAdd). ',<br> removed - ' . count($toRemove);
}

if( isset($_GET['channel_archive_state']) && isset($_GET['archive_state']) )
{	
	setChannelArchiveState($_GET['channel_archive_state'] , $_GET['archive_state']);
}
else if( isset($_GET['user_id']) && isset($_GET['2FA']) )
{
	setTwoFactor($_GET['user_id'], $_GET['2FA']);
}

if( isset($_POST['remove_profile_img']) )
{
	unlink($_POST['remove_profile_img']);
}

header('Location: profile.php');
?>
