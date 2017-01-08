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

	

			//connect to the database


	$issue_id = $_GET['issue_id'];
	
	//echo $issue_id;
	
	$issue = $dbc->prepare("select issue_ed, year, volume, number, issue_length, issue_cover, pub_ind from issue where issue_id = ?");
	$issue->bind_param("i",$issue_id);
	$issue->execute();
	$issue->bind_result($issue_ed, $year, $volume, $number, $issue_length, $issue_cover, $pub_ind);
	$issue->store_result();
	
	
	$headers =  $dbc->prepare("select distinct h.header_id, h.header_name
								from header h
								join article a
								on h.header_id = a.header_id
								where a.header_id = h.header_id
								and a.issue_id = ?
								order by a.order_in_issue");
	$headers->bind_param("i",$issue_id);
	$headers->execute();
	$headers->bind_result($header_id, $header_name);
	$headers->store_result();
	$issue->fetch();
	
	if ($pub_ind != 1) {
		$published = 'unpublished';
	}
	else {
		$published = 'published';
	}		

?>
<!DOCTYPE html>
<html>
<title><?php echo $pubtitle . ' - Administration: '.$issue_ed; ?></title>
<head>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
	<link rel="stylesheet" type="text/css" href="../css/custom.css" />
</head>
<body>
<?php 
	require_once('includes/loggedin.php');
include_once('includes/admintop.php'); ?>

	<div class="content">

<?php

	

		echo '<div class="main">';
		if (!empty($issue_cover)) {

			echo '<div class="cover"><div id="cover" class="edit"> <a href="iscov.php?issue_id='.$issue_id.'">Edit issue cover image</a><br /> <img src="../images/'.$issue_cover.'" id="cover"/></div></div>';
		}
		else {
			echo '<div class="cover"><div id="cover" class="edit"> <a href="iscov.php?issue_id='.$issue_id.'">Add issue cover image</a><br /></div></div>';
		}
		
		
		
		//get relevant title for issue
		
		$isyear = $dbc->query("select title_id, title, ystart, yend from pub");

		
		while ($row = $isyear->fetch_assoc()) {
			
			if (($year >= $row['ystart']) && ($year <= $row['yend'])  ) {
				
				
				echo '<div class="issue"><h3>' . $row['title'] . '</h3>';
					
				
			}
			
			
			
		}
		echo 'Volume: ' . $volume . ' Issue: ' . $number . ' Year: ' . $year . ' Length: '. $issue_length .' <p/>';
		echo 'Status: <span class="alert">'.$published.'</span><br/>';
		echo '<a href="issed.php?issue_id='.$issue_id.'">Edit issue information</a>  </div>';




		echo '<div class="issue_ed">'.$issue_ed . '</div>';
	$issue->free_result();
	$issue->close();

	while ($headers->fetch()) {
		echo '<div class="headers"><h3>'.$header_name.'</h3></div>';
		
		$articles = $dbc->prepare("SELECT a.article_id, a.article_title, c.first_nm, c.middle, c.last_nm, h.header_id, h.header_name, a.order_in_issue, a.article_pdf, a.issue_id
									FROM article a
									JOIN creator c on 
									a.creator_id = c.creator_id
									JOIN header h on
									a.header_id = h.header_id
									JOIN issue i on
									a.issue_id = i.issue_id
									WHERE a.issue_id = ?
									and a.header_id = ?
									order by order_in_issue");
		$articles->bind_param("ii",$issue_id, $header_id);
		$articles->execute();
		$articles->bind_result($article_id, $article_title, $first_nm, $middle, $last_nm, $header_id, $header_name, $order_in_issue, $article_pdf, $issue_id);
		
		while ($articles->fetch())  {
		
		$creator = $first_nm . ' ' .$middle. ' ' . $last_nm;
		echo '<div class="record">';
			echo '<div class="atitle"><span class="order">' .$order_in_issue.'</span> <a href="article_admin.php?article_id='.$article_id.'">'. $article_title . '</a> </div>';
			echo '<div class="data">';
			echo $creator;
			if (isset($article_pdf)) {
				echo '<a href="../pdf/'.$article_pdf.'" target="_blank"><span class="download"> Download PDF</span></a>';
			}	
			echo '</div>';
		echo '</div>';
		

		} 
			
		$articles->free_result();
		$articles->close();
	
	}	
	$headers->free_result();
	$headers->close();
	
?>
		</div>
	</div>
</body>
</html>
