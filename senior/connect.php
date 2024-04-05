<?php
	$dbserver ="localhost";
	$dbuser = "root";
	$dbpasswd = "";
	$dbdb = "hospital";
	
	
	$con = mysqli_connect($dbserver, $dbuser, $dbpasswd, $dbdb);
	
	if (mysqli_connect_errno()) { // returns an integer
		die (mysqli_connect_error()); // returns a string
	}
	?>