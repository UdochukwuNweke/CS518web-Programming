<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	include('DB.php');


	/*
	//LOGIN
	$email ='mater@rsprings.gov';
	$pass = '@mater';
	$isLoggedIn = login($email, $pass);
	var_dump($isLoggedIn);
	*/
	
	
	
	//POST
	$channel_id = 5;
	$parent_id = 5;
	$content = 'it is right.';
	$fname= 'Tow';
	$lname = 'Mater';
	$user_id = 4;

	$numRows = post($channel_id, $parent_id, $content, $fname, $lname, $user_id);
	echo 'posted items:' . $numRows;
	

	
	/*
	//DELETE POST
	$post_id = 54;
	$user_id = 12;
	$numRows = deletePost($post_id, $user_id);
	echo 'deleted items:' . $numRows;
	*/
	




?>