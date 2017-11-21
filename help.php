<?php
	include('services.php');
	session_start();
	
	if( isset($_SESSION['authenticationFlag']) === false )
	{
		header('Location: index.php');
		exit;
	}
?>


<html>

<head>
	<script src="common.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">

</head>

<body>

	
	<div style="text-align:center; font-size: 40px; color: #3B0029;">
		<a style="color: inherit; text-decoration: none; font-size: 40px;" href="main.php?channel=general"> &lt; </a>
		<strong>Help</strong>
	</div>

	<hr class="style13">
	
	<div style="width: 80%;  margin: 0 auto;">
		<h3>How to create a channel</h3>
		<ul>
			<li>From the homepage, click "New channel/invite" to create a new channel or invite a user to 
			a particular channel</li>
		</ul>

		<h3>How to become a channel member</h3>
		<ul>
			<li>From the channel panel, select the channel, and post inside the channel to subscribe</li>
		</ul>

		<h3>How to make a user a channel member</h3>
		<ul>
			<li>Send an invitation to the user to enroll the user to a channel</li>
		</ul>

		<h3>Change your profile image</h3>
		<ul>
			<li>From the profile page, under your avatar, click "Select image" and click "Upload new image"</li>
		</ul>

		<h3>Embed an image to a post</h3>
		<ul>
			<li>Paste image url in post text box or select an image from local file ("Upload image)</li>
		</ul>

		<h3>See replies to a post</h3>
		<ul>
			<li>Click on the reply count of the post to see thread</li>
		</ul>

	<hr class="style13">
		<h3>ADMINISTRATOR</h3>

		<h3>Archive/Unarchive channel</h3>
		<ul>
			<li>From profile, under "Current Archived Channels," check to archive a channel, uncheck to unarchive channel</li>
		</ul>

		<h3>Edit a user's channel membership</h3>
		<ul>
			<li>From profile, under "Edit User Channel Membership," select the user and select/unselect the channels, hit submit</li>
		</ul>

	</div>



</body>
</html>