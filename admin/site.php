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
	include_once('../includes/pub.php');
	require_once('includes/loginid.php');
	

	if ((!isset($_SESSION['user_id'])) || ($_SESSION['admin'] != 1)) {
		echo 'You do not have access to this page';
		exit();
	}

    $iinfo = $dbc->prepare("select logo
						    from site_data");
	$iinfo->execute();
	$iinfo->bind_result($logo);
	$iinfo->store_result();
	$iinfo->fetch();

	
	$file = '../css/custom.css';
	$css = file_get_contents($file);
	$update = '';
	
	if (isset($_POST['submit'])) {
	
		$cssn = $_POST['css'];		
		file_put_contents($file,strip_tags($cssn));
		$update = '<span class="alert">CSS updated</span><br/>';

	}
	
	
	$file = '../css/custom.css';
	$css = file_get_contents($file);


?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $pubtitle; ?> - Site Management</title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
<link rel="stylesheet" type="text/css" href="../css/custom.css" />
</head>
<body>

<?php 
	require_once('includes/loggedin.php');
	include_once('includes/admintop.php'); 
?>	
	<div class="main">
		<div class="mainmenu">	
		<p/>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<fieldset>
					<legend>Site Management</legend>

						<p><a href="thist.php">Manage Title History</a></p>
						<p><a href="mtext.php">Edit Main Page Text</a></p>
						<p><a href="link.php">Update Domain Information</a></p>						
						<p><a href="logo.php">Edit Header Logo</a><br/><span class="alert">Note:</span>  If you change the logo, you will then need to update the link to the file in the custom css below to the below text:<br/>
						<?php echo '&nbsp;<span class="csslogo">background-image: url("../images/'.$logo.'");</span> <br/>and modify height and width of the header or image, as well as the link entry to match desired dimensions.</p>';		?>				

						<h4>CSS edit</h4>
						<?php echo $update; ?>
						<textarea name="css" cols="100" rows="20"><?php echo $css; ?></textarea>
				</fieldset>
				<input type="submit" name="submit" value="Update" />
			</form>
			 
		</div>	
	
	</div>
</body>
</html>