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

	$type_code = $_GET['type_code'];
	
	$type = $dbc->prepare("select type_name from type where type_code =?");
	$type->bind_param("s", $type_code);
	$type->execute();
	$type->bind_result($type_name);
	$type->fetch();
	$type->close();
	

	$articles = $dbc->prepare("select a.article_id, a.article_title, i.issue_ed, c.first_nm, c.middle, c.last_nm
								from article a								
								join creator c
								on a.creator_id = c.creator_id
								join issue i
								on a.issue_id = i.issue_id
								where a.type_code = ?
								and i.pub_ind = 1
								ORDER BY year desc, volume desc, number  DESC");
	$articles->bind_param("s", $type_code);
	$articles->execute();
	$articles->bind_result($article_id, $article_title, $issue_ed, $first_nm, $middle, $last_nm);
	
  ?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $pubtitle. ' - Article Type - '. $type_name;  ?> </title>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="css/digpub.css" />
<link rel="stylesheet" type="text/css" href="css/custom.css" />
</head>
<body>
 <?php	

 include_once('includes/docheader.php');
	echo '<h4>All articles of type: '.$type_name.' </h4>'; 	
	while ($articles->fetch()) {
			$creator_name = $first_nm. ' '. $middle. ' '. $last_nm;

			echo '<div class="record">';
			echo '<div class="atitle"><a href="article.php?article_id='.$article_id.'">'. $article_title . '</a> </div>';
			echo '<div class="data">';
			echo $creator_name. ' - '. $issue_ed;
			echo '</div>';
		echo '</div>';
	
	}
							
	$articles->close();
?>


</body>
</html>