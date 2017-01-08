<html>
<head>
<title>Delete Title History</title>
</head>
<body>
<?php
session_start();
// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  GPL                                                                *
//                                                                              *
// ******************************************************************************

error_reporting(E_ALL);
ini_set( 'display_errors','1'); 

require_once('../includes/connect.php');
include_once('../includes/pub.php');
require_once('includes/loginid.php');



$title_id = $_GET['title_id'];

?>

<html>
<head>
<title>Delete Title History</title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
<link rel="stylesheet" type="text/css" href="../css/custom.css" />
</head>
<body>

<?php

	require_once('includes/loggedin.php');
	include_once('includes/admintop.php'); 
	
	if ((!isset($_SESSION['user_id'])) || ($_SESSION['admin'] != 1)) {
		echo 'You do not have access to this page';
		exit();
	  }
	  
	  //connect to the database
	$dbc = new mysqli($host, $username, $password, $dbname);

	if ($dbc->connect_error) {
		die("Connection failed: " . $dbc->connect_error);
	}


	
	if (isset($_POST['delete'])) {
	
		$delh = $dbc->prepare("Delete FROM pub where title_id = ?");				
		$delh->bind_param("i", $title_id);
		$delh->execute();
		$delh->close();	
	
		header('Location: ' . 'thist.php');
	
	}
	elseif (isset($_POST['cancel'])) {
	
			header('Location: ' . 'thist.php');
	}
	
	else {
	
		$hist = $dbc->prepare("select title, ystart, yend from pub where title_id = ?");
		$hist->bind_param("i", $title_id);
		$hist->execute();
		$hist->store_result();
		$hist->bind_result($title,$ystart, $yend);
			while ($hist->fetch()) {
				echo '<div class="error">Are you sure you want to delete ' . $title .  ' ('. $ystart .' - '. $yend. ')?  <p /> This cannot be undone.<br /><br /></div>';
				
			}
			
?>			

<?php			
		$hist->free_result();
		$hist->close();		
	}
	
?>

			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?title_id=' . $title_id; ?>">

				<input type="submit" name="delete" value="Delete" /> <input type="submit" name="cancel" value="Cancel" />

			</form>
			
</body>
</html>			