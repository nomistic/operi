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
	$issue_id = $_GET['issue_id'];
	
	//echo $issue_id;
	
	$issue = $dbc->prepare("select i.issue_ed, i.year, i.volume, i.number, i.issue_length, i.issue_cover, a.rights
							from issue i
							left join article a
							on a.issue_id = i.issue_id
							where i.issue_id = ?
							and a.type_code = 'CO'");
	$issue->bind_param("i",$issue_id);
	$issue->execute();
	$issue->bind_result($issue_ed, $year, $volume, $number, $issue_length, $issue_cover, $rights);
	$issue->store_result();
	$issue->fetch();
	
	
	$headers =  $dbc->prepare("select distinct h.header_id, h.header_name
								from header h
								join article a
								on h.header_id = a.header_id
								join type t
								on a.type_code = t.type_code
								where a.header_id = h.header_id
								and a.issue_id = ?
								and t.display = 1
								order by a.order_in_issue");
	$headers->bind_param("i",$issue_id);
	$headers->execute();
	$headers->bind_result($header_id, $header_name);
	$headers->store_result();

?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $pubtitle . ' - '. $issue_ed; ?></title>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js">
</script>

<link rel="stylesheet" type="text/css" href="css/digpub.css" />
<link rel="stylesheet" type="text/css" href="css/custom.css" />


</head>
<body>
	<div class="content">
<?php  include_once('includes/docheader.php'); ?>
<?php


		echo '<div class="main">';
		
		if (!empty($issue_cover)) {

			echo '<div class="cover"><img src="images/'.$issue_cover.'" id="cover" /><br/><span id="cap">'.$rights.'</span></div>';
		}				
		//edit this row to be FSC Review when we figure out the correct date
		
		
/*		if ($year < 2010) {
			echo '<div class="issue"><h4>FSC Review - ';
		}
		else {
			echo '<div class="issue"><h4>Falconer - ';
		}
*/

// now figure out out to get this dynamically

		$isyear = $dbc->query("select title_id, title, ystart, yend from pub");
	//	$isyear->bind_result($title_id, $title, $ystart, $yend);
	//	$isyear->execute();
	//	$isyear->close();
		
		while ($row = $isyear->fetch_assoc()) {
			
			if (($year >= $row['ystart']) && ($year <= $row['yend'])  ) {
				
				
				echo '<h3>' . $row['title'] . '</h3>';
					
				
			}
			
			
			
		}
			
		
		
			
			
	
		
		echo 'V. ' . $volume . ' Issue ' . $number . ', ' . $year . '</h4>';
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
									JOIN type t on
									a.type_code = t.type_code								
									WHERE a.issue_id = ?
									and a.header_id = ?
									and t.display = 1
									order by order_in_issue");
		$articles->bind_param("ii",$issue_id, $header_id);
		$articles->execute();
		$articles->bind_result($article_id, $article_title, $first_nm, $middle, $last_nm, $header_id, $header_name, $order_in_issue, $article_pdf, $issue_id);
		
		while ($articles->fetch())  {
		
		$creator = $first_nm . ' ' .$middle. ' ' . $last_nm;
		echo '<div class="record">';
			echo '<div class="atitle"><a href="article.php?article_id='.$article_id.'">'. $article_title . '</a></div>';
			echo '<div class="data">';
			echo $creator;
			if (isset($article_pdf)) {
				echo '<a href="pdf/'.$article_pdf.'" target="_blank"><span class="download"> Download PDF</span></a>';
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
