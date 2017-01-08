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
	
	$title_id = $_GET['title_id'];
	
	
	if (isset($_POST['submit'])) {
		

		$title = $_POST['pubtitle'];
		$ystart =$_POST['ystart'];
		$yend = $_POST['yend'];
			
		$tupdate = $dbc->prepare("update pub set title = ?, ystart = ?, yend = ? where title_id = ?");
		$tupdate->bind_param("sssi", $title, $ystart, $yend, $title_id);
		$tupdate->execute();
		$tupdate->close();

		header('Location: thist.php');	
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
			<?php
			$titlehist = $dbc->prepare("select title, ystart, yend from pub where title_id = ?");
			$titlehist->bind_param("i", $title_id);
			$titlehist->execute();
			$titlehist->bind_result($title, $ystart,$yend);
			$titlehist->fetch();
			
			?>			
			<form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?title_id='.$title_id; ?>">
				<fieldset>
					<legend>Add Title Change</legend>


					

						<label for "pubtitle">Publication Title</label><br/>
						<input type="text" size="50" id="pubtitle" name="pubtitle" value="<?php echo $title; ?>"/> Start Date:<span class="alert">*</span> <input type="text" size="10" name="ystart" value="<?php echo $ystart ?>"/> End Date:<span class="alert">*</span> <input type="text" size="10" name="yend" value="<?php echo $yend; ?>"/><br/>
						<span class="alert">Use format 0000-00-00 or 00000000 (Year-month-day)</span>
					<p/>
				</fieldset>
				<p/>
				<input type="submit" name="submit" value="Update" />
			</form>			
			</div>
		</div>	
	


	</body>
</html>