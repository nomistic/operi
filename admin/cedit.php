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


	$creator_id = $_GET['creator_id'];
	
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
		
		$cr = $dbc->prepare("UPDATE creator
							 set first_nm = ?,
							 middle = ?, 
							 last_nm= ?, 
							 fac_ind= ?
							 where creator_id = ?");
		$cr->bind_param("ssssi", $first_nm, $middle, $last_nm, $fac_ind, $creator_id);
		$cr->execute();
		$cr->close();
		
		echo "<script>window.close();</script>";
	}

	$creator = $dbc->prepare("select first_nm, middle, last_nm, fac_ind from creator where creator_id = ?");
	$creator->bind_param("i",$creator_id);
	$creator->execute();
	$creator->bind_result($first_nm, $middle, $last_nm, $fac_ind);

	while ($creator->fetch()) {
		if ($fac_ind == 'Y') {
			$checked = 'checked="checked"';
		}
		else {
			$checked = '';
		
		}

?>


<p/>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?creator_id='.$creator_id; ?>">
	<fieldset><legend>Edit author/creator: </legend>

	First Name:<br/>
	<input type="text" name="first_nm" id="first_nm" size= 30 value="<?php echo $first_nm; ?>" /><p/>
	Middle:<br/>
	<input type="text" name="middle" id="middle" size= 30 value="<?php echo $middle; ?>" /><p/>
	Last Name:<br/>
	<input type="text" name="last_nm" id="last_nm" size= 30 value="<?php echo $last_nm; ?>" /><p/>
	Faculty Member? <input type="checkbox" name="fac_ind"  <?php echo $checked; ?> /><p/>
	<input type="submit" name="csubmit" value="Update Creator" />

	</fieldset>
</form>
<?php

	}

 $creator->close();
?>
</body>
</html>