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
	//connection variables


	$issue_id = $_GET['issue_id'];

    $iinfo = $dbc->prepare("select issue_ed, number, volume, issue_length, year, pub_ind
						    from issue where issue_id = ?");
	$iinfo->bind_param("i", $issue_id);
	$iinfo->execute();
	$iinfo->bind_result($issue_ed, $number, $volume, $issue_length, $year, $pub_ind);
	$iinfo->store_result();
	$iinfo->fetch();

						
	
	//if form is submitted
	if (isset($_POST['submit'])) {
	
		//initialize issue variables 
		$issue_ed = $_POST['issue_ed'];
		$number = $_POST['number'];
		$volume = $_POST['volume'];
		$issue_length  = $_POST['issue_length']; 
		$year = $_POST['year']; 
		$pub_ind = $_POST['pub_ind'];
		//$cover_id = $_POST['cover_id'];  

		$isi = $dbc->prepare("UPDATE issue 
							  SET issue_ed = ?, 
							  number = ?, 
							  volume = ?, 
							  issue_length = ?, 
							  year = ?,
							  pub_ind = ?
							  WHERE issue_id = ?");
		$isi->bind_param("siiiiii",  $issue_ed, $number, $volume, $issue_length, $year, $pub_ind, $issue_id);
		$isi->execute();
		$isi->close();


		header('Location: issue_admin.php?issue_id='.$issue_id);	
	}
	if (isset($_POST['cancel'])) {

		header('Location: issue_admin.php?issue_id='.$issue_id);	
	}

	
	?>
	
<!DOCTYPE html>
<html>
<head>
<title><?php echo $pubtitle . ' - Edit '.$issue_ed; ?></title>
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
<p/>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?issue_id='.$issue_id; ?>">
	<fieldset>
	<legend>Edit Issue</legend>


	Issue Name:<br/>
	<input type="text" name="issue_ed" value="<?php echo $issue_ed; ?>" /><br/>
	Year:<br/>  
	<input type="text" name="year" value="<?php echo $year; ?>" /><br/>
	Volume: <br/> <!-- input - enter as number-->
	<input type="text" name="volume" value="<?php echo $volume; ?>"/><br/>
	Number:<br/> <!-- option box?  should also have an explanation for what this is -->
	<input type="text" name="number" value="<?php echo $number; ?>" /><br/>
	Length:<br/>
	<input type="text" name="issue_length" value="<?php echo $issue_length; ?>" /><br/>
	
	<input type="radio" name="pub_ind" value="1" <?php if ($pub_ind == 1) {echo 'checked';} ?>> Published <br/>
	<input type="radio" name="pub_ind" value="0" <?php if ($pub_ind == 0) {echo 'checked';} ?>>  Unpublished 
	<p/>
	<input type="submit" name="submit" value="Edit issue" /> <input type="submit" name="cancel" value="Cancel" />



	</fieldset>
</form>

<?php
	$iinfo->free_result();
	$iinfo->close();
 mysqli_close($dbc);
?>
</div>
</body>
</html>