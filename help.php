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
	

	<table style="width: 60%; cellpadding: 10px; margin: 0 auto;">
	  <tr>

	    <td align="center">
	    	<div style="padding: 10px 0px 0px 10px; width:80%; height: 20%;">
	    		<h3> Profile Image </h3>
			</div>
	    </td>

	    <td align="center">
	    	<div style="padding: 10px 0px 0px 10px;width: 80%; height: 20%;">
			</div>
	    </td> 

	  </tr>


	  <tr>
	  	<td align="center">
	  		<div style="padding: 10px 0px 0px 10px; width:80%; height: 20%;">
	  			<h3>Public Channel Membership</h3>
	  		</div>
	  	</td>


	  	<td align="center">
	  		<div style="padding: 10px 0px 0px 10px; width:80%; height: 20%;">
	  	</td>
	  </tr>

	</table>


</body>
</html>