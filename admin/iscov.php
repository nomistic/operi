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
	
	if (!isset($_SESSION['user_id'])) {
		echo 'You do not have access to this page';
		exit();
	  }

	$issue_id = $_GET['issue_id'];

    $iinfo = $dbc->prepare("select issue_ed, number, volume, issue_length, year, issue_cover
						    from issue where issue_id = ?");
	$iinfo->bind_param("i", $issue_id);
	$iinfo->execute();
	$iinfo->bind_result($issue_ed, $number, $volume, $issue_length, $year, $issue_cover);
	$iinfo->store_result();
	$iinfo->fetch();
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $pubtitle . ' - Edit Cover: '.$issue_ed; ?></title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
	<link rel="stylesheet" type="text/css" href../css/custom.css" />

</head>
<body>
<?php						

	require_once('includes/loggedin.php');	  
	include_once('includes/admintop.php');		
	
	//if form is submitted
	if (isset($_POST['submit'])) {
		if (!empty($_FILES['issue_cover']['name'])) {
	
		// get image variables to upload
		
			//directory location
			$dir = '../images/';
			
			//the path of the file being uploaded
			$file = $dir . basename($_FILES['issue_cover']['name']);
		
			//variable to hold the file extension
			$filetype = pathinfo($file,PATHINFO_EXTENSION);	
			
			
			//check to make sure file is an image
			
			if (getimagesize($_FILES["issue_cover"]["tmp_name"]) == false) {
				
				echo 'This file is not an image';
				exit();
			
			}
			
			//check file size, make sure it is not larger than 500kb (way too large in itself, will reduce at some point)
			else if ($_FILES["issue_cover"]["size"] > 500000) {
				echo "Sorry, your file is too large.";
				exit();
			} 		
			
			else if (move_uploaded_file($_FILES["issue_cover"]["tmp_name"], $file)) {

				echo 'The file <a href="'.$file.'">'. basename($_FILES["issue_cover"]["name"]). '</a> has been uploaded.';
				$issue_cover = $_FILES['issue_cover']['name'];
				
				//set the update variable here to indicate whether or not to update the pdf data in the database
				$update = 1;
			}
			
			//or return error
			else {
				echo "Sorry, there was a problem uploading your file.";
				echo $_FILES['issue_cover']['error'];
				exit();
			}		
		
			//update the database 

			
			if ($update) {

				$isi = $dbc->prepare("UPDATE issue 
									  SET issue_cover = ? 
									  WHERE issue_id = ?");
				$isi->bind_param("si",  $issue_cover,  $issue_id);
				$isi->execute();
				$isi->close();
			}


			header('Location: issue_admin.php?issue_id='.$issue_id);	
			exit();
		}	
	}
	if (isset($_POST['cancel'])) {

		header('Location: issue_admin.php?issue_id='.$issue_id);	
	}

echo 'Volume: ' . $volume . ' Issue: ' . $number . ' Year: ' . $year . ' Length: '. $issue_length .'<p/>';
?>
<div class="main">
<?php
if (!empty($issue_cover)) {

	echo '<img src="../images/'.$issue_cover.'" id="cover" />';
}	
	?>
	
	
<form method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'].'?issue_id='.$issue_id.'&issue_cover='.$issue_cover; ?>">
	<fieldset>
	<legend>Edit Cover Image</legend>


		<span class="header">Current Cover Image file: </span></span><?php if (isset($issue_cover)) { echo $issue_cover; } else { echo 'None'; } ?> <p/>
		Upload a new image (.png or .jpg): 
		<input type="file" id="issue_cover" name="issue_cover" /> 
	<p/>
	<input type="submit" name="submit" value="Update Cover" /> <input type="submit" name="cancel" value="Cancel" />



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