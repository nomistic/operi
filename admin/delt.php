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



$type_code = $_GET['type_code'];

	if ((!isset($_SESSION['user_id'])) || ($_SESSION['admin'] != 1)) {
		echo 'You do not have access to this page';
		exit();
	  }
	  
	
	if (isset($_POST['delete'])) {
	
		$delt = $dbc->prepare("Delete FROM type where type_code = ?");				
		$delt->bind_param("s", $type_code);
		$delt->execute();
		$delt->close();	  
	
		header('Location: ' . 'types.php'); 
	
	}
	elseif (isset($_POST['cancel'])) {
	
			header('Location: ' . 'types.php');
	}
	
	else {
	
		$type = $dbc->prepare("select t.type_name, a.article_id, a.article_title, i.issue_ed 
								from type t
								left join article a
								on a.type_code = t.type_code
								left join issue i
								on a.issue_id = i.issue_id
								where t.type_code = ?");

		$type->bind_param("s", $type_code);
		$type->execute();
		$type->store_result();
		$num = $type->num_rows;
		$type->bind_result($type_name, $article_id, $article_title, $issue_ed);
		
	$display = '';

?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $pubtitle; ?> - Delete Type</title>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
<link rel="stylesheet" type="text/css" href="../css/custom.css" />
</head>
<body>

<?php

	require_once('includes/loggedin.php');
	include_once('includes/admintop.php');
	while ($type->fetch()) {


		

			if (($num != 0) && ($article_id != null)) {
				
				echo '<div class="alert"><p/>'. $type_name . ' is associated with ' . $article_title . '('.$issue_ed.') and cannot be deleted. </div>  ';
				

			}		
			else {
				echo '<div class="alert"><p/>Are you sure you want to delete ' . $type_name .'? <p /> This cannot be undone.<br /><br /></div>';
				$display = 'Y';
			
			}
				
		
	}		
?>			

<?php			


		if ($display=='Y') {
?>

			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?type_code=' . $type_code; ?>">

				<input type="submit" name="delete" value="Delete" /> <input type="submit" name="cancel" value="Cancel" />

			</form>

<?php 
		} 


		$type->free_result();
		$type->close();		
	}
?>			
</body>
</html>			