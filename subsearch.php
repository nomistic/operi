<?php

// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  Person                                                               *
//                                                                              *
// ******************************************************************************



	require_once('includes/connect.php');
	include_once('includes/pub.php');

	$subject_id = $_GET['subject_id'];
	
	$subject = $dbc->prepare("select subject_name from subject where subject_id= ?");
	$subject->bind_param("i", $subject_id);
	$subject->execute();
	$subject->bind_result($subject_name);
	$subject->fetch();
	$subject->close();
	
	$articles = $dbc->prepare("select a.article_id, a.article_title, c.first_nm, c.middle, c.last_nm, i.issue_ed
								from article a
								join creator c
								on a.creator_id = c.creator_id
								join issue i
								on a.issue_id = i.issue_id
								join article_subject sa
								on a.article_id = sa.article_id
								join subject s
								on s.subject_id = sa.subject_id
								where s.subject_id = ?
								and i.pub_ind = 1");
	$articles->bind_param("i", $subject_id);
	$articles->execute();
	$articles->bind_result($article_id, $article_title, $first_nm, $middle, $last_nm, $issue_ed);

?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $pubtitle. ' - Articles on subject: '. $subject_name;  ?> </title>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="css/digpub.css" />
<link rel="stylesheet" type="text/css" href="css/custom.css" />
</head>
<body>

<?php
	include_once('includes/docheader.php'); 	
	echo '<h4>Subject: '.$subject_name.'</h4>';
	while ($articles->fetch()) {

		$creator = $first_nm . ' ' .$middle. ' ' . $last_nm;
			echo '<div class="record">';
			echo '<div class="atitle"><a href="article.php?article_id='.$article_id.'">'. $article_title . '</a></div>';
			echo '<div class="data">';
			echo $creator . ' - '. $issue_ed;
			if (isset($article_pdf)) {
				echo ' <a href="pdf/'.$article_pdf.'" target="_blank">Download PDF</a>';
			}	
			echo '</div>';
		echo '</div>';
	
	}
							
	
?>


</body>
</html>