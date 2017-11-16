<?php
include('services.php');
session_start();

$_SESSION['profileOps.php.msg'] = '';
if( $_FILES )
{
	$uploaddir = './profileImgs/';
		$uploadfile = $uploaddir . $_SESSION['authenticationFlag']['user_id'] . '.jpg';
		$uploadfile = str_replace('.php', '.txt', $uploadfile);//prevent .php files from being uploaded

	if (!$_FILES['mkfile']['error'] && move_uploaded_file($_FILES['mkfile']['tmp_name'], $uploadfile)) 
	{
		$_SESSION['profileOps.php.msg'] = 'go';
		chmod($uploadfile, 0644);
	} 
	elseif($_FILES['mkfile']['error'])
	{
		$_SESSION['profileOps.php.msg'] = 'Error ' . $_FILES['mkfile']['error'] . '. Make sure file size is under 1MB.';
	} 
	else 
	{
		$_SESSION['profileOps.php.msg'] = 'Error during upload';
	}
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

header('Location: profile.php');
?>
