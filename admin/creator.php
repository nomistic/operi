<html>

<head>

<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
<script>
    window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }
</script>
</head>
<body>
<div class="alert">Please verify that the creator does not already exist prior to entering a new one.</div>

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

	require_once('includes/loginid.php');
	require_once('../includes/connect.php');
	
	if (!isset($_SESSION['user_id'])) {
		echo 'You do not have access to this page';
		exit();
	  }


	if (isset($_POST['csubmit'])) {
	
		$first_nm = $_POST['first_nm'];
		$middle = $_POST['middle'];
		$last_nm = $_POST['last_nm'];
		if (isset($_POST['fac_ind'])) {
			$fac_ind = 'Y';
		}
		else {
			$fac_ind = 'N';
		}
		
		$cr = $dbc->prepare("INSERT into creator (creator_id, first_nm, middle, last_nm, fac_ind)
							 VALUES (NULL, ?,?,?,?)");
		$cr->bind_param("ssss", $first_nm, $middle, $last_nm, $fac_ind);
		$cr->execute();
		$cr->close();
		
		echo "<script>window.close();</script>";
	}


?>


<p/>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset><legend>New author/creator: </legend>

	First Name:<br/>
	<input type="text" name="first_nm" id="first_nm" size= 30 /><p/>
	Middle:<br/>
	<input type="text" name="middle" id="middle" size= 30 /><p/>
	Last Name:<br/>
	<input type="text" name="last_nm" id="last_nm" size= 30 /><p/>
	Faculty Member? <input type="checkbox" name="fac_ind" /><p/>
	<input type="submit" name="csubmit" value="Add Creator" />

	</fieldset>
</form>
<?php
 mysqli_close($dbc);
?>
</body>
</html>