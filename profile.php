<?php
	include('services.php');
	session_start();

	//echo exec('whoami');
	//username
	//channel dynamics
	//upload files

	//for public channel membership can be acquired by:
	//a. browsing and clicking join
	//b. by invitation

	//for private
	//a. by invitation
	
	if( $_FILES )
	{
		define ('SITE_ROOT', realpath(dirname(__FILE__)));

		print "Out files array:<br>";
		print_r($_FILES);
		print "<br><br>";

		
		$uploaddir = SITE_ROOT . '/profileImgs/';
  		$uploadfile = $uploaddir . basename($_FILES['mkfile']['name']);
  		$uploadfile = str_replace(".php", ".txt", $uploadfile); //prevent .php files from being uploaded

		if (!$_FILES['mkfile']['error'] && move_uploaded_file($_FILES['mkfile']['tmp_name'], $uploadfile)) 
		{
			echo "File is valid, and was successfully uploaded.\n";
			chmod($uploadfile, 0644);
		} 
		elseif($_FILES['mkfile']['error'])
		{
			echo "Error ".$_FILES['mkfile']['error']."<br />";
		} 
		else 
		{
			echo "Possible file upload attack!";
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

				<?php 					
					echo '<img src="https://www.w3schools.com/tags/smiley.gif" alt="avatar" width="200" height="200" style="float: left; border-radius: 5px; border: 1px solid #999999;">';
				?>
			</div>
	    </td>

	    <td align="center">
	    	<div style="padding: 10px 0px 0px 10px;width: 80%; height: 20%;">
				
				<form action="profile.php" enctype="multipart/form-data" method="post">
				    <fieldset>
				    	<input type="hidden" name="MAX_FILE_SIZE" value="1048576">
				    	<input class="pure-button pure-button-primary" name="mkfile" type="file">
				    	<button type="submit" class="pure-button">Upload new image</button>
					</fieldset>
				</form>

			</div>
	    </td> 
	    
	  </tr>
	</table>


</body>
</html>