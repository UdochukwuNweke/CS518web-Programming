<?php
	include('services.php');
	session_start();
	
	if( isset($_SESSION['authenticationFlag']) === false )
	{
		header('Location: index.php');
		exit;
	}

	if( isset($_GET['user']) == false )
	{
		header('Location: profile.php?user=' . $_SESSION['authenticationFlag']['user_id'] );
	}

	//for public channel membership can be acquired by:
	//a. clicking join
	//b. by invitation

	//for private
	//a. by invitation
	
	if( $_FILES )
	{
		$uploaddir = './profileImgs/';
  		$uploadfile = $uploaddir . $_SESSION['authenticationFlag']['user_id'] . '.jpg';
  		$uploadfile = str_replace('.php', '.txt', $uploadfile);//prevent .php files from being uploaded

		if (!$_FILES['mkfile']['error'] && move_uploaded_file($_FILES['mkfile']['tmp_name'], $uploadfile)) 
		{
			$_SESSION['profile.php.msg'] = 'go';
			chmod($uploadfile, 0644);
		} 
		elseif($_FILES['mkfile']['error'])
		{
			$_SESSION['profile.php.msg'] = 'Error ' . $_FILES['mkfile']['error'] . '. Make sure file size is under 1MB.';
		} 
		else 
		{
			$_SESSION['profile.php.msg'] = 'Error during upload';
		}
	}
	
?>


<html>

<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">

</head>

<body>

	
	<div style="text-align:center; font-size: 40px; color: #3B0029;">
		<?php
			echo '<a style="color: inherit; text-decoration: none; font-size: 40px;" href="main.php?channel=General">  <  </a>';
			echo '<strong>Profile for: ' . $_SESSION['authenticationFlag']['fname'] . ' ' . $_SESSION['authenticationFlag']['lname'] . '</strong>';
		?>
	</div>

	<hr class="style13">
	
	<!--
	See for form validation:
	https://www.w3schools.com/PhP/showphp.asp?filename=demo_form_validation_complete
	-->

	<table style="width: 60%; cellpadding: 10px; margin: 0 auto;">
	  <tr>

	    <td align="center">
	    	<div style="padding: 10px 0px 0px 10px; width:80%; height: 20%;">

	    		<h3> Profile Image </h3>
				<?php
					$avatar = './profileImgs/' . $_SESSION['authenticationFlag']['user_id'] . '.jpg';
					if( file_exists($avatar) )
					{
						/*
							echo '<div class="avatar" style="width: 200px; height: 200px;">';
								echo '<img src="'. $avatar .'" alt="avatar" style="border-radius: 4px; max-width: 100%; max-height: 100%; margin: 0 auto;">';
							echo '</div>';
						*/
						echo '<img src="'. $avatar .'" alt="avatar" class="avatar" style="width: 200px; height: 200px;">';	
					}
					else
					{
						echo '<img src="https://www.w3schools.com/tags/smiley.gif" alt="avatar" width="200" height="200" style="float: left; border-radius: 5px; border: 1px solid #999999;">';
					}
				?>
			</div>
	    </td>

	    <td align="center">
	    	<div style="padding: 10px 0px 0px 10px;width: 80%; height: 20%;">
				
				<?php 
					if( isset($_SESSION['profile.php.msg']) )
					{
						if( $_SESSION['profile.php.msg'] == 'go' )
						{
							unset( $_SESSION['profile.php.msg'] );
							echo '<strong><p style="color: green">Successfully uploaded file!</p></strong>';
						}
						else
						{
							echo '<strong><p style="color: red">' . $_SESSION['profile.php.msg'] . '</p></strong>';
						}
					}
				?>

				<form class="pure-form" action="profile.php" enctype="multipart/form-data" method="post">
				    <fieldset>
				    	<input type="hidden" name="MAX_FILE_SIZE" value="1048576">
				    	<input class="pure-button pure-button-primary" name="mkfile" type="file">
				    	<button type="submit" class="pure-button">Upload new image</button>
				    	
					</fieldset>
				</form>

			</div>
	    </td> 

	  </tr>

	  <tr>
	  	<td align="center">
	  		
	  		<div style="padding: 10px 0px 0px 10px; width:80%; height: 20%;">
	  			
	  			<h3>Public Channel Membership</h3>
	  			<ol>
		  			<?php
		  				for($i = 0; $i<count($_SESSION['pub-memb-channels']); $i++)
		  				{
		  					$channel = $_SESSION['pub-memb-channels'][$i];
		  					echo '<li>' . getHTMLForChannel($channel) . '</li>';
		  				}
		  				/*
		  				for($i = 0; $i<count($_SESSION['channels']); $i++)
		  				{
		  					if( $_SESSION['channels'][$i]['type'] == 'PUBLIC' )
		  					{
		  						echo '<li>' . getHTMLForChannel($_SESSION['channels'][$i]) . '</li>';
		  					}
		  				}
		  				*/
		  			?>
				</ol>

	  		</div>

	  	</td>


	  	<td align="center">
	  		<div style="padding: 10px 0px 0px 10px; width:80%; height: 20%;">
			
			<input type="checkbox" name="twoFA">
	  		Two-Factor Authentication

			</div>
	  	</td>

	  </tr>

	</table>


</body>
</html>