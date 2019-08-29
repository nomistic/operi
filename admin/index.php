<?php
session_start();
require_once('includes/loginid.php');

	require_once('../includes/connect.php');
	include_once('../includes/pub.php');
// ***************************************************************************	***
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  GPL                                                                *
//                                                                              *
// ******************************************************************************

//require_once('loggedin.php');

?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $pubtitle; ?> Archive Administration</title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
<link rel="stylesheet" type="text/css" href="../css/custom.css" />
</head>
<body>


<?php

	  if (!isset($_SESSION['user_id'])) {
		echo '<p class="login"><a href="login.php">log in</a></p>';
		
		header('Location: login.php');
		exit();
	  }
	  else {
		echo('<nav class="navbar navbar-dark bg-dark"><ul> <li>Logged in: <span class="alert">' . $_SESSION['user_name'] . '</span></li><li><a href="logout.php">Log out</a></li></ul></nav>');
	  }



	include_once('includes/admintop.php');
	?>
	<div class="content">
		<div class="main">
	<?php
	$years = "SELECT distinct year FROM issue order by year desc";
	$yeare = $dbc->query($years);
	
	while ($row = $yeare->fetch_assoc()) {
		$year = $row['year'];

		echo '<h3>' . $year . '</h3>';		
		$yeared = $dbc->prepare("SELECT issue_id, year, issue_ed, number, year, pub_ind 
					FROM issue
					where year= ?
					order by number, volume desc");
		$yeared->bind_param("i", $year);
		$yeared->execute();
		$yeared->store_result();
		$yeared->bind_result($issue_id, $year, $issue_ed, $number, $year, $pub_ind);
		

		echo '<ul class="issues">';
			while ($yeared->fetch()) {
						
				if ($pub_ind != 1) {
					$published = 'unpublished';
				}
				else {
					$published = '';
				}				
			
				echo '<li class="issue"><a href="issue_admin.php?issue_id='. $issue_id .'">'.$issue_ed . '</a> <span class="alert">'.$published.'</span></li>';
			}
		echo '</ul>';
		$yeared->free_result();
		$yeared->close();
	
	}
	


?>

</div>
</div>
</body>
</html>