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

	//get article variable from URL
	$article_id = $_GET['article_id'];
	
	//sanitize it and get the rest of the variables from tables
	$adata = $dbc->prepare("select article_title, 
							creator_id, 
							header_id, 
							article_pdf, 
							type_code, 
							fic_ind, 
							issue_id, 
							order_in_issue, 
							length,
							arange,
							rights,
							abstract
							from article
							where article_id = ?");
	$adata->bind_param("i",$article_id);
	$adata->execute();
	$adata->store_result();
	
	//create new variables
	$adata->bind_result($article_title,$creator_id,$header_id,$article_pdf,$type_code,$fic_ind,$issue_id,$order_in_issue,$length,$arange, $rights, $abstract);
	$adata->fetch();
	
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit <?php echo $article_title; ?> </title>

<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
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
</head>
<body>
<?php	
	require_once('includes/loggedin.php');
	include_once('includes/admintop.php');

	
	if (isset($_POST['submit'])) {
	

		//file upload variables
		//directory location
		$dir = '../pdf/';


		//the path of the file being uploaded
		$file = $dir . basename($_FILES['article_pdf']['name']);
	
		
		//variable to hold the file extension
		$filetype = pathinfo($file,PATHINFO_EXTENSION);	

	
//instead of checking file extension check mime types
	if (!empty($_FILES['article_pdf']['tmp_name'])) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $_FILES['article_pdf']['tmp_name']);
			if ($mime != 'application/pdf') {
			
				echo 'this is not a PDF file!';
				exit();
			}
		
		
		else {
		
	
			//if it has been uploaded and moved to the correct location, state this.
			if (move_uploaded_file($_FILES["article_pdf"]["tmp_name"], $file)) {

				echo 'The file <a href="'.$file.'">'. basename($_FILES["article_pdf"]["name"]). '</a> has been uploaded.';
				$article_pdf = $_FILES['article_pdf']['name'];
				
				//set the update variable here to indicate whether or not to update the pdf data in the database
				$update = 1;
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
		$rights = $_POST['rights'];
		$abstract = $_POST['abstract'];
	
		
		//leaving out these fields that exist on tables; consider adding later:  abstract, article_text.  article_pdf may be added later?
		
		// update the database with all values except for pdf
		$ari = $dbc->prepare("UPDATE article 
							 SET article_title = ?,
							 creator_id = ?,
							 header_id = ?,  
							 type_code = ?,
							 fic_ind = ?,
							 order_in_issue =?, 
							 issue_id = ?,
							 length = ?,
							 arange = ?,
							 rights = ?,
							 abstract = ?
							 where article_id = ?");
		
		
		// the following three if/then statements deal with error handling;  with prepared statements they can fail at any point so each needs to be checked.  (in this case I had a problem at the execution phase, with a database error)
		if (false===$ari) {
			 die('prepare() failed: ' . htmlspecialchars($dbc->error));
		}
		$bp = $ari->bind_param("siissiiisssi", $article_title, $creator_id, $header_id, $type_code, $fic_ind, $order_in_issue, $issue_id, $length, $arange, $rights, $abstract, $article_id);
		if (false===$ari) {
			die('bind_param() failed: ' . htmlspecialchars($ari->error));
		}
		$bp = $ari->execute();
		
		if ( false===$bp ) {
		  die('execute() failed: ' . htmlspecialchars($ari->error));
		}		
		$ari->close();
		
		//if there was a new pdf upload, update the reference to it in the database.
		
		if ($update)  {
			$updf = $dbc->prepare("update article
								   set article_pdf = ?
								   where article_id = ?");
			$updf->bind_param("si", $article_pdf, $article_id);
			$updf->execute();
			$updf->close();
		}
		
		header('Location: article_admin.php?article_id='.$article_id);

		
		exit();
		
	}


// create the form and prepopulate it with existing data

?>

<form method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] . '?article_id='.$article_id; ?>">
	<fieldset><legend>Edit <?php echo $article_title; ?></legend>
	Issue: <br />

		<select name = "issue_id" >
	
			<?php  
			$d = $dbc->query('select i.issue_id, i.issue_ed
								from issue i
								order by issue_ed, number');
			while ($row = $d->fetch_assoc()) {
				if ($row['issue_id'] == $issue_id) {
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
				if ($row['header_id'] == $header_id) {
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
				if ($row['creator_id'] == $creator_id) {
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

	<input type="text" name="article_title" size="100" value="<?php echo htmlspecialchars($article_title);  ?>"/><p/>
	
	
	Article Type:<br/> <!-- option box and input-->
	
			<select name = "type_code" >

			<?php  
			$d = $dbc->query('select type_code, type_name from type order by type_name');
			while ($row = $d->fetch_assoc()) {
				if ($row['type_code'] == $type_code) {
				$selected = 'selected="selected"';
				}
				else {
				$selected = '';
				}		
	
				echo '<option value ="' . $row['type_code'] . '" ' .  $selected . '>'. $row['type_name'] . '</option>' ;
			}	
					
			?>

			</select>  			<p/>
	
		
		Fiction?    <input type="checkbox" name="fic_ind" <?php  if ($fic_ind == 'Y') {echo 'checked';}  ?> />  <p/>
		
		Order in Issue:<br/>
		<input type="text" name="order_in_issue" id="order_in_issue" value="<?php echo $order_in_issue; ?>" /><p/>		
		
		Length:<br/>
		<input type="text" name="length" id="length" value="<?php echo $length; ?>" /><p/>
		Page Range:<br/>
		<input type="text" name="arange" id="arange" value="<?php echo htmlspecialchars($arange); ?>" /><p/>		
		Rights:<br/>
		<textarea name="rights" id="rights" rows="10" cols="40" /><?php echo htmlspecialchars($rights); ?></textarea><p/>
		Abstract:<br/>
		<textarea name="abstract" id="abstract" rows="10" cols="40" /><?php echo htmlspecialchars($abstract); ?></textarea><p/>		
		<span class="header">Current PDF file: </span></span><?php if (isset($article_pdf)) { echo $article_pdf; } else { echo 'None'; } ?> <p/>
		Upload a new pdf: 
		<input type="file" id="article_pdf" name="article_pdf" /> 
		<p/>			

		<input type="submit" name="submit" id="submit" value="Update article" />
		
		
		

	</fieldset>
</form>

<?php


// free the data and close the connections
$adata->free_result();
$adata->close();

mysqli_close($dbc);

?>

</body>
</html>
