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

	require_once('../includes/connect.php');
	
	require_once('includes/loginid.php');
	

	if ((!isset($_SESSION['user_id'])) || ($_SESSION['admin'] != 1)) {
		echo 'You do not have access to this page';
		exit();
	}
	
	include_once('../includes/pub.php');
	
	$update = '';
	
	if (isset($_POST['submit'])) {
		

		$maintext = $_POST['maintext'];

		$tupdate = $dbc->prepare("update site_data set maintext = ?");
		$tupdate->bind_param("s", $maintext);
		$tupdate->execute();
		$tupdate->close();
		
		$update = '<span class="alert">Main page text updated</span><br/>';

			
	}
	

	
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $pubtitle; ?> - Site Management</title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
<link rel="stylesheet" type="text/css" href="../css/custom.css" />

</head>
<body>
<?php 
	require_once('includes/loggedin.php');
	include_once('includes/admintop.php'); 
?>	

	<div class="container">
		<div class="mainmenu">	
			<?php echo $update; ?>
			<h4>Current Main Page Text:</h4>
			
			
			<?php
			$mp = $dbc->prepare("select maintext from site_data");
			$mp->execute();
			$mp->bind_result($maintext);
			$mp->fetch();
			

			echo '<p>'. $maintext. '</p>';
			?>
		
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<fieldset>
					<legend>Update Main Text (HTML tags allowed)</legend>


						<label for "maintext">Main Page Text</label><br/>
						<textarea name="maintext" rows="15" cols="50"/><?php echo $maintext; ?> </textarea>

					<p/>
				</fieldset>
				<p/>
				<input type="submit" name="submit" value="Submit" />
			</form>
			<?php
			$mp->close();
			?>
		</div>	
	
	</div>
</body>
</html>		