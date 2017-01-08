<?php
// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  Personal                                                           *
//                                                                              *
// ******************************************************************************

	require_once('includes/connect.php');
	include_once('includes/pub.php');


	$creator_id = $_GET['creator_id'];
	
	$creator = $dbc->prepare("select first_nm, middle, last_nm from creator where creator_id= ?");
	$creator->bind_param("i", $creator_id);
	$creator->execute();
	$creator->bind_result($first_nm, $middle, $last_nm);
	$creator->fetch();
	$creator->close();
	$creator_name = $first_nm. ' '. $middle. ' '. $last_nm;

	$articles = $dbc->prepare("select a.article_id, a.article_title, i.issue_ed
								from article a								
								join creator c
								on a.creator_id = c.creator_id
								join issue i
								on a.issue_id = i.issue_id
								where a.creator_id = ?
								and i.pub_ind = 1");
	$articles->bind_param("i", $creator_id);
	$articles->execute();
	$articles->bind_result($article_id, $article_title, $issue_ed);

?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $pubtitle. ' - Articles by '. $creator_name;  ?> </title>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="css/digpub.css" />
<link rel="stylesheet" type="text/css" href="css/custom.css" />
</head>
<body>


<?php
	include_once('includes/docheader.php'); 
	echo '<h4>All articles by '.$creator_name.'</h4>';	
	while ($articles->fetch()) {

			echo '<div class="record">';
			echo '<div class="atitle"><a href="article.php?article_id='.$article_id.'">'. $article_title . '</a> </div>';
			echo '<div class="data">';
			echo $creator_name. ' - '. $issue_ed;
			if (isset($article_pdf)) {
				echo ' <a href="pdf/'.$article_pdf.'" target="_blank">Download PDF</a>';
			}	
			echo '</div>';
		echo '</div>';
	
	}
							
	
?>


</body>
</html>