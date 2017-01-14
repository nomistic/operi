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
	require_once('includes/contact.php');
	

	if ((!isset($_SESSION['user_id'])) || ($_SESSION['admin'] != 1)) {
		echo 'You do not have access to this page';
		exit();
	}
	
	include_once('../includes/pub.php');
	

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $pubtitle; ?> - Site Management</title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
<link rel="stylesheet" type="text/css" href="../css/custom.css" />

</head>
<script>
function disable() {
    document.getElementById("ldom").disabled = true;
}

function undisable() {
    document.getElementById("ldom").disabled = false;
}
</script>
<body>
<?php 
	require_once('includes/loggedin.php');
	include_once('includes/admintop.php'); 
			$mp = $dbc->prepare("select resetl, ldom_ind, ldom from site_data");
			$mp->execute();
			$mp->bind_result($resetl, $ldom_ind,$ldom);
			$mp->fetch();
			$mp->close();
	if (isset($_POST['submit'])) {
		

		$resetl = $_POST['resetl'];
		$ldom_ind = $_POST['ldom_ind'];
		if (isset($_POST['ldom'])) {
			$ldom = $_POST['ldom'];
		}
		else $ldom = $ldom;
		$tupdate = $dbc->prepare("update site_data set resetl = ?, ldom_ind = ?, ldom= ?");
		$tupdate->bind_param("sis", $resetl,$ldom_ind,$ldom);
		$tupdate->execute();
		$tupdate->close();
		
		echo '<span class="alert">Link Updated</span><br/>';
	
	}

?>	

	<div class="container">
		<div class="mainmenu">	
		
			<h4>Update Domain Information</h4>
			
			<p>Information on this page controls location of reset links, and offers the option to limit administrative access by domain.</p>
		
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<fieldset>
					<legend>Update Site Location</legend>

						<?php
	
						echo '<label for "resetl">Domain (URL)</label><br/>';
						echo 'http://<input name="resetl" size="80" value="'.  $resetl .'" /><p/>';
						
				echo '</fieldset><p/>';
						echo '<fieldset>';
						echo '<legend>Require Local Email Domain Access</legend>';

						echo '<label for "ldom_ind">Use Local Email Domain?<span class="alert">*</span></label> ';
						echo 'Yes:<input type="radio" name="ldom_ind" id="ldom_ind" value="1" onfocus="undisable()"';
						if ($ldom_ind == 1) {echo ' checked';}
						echo '/>';
						echo 'No:<input type="radio" name="ldom_ind" id="ldom_ind" value="0" onfocus="disable()"';
						if ($ldom_ind == 0) {echo ' checked';}
						echo ' /><br/><br/>';
						echo '&nbsp; &nbsp; &nbsp; <label for "ldom">Email Domain: </label>@<input type="text" name="ldom" id="ldom" value="'. $ldom.'" size ="40"';
						if ($ldom_ind == 0) {echo ' disabled';}
						echo '><br/><br/><span class="alert">*Note: only set this to yes if you are going to restrict all administrative users to have a standard base email, e.g. "@yourdomain.com"  Also please note that if you wish to use this option, it is highly recommended that the server itself be hosted within this domain, or it will likely fail DMARC authentication, and may end up treated as SPAM.</span><br/>';

						?>
					<p/>
				</fieldset>
				<p/>
				<input type="submit" name="submit" value="Submit" />
			</form>
		</div>	
	
	</div>
</body>
</html>		