<?php

	require_once 'dbconnect.php';

			//connect to the database
	$dbc = new mysqli($host, $username, $password, $dbname);

	if ($dbc->connect_error) {
		die("Connection failed: " . $dbc->connect_error);
		}


?>