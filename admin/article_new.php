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

?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $pubtitle; ?> - Add New Article</title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
	<link rel="stylesheet" type="text/css" href="../css/custom.css" />

<script language="javascript">
var popupWindow = null;
function positionedPopup(url,winName,w,h,t,l,scroll){
settings =
'height='+h+',width='+w+',top='+t+',left='+l+',scrollbars='+scroll+',resizable'
	popupWindow = window.open(url,winName,settings)
	}
	</script>


<script>
function validateForm() {
    var x = document.forms["addarticle"]["article_title"].value;
    if (x == null || x == "") {
        alert("You must enter article title");
        return false;
    }
}
</script>
</head>
<body>

<?php
	require_once('includes/loggedin.php');
	include_once('includes/admintop.php');
	//if form is submitted
	if (isset($_POST['submit'])) {
	

		//file upload variables
		//directory location
		$dir = '../pdf/';


		//the path of the file being uploaded
		$file = $dir . basename($_FILES['article_pdf']['name']);
		//$upload = 1;
		
		//variable to hold the file extension
		$filetype = pathinfo($file,PATHINFO_EXTENSION);	



	//file uploads
	
//instead of checking file extension check mime types
		if (!empty($_FILES['article_pdf']['tmp_name'])) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $_FILES['article_pdf']['tmp_name']);
				if ($mime != 'application/pdf') {
				
					echo 'this is not a PDF file!';
					exit();
				}
			
			
			else {
			
				// Check if file already exists.  
				
				if (file_exists($file)) {
					echo "Sorry, file already exists.";
					exit();
				}
			
				//if it has been uploaded and moved to the correct location, state this.
				if (move_uploaded_file($_FILES["article_pdf"]["tmp_name"], $file)) {

					echo 'The file <a href="'.$file.'">'. basename($_FILES["article_pdf"]["name"]). '</a> has been uploaded.';
					
					$article_pdf = $_FILES['article_pdf']['name'];
				}
				
				//or return error
				else {
					echo "Sorry, there was a problem uploading your file.";
					echo $_FILES['article_pdf']['error'];
					exit();
				}		
			
			}
		}
		

		//database entries	
			//create variable for database entry

		//article variables
		$article_title = $_POST['article_title'];
		$creator_id = $_POST['creator_id'];
		$header_id = $_POST['header_id'];
		$type_code = $_POST['type_code'];
		$issue_id = $_POST['issue_id'];
		if (isset($_POST['fic_ind'])) {
			$fic_ind = 'Y';
		}
		else {
			$fic_ind = 'N';
		}
		$order_in_issue = $_POST['order_in_issue'];
		$length = $_POST['length'];
		$arange = $_POST['arange'];
		$abstract = $_POST['abstract'];
		$rights = $_POST['rights'];
	
		
		
		// insert into article
		$ari = $dbc->prepare("INSERT INTO article (article_id, article_title, creator_id, abstract, article_pdf, header_id,  type_code, fic_ind, order_in_issue, issue_id, length, arange, rights) VALUES (NULL, ?, ?,?,?,?,?,?,?,?,?,?,?)");
		
		
		// the following three if/then statements deal with error handling;  with prepared statements they can fail at any point so each needs to be checked.  
		if (false===$ari) {
			 die('prepare() failed: ' . htmlspecialchars($dbc->error));
		}
		$bp = $ari->bind_param("sississiiiss", $article_title, $creator_id, $abstract, $article_pdf, $header_id, $type_code, $fic_ind, $order_in_issue, $issue_id, $length, $arange, $rights);
		if (false===$ari) {
			die('bind_param() failed: ' . htmlspecialchars($ari->error));
		}
		$bp = $ari->execute();
		
		if ( false===$bp ) {
		  die('execute() failed: ' . htmlspecialchars($ari->error));
		}		
		$ari->close();
		
		
		echo 'submitted.  <a href="article_new.php">Enter another?</a><br/>';
		
		header('Location: issue_admin.php?issue_id='.$issue_id);

		
		exit();
		
	}

