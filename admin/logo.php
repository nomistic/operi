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

//	$issue_id = $_GET['issue_id'];

    $iinfo = $dbc->prepare("select logo
						    from site_data");
	$iinfo->execute();
	$iinfo->bind_result($logo);
	$iinfo->store_result();
	$iinfo->fetch();
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $pubtitle . ' - Edit Logo'; ?></title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
	<link rel="stylesheet" type="text/css" href../css/custom.css" />

</head>
<body>
<?php						

	require_once('includes/loggedin.php');	  
	include_once('includes/admintop.php');		
	
	//if form is submitted
	if (isset($_POST['submit'])) {
		
		//check to make sure a change has been made
		if (!empty($_FILES['logo']['name'])) {	

		
		// get image variables to upload
		
			//directory location
			$dir = '../images/';
			
			//the path of the file being uploaded
			$file = $dir . basename($_FILES['logo']['name']);
		
			//variable to hold the file extension
			$filetype = pathinfo($file,PATHINFO_EXTENSION);	
			
			
			//check to make sure file is an image
			
			if (getimagesize($_FILES["logo"]["tmp_name"]) == false) {
				
				echo 'This file is not an image';
				exit();
			
			}
			
			//check file size, make sure it is not larger than 500kb (way too large in itself, will reduce at some point)
			else if ($_FILES["logo"]["size"] > 500000) {
				echo "Sorry, your file is too large.";
				exit();
			} 		
			
			else if (move_uploaded_file($_FILES["logo"]["tmp_name"], $file)) {

				echo 'The file <a href="'.$file.'">'. basename($_FILES["logo"]["name"]). '</a> has been uploaded.';
				$logo = $_FILES['logo']['name'];
				
				//set the update variable here to indicate whether or not to update the pdf data in the database
				$update = 1;
			}
			
			//or return error
			else {
				echo "Sorry, there was a problem uploading your file.";
				echo $_FILES['logo']['error'];
				exit();
			}		
		

			
			if ($update) {

				$isi = $dbc->prepare("UPDATE site_data 
									  SET logo = ?");
				$isi->bind_param("s",  $logo);
				$isi->execute();
				$isi->close();
			}


			header('Location: site.php');	
			exit();
		}	
	}
	if (isset($_POST['cancel'])) {

		header('Location: site.php');	
	}


if (!empty($logo)) {

	echo '<img src="../images/'.$logo.'" id="logo" />';
}	
	?>
	
	
<form method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
	<legend>Edit Cover Image</legend>


		<span class="header">Current Cover Image file: </span></span><?php if (isset($logo)) { echo $logo; } else { echo 'None'; } ?> <p/>
		Upload a new image (.png or .jpg): 
		<input type="file" id="logo" name="logo" /> 
	<p/>
	<input type="submit" name="submit" value="Update Logo" /> <input type="submit" name="cancel" value="Cancel" />



	</fieldset>
</form>

<?php
	$iinfo->free_result();
	$iinfo->close();
 //mysqli_close($dbc);
?>
</body>
</html>