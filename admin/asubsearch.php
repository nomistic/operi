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
<head><title><?php echo $pubtitle . ' - Subject Search'; ?></title>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
<link rel="stylesheet" type="text/css" href="../css/custom.css" />
</head>
<body>

<?php
	require_once('includes/loggedin.php');
	include_once('includes/admintop.php');

	$subject_id = $_GET['subject_id'];
	
	$subject = $dbc->prepare("select subject_name from subject where subject_id= ?");
	$subject->bind_param("i", $subject_id);
	$subject->execute();
	$subject->bind_result($subject_name);
	$subject->fetch();
	$subject->close();
	echo '<h4>Subject: '.$subject_name.'</h4>';
	$articles = $dbc->prepare("select a.article_id, a.article_title, c.first_nm, c.middle, c.last_nm
								from article a
								join creator c
								on a.creator_id = c.creator_id
								join article_subject sa
								on a.article_id = sa.article_id
								join subject s
								on s.subject_id = sa.subject_id
								where s.subject_id = ?");
	$articles->bind_param("i", $subject_id);
	$articles->execute();
	$articles->bind_result($article_id, $article_title, $first_nm, $middle, $last_nm);
	
	while ($articles->fetch()) {

		$creator = $first_nm . ' ' .$middle. ' ' . $last_nm;
			echo '<div class="record">';
			echo '<div class="atitle"><a href="article_admin.php?article_id='.$article_id.'">'. $article_title . '</a></div>';
			echo '<div class="data">';
			echo $creator;
			if (isset($article_pdf)) {
				echo ' <a href="../pdf/'.$article_pdf.'" target="_blank">Download PDF</a>';
			}	
			echo '</div>';
		echo '</div>';
	
	}
	
?>


</body>
</html>