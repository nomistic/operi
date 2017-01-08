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

$issue_id = $_GET['issue_id'];
$article_id = $_GET['article_id'];

	if ((!isset($_SESSION['user_id'])) || ($_SESSION['admin'] != 1)) {
		echo 'You do not have access to this page';
		exit();
	  }
	  
	//identify current user from session id

	
	if (isset($_POST['delete'])) {
		
		$dsa = $dbc->prepare("DELETE FROM article_subject WHERE article_id = ?");
		$dsa->bind_param("i",$article_id);
		$dsa->execute();
		$dsa->close();
	
		$da = $dbc->prepare("DELETE FROM article WHERE article_id = ?");				
		$da->bind_param("i", $article_id);
		$da->execute();
		$da->close();	
	
		header('Location: issue_admin.php?issue_id='.$issue_id);
	
	}
	elseif (isset($_POST['cancel'])) {
	
			header('Location: issue_admin.php?issue_id='.$issue_id);
	}
	
	else {
	
		$art = $dbc->prepare("select article_title from article where article_id= ?");
		$art->bind_param("i", $article_id);
		$art->execute();
		$art->store_result();
		$art->bind_result($article_title);
		$art->fetch();
		
?>
<html>
<head>
<title>Delete <?php echo $article_title; ?> </title>

	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
<link rel="stylesheet" type="text/css" href="../css/custom.css" />
</head>
<body>

<?php
	require_once('includes/loggedin.php');
	include_once('includes/admintop.php');
		

				echo '<div class="error"><p/>Are you sure you want to delete <em>' . $article_title .'?</em> <p /> This cannot be undone.<br /><br /></div>';
				

			
?>			

<?php			
		$art->free_result();
		$art->close();		
	}
	
?>
		
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?article_id='. $article_id . '&issue_id=' . $issue_id; ?>">

				<input type="submit" name="delete" value="Delete" /> <input type="submit" name="cancel" value="Cancel" />

			</form>	
</body>
</html>			