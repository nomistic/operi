<?php

// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  Personal                                                           *
//                                                                              *
// ******************************************************************************


	require_once('includes/connect.php');
	include_once('includes/pub.php');

 
 ?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $pubtitle; ?> - All Types</title>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="css/digpub.css" />
<link rel="stylesheet" type="text/css" href="css/custom.css" />
</head>
<body>
 <?php
 
 include_once('includes/docheader.php'); 
 echo '<h3>Choose Article Type</h3>';


	//$type_code_id = $_GET['type_code'];
	
	$types = $dbc->query("select type_code, type_name from type where display= 1 order by type_name");

	echo '<ul class="record">';
	while ($row = $types->fetch_assoc()) {

		
		echo '<li class="types"><a href="tsearch.php?type_code='.$row['type_code'].'">'. $row['type_name'] . '</a> </li>';
		
	
	}
	echo '</ul>';						
	
?>


</body>
</html>