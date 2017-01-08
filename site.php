<?php
session_start();

// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  Personal                                                           *
//                                                                              *
// ******************************************************************************

	require_once('connect.php');
	
	require_once('loginid.php');
	

	if ((!isset($_SESSION['user_id'])) || ($_SESSION['admin'] != 1)) {
		echo 'You do not have access to this page';
		exit();
	}
	
	$file = 'custom.css';
	$css = file_get_contents($file);
	
	if (isset($_POST['submit'])) {
	
		$cssn = $_POST['css'];
		
		$title = $_POST['pubtitle'];
		$site = $dbc->prepare("update pub set title= ?");
		$site->bind_param("s",$title);
		$site->execute();
		$site->close();
		
		file_put_contents($file,strip_tags($cssn));

	
	}
	
	include_once('pub.php');
	$file = 'custom.css';
	$css = file_get_contents($file);


?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $pubtitle; ?> - Site Management</title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="digpub.css" />
<link rel="stylesheet" type="text/css" href="custom.css" />
</head>
<body>

<?php 
	require_once('loggedin.php');
	include_once('admintop.php'); 
?>	
	<div class="container">
		<div class="mainmenu">	
		<p/>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<fieldset>
					<legend>Site Management</legend>

						<p><a href="thist.php">Manage Title History</a></p>
						<p><a href="mtext.php">Edit Main Page Text</a></p>
						<h4>CSS edit</h4>
						<textarea name="css" cols="100" rows="20"><?php echo $css ?></textarea>
				</fieldset>
				<input type="submit" name="submit" value="Update" />
			</form>
			 
		</div>	
	
	</div>
</body>
</html>