?>
<div class="main">
<form name="addarticle" onsubmit="return validateForm()" method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
<fieldset><legend>Add a new article</legend>



	Issue: <br />

		<select name = "issue_id" >

			<?php  
			$d = $dbc->query('select issue_id, issue_ed, number from issue order by issue_id, number');
			while ($row = $d->fetch_assoc()) {
				if ($row[0] == $issue_id) {
				$selected = 'selected="selected"';
				}
				else {
				$selected = '';
				}		
	
				echo '<option value ="' . $row['issue_id'] .'" ' .  $selected . '>'. $row['issue_ed'] . '</option>' ;
			}	
					
			?>
		</select> &nbsp;&nbsp;<a href="issue_new.php" onclick="positionedPopup(this.href,'myWindow','300','350','100','200','yes');return false">Add new issue</a><p/>


	Choose Table of Contents Header:<br/> <!-- option box and input-->
	
			<select name = "header_id" >

			<?php  
			$d = $dbc->query('select header_id, header_name from header order by header_name');
			while ($row = $d->fetch_assoc()) {
				if ($row[0] == $header_id) {
				$selected = 'selected="selected"';
				}
				else {
				$selected = '';
				}		
	
				echo '<option value ="' . $row['header_id'] . '" ' .  $selected . '>'. $row['header_name'] . '</option>' ;
			}	
					
			?>
		</select>  &nbsp;&nbsp;<a href="header.php" onclick="positionedPopup(this.href,'myWindow','300','350','100','200','yes');return false">Add new header</a><p/>


	Choose Creator:<br/> <!-- option box and input-->
	
			<select name = "creator_id" >

			<?php  
			$d = $dbc->query('select creator_id, first_nm, middle, last_nm from creator order by last_nm');
			while ($row = $d->fetch_assoc()) {
				if ($row[0] == $creator_id) {
				$selected = 'selected="selected"';
				}
				else {
				$selected = '';
				}		
	
				echo '<option value ="' . $row['creator_id'] . '" ' .  $selected . '>'. $row['last_nm'] . ', '. $row['first_nm'] . ' '. $row['middle'] . '</option>' ;
			}	
					
			?>
		</select>  &nbsp;&nbsp;<a href="creator.php" onclick="positionedPopup(this.href,'myWindow','300','350','100','200','yes');return false">Add new creator</a><p/>


	Article Title:<br/>

	<input type="text" name="article_title" size="100" placeholder="Enter the article title" /><p/>
	
	
	Article Type:<br/> <!-- option box and input-->
	
			<select name = "type_code" >

			<?php  
			$d = $dbc->query('select type_code, type_name from type order by type_name');
			while ($row = $d->fetch_assoc()) {
				if ($row[0] == $type_code) {
				$selected = 'selected="selected"';
				}
				else {
				$selected = '';
				}		
	
				echo '<option value ="' . $row['type_code'] . '" ' .  $selected . '>'. $row['type_name'] . '</option>' ;
			}	
					
			?>

			</select>  			<p/>
	
		
		Fiction?    <input type="checkbox" name="fic_ind" />  <p/>
		Order in Issue:<br/>
		<input type="text" name="order_in_issue" id="order_in_issue" placeholder="Enter a number"/><p/>		
		
		Length:<br/>
		<input type="text" name="length" id="length" placeholder="The number of pages" /><p/>
		
		Page Range:<br/>
		<input type="text" name="arange" id="arange" placeholder="Page Range" /><p/>
		Abstract<br/>
		<textarea name="abstract" id="abstract" rows="10" cols="40" placeholder="Abstract (HTML allowed)" /></textarea><p/>
		
		Rights:<br/>
		<textarea name="rights" id="rights" rows="10" cols="40" placeholder="Enter any rights restrictions" /></textarea><p/>
		
		Upload a pdf: 
		<input type="file" id="article_pdf" name="article_pdf" />
		<p/>			

		<input type="submit" name="submit" id="submit" value="Enter new article" />

	</fieldset>
</form>

<?php

mysqli_close($dbc);

?>
</div>
</body>
</html>
