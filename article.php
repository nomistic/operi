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

  

	$article_id = $_GET['article_id'];

	$article = $dbc->prepare("SELECT a.article_title, 
							  c.creator_id,
							  c.first_nm, 
							  c.middle,
							  c.last_nm,
							  c.fac_ind,
							  a.article_pdf,
							  t.type_name,
							  a.fic_ind,
							  i.issue_ed,
							  i.issue_id,
							  i.number,
							  i.volume,
							  i.year,
							  a.length,
							  a.arange,
							  a.rights,
							  a.order_in_issue,
							  a.abstract
							  FROM article a
							  JOIN creator c
							  ON a.creator_id = c.creator_id
							  JOIN type t
							  ON a.type_code = t.type_code
							  JOIN issue i
							  ON a.issue_id = i.issue_id
							  WHERE article_id = ?");
	$article->bind_param("i", $article_id);
	$article->execute();
	$article->bind_result($article_title, $creator_id, $first_nm, $middle, $last_nm, $fac_ind, $article_pdf, $type_name, $fic_ind, $issue_ed, $issue_id, $number, $volume, $year, $length, $arange, $rights, $order_in_issue, $abstract);
	$article->fetch();
	$article->close();
	
	$per = $dbc->prepare("select s.subject_id, s.subject_name
							from subject s
							join article_subject sa
							on s.subject_id = sa.subject_id
							join article a
							on a.article_id = sa.article_id
							where a.article_id = ?
							and s.type_code = 'PE'");
	$per->bind_param("i",$article_id);
	$per->execute();
	$per->bind_result($subject_id, $subject_name);
	$per->store_result();

?>
<!DOCTYPE html>
	<html>
	<head>
		<title><?php echo $pubtitle. ' - ' .$article_title . ' - ' .$first_nm. ' '.$middle.' '.$last_nm;  ?></title>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<link rel="stylesheet" type="text/css" href="css/digpub.css" />
	<link rel="stylesheet" type="text/css" href="css/custom.css" />
	</head>
	<body>

<?php 	include_once('includes/docheader.php');  ?>
		<div class="content">
			<div class="metadata">
<?php

		echo '<h4>'.$article_title.' - <a href="issue.php?issue_id='.$issue_id.'">' . $issue_ed . '</a></h4>';

		echo '<div id="table">';
			echo '<div class="row">';
				echo '<span class="leftcell"> Author: </span>';
				echo '<span class="rightcell"><a href="csearch.php?creator_id='.$creator_id.'">' .$first_nm. ' '.$middle.' '.$last_nm. '</a></span>';
			echo '</div>';
			echo '<div class="row">';
				echo '<span class="leftcell"> Content Type: </span>';
				echo '<span class="rightcell">' .$type_name. '</span>';
			echo '</div>';
			echo '<div class="row">';
				echo '<span class="leftcell"> Year: </span>';
				echo '<span class="rightcell">' .$year. '</span>';
			echo '</div>';						
			echo '<div class="row">';
				echo '<span class="leftcell"> Volume: </span>';
				echo '<span class="rightcell">' .$volume. '</span>';
			echo '</div>';					
			echo '<div class="row">';
				echo '<span class="leftcell"> Number: </span>';
				echo '<span class="rightcell">' .$number. '</span>';
			echo '</div>';			
			echo '<div class="row">';
				echo '<span class="leftcell"> Length: </span>';
				echo '<span class="rightcell">' .$length. ' pp.</span>';
			echo '</div>';	
			echo '<div class="row">';
				echo '<span class="leftcell"> Page Range: </span>';
				echo '<span class="rightcell">'.$arange.'</span>';
			echo '</div><p/>';				
		if ($per->num_rows > 0) {
			echo '<div class="row">';
				echo '<span class="leftcell">People: </span>';
				echo '<span class="rightcell">';
					while ($per->fetch()) {
					  
								echo '<a href="subsearch.php?subject_id='.$subject_id.'">'.$subject_name . '</a><br/>';								
						}		
					$per->close();		
		
			echo '</span>';
			echo '</div><p/>';
		}
	$subj = $dbc->prepare("select s.subject_id, s.subject_name
							from subject s
							join article_subject sa
							on s.subject_id = sa.subject_id
							join article a
							on a.article_id = sa.article_id
							where a.article_id = ?
							and s.type_code= 'RG'");
	$subj->bind_param("i",$article_id);
	$subj->execute();
	$subj->bind_result($subject_id, $subject_name);  
	$subj->store_result();
	if ($subj->num_rows > 0) {
			echo '<div class="row">';
				echo '<span class="leftcell">Subjects: </span>';
				echo '<span class="rightcell">' ; 
				


				
					while ($subj->fetch()) {
					//  $subs[] = $subj;
					  
								echo '<a href="subsearch.php?subject_id='.$subject_id.'">'.$subject_name . '</a><br/>';									
						}		
					$subj->close();
					
			echo '</span>';		
			echo '</div>';	
	}
	if (!empty($abstract) && trim($abstract) != '') {
		echo '<div class="row">';
			echo '<span class="leftcell"> Abstract: </span>';
			echo '<span class="rightcell">' .$abstract. '</span>';
		echo '</div><p/>';	
	}
	echo '</div>';		
	

//get next issue

	$next = $dbc->prepare("SELECT a.article_id
							FROM article a
							JOIN type t
							ON a.type_code = t.type_code
							WHERE a.issue_id = ?
							AND t.display = 1
							AND a.order_in_issue =
							(SELECT min(order_in_issue) 
							FROM article a
							JOIN type t
							ON a.type_code = t.type_code
							WHERE a.issue_id = ?
							AND t.display = 1
							AND  a.order_in_issue > ?)
							order by a.order_in_issue");
	$next->bind_param("iii",$issue_id, $issue_id, $order_in_issue);
	$next->execute();
	$next->bind_result($article_next);
	$next->store_result();
	$numn = $next->num_rows;
	$next->fetch();
	$next->free_result();
	$next->close();

//get previous issue

	$prev = $dbc->prepare("SELECT a.article_id
							FROM article a
							JOIN type t
							ON a.type_code = t.type_code
							WHERE a.issue_id = ?
							AND a.order_in_issue =
							(SELECT max(order_in_issue) 
							FROM article a
							JOIN type t
							ON a.type_code = t.type_code
							WHERE a.issue_id = ?
							AND t.display = 1
							AND  a.order_in_issue < ?)
							order by a.order_in_issue");
	$prev->bind_param("iii", $issue_id, $issue_id,  $order_in_issue);
	$prev->execute();
	$prev->bind_result($article_prev);
	$prev->store_result();
	$nump = $prev->num_rows;
	$prev->fetch(); 
	$prev->free_result();
	$prev->close();

			
		
	
	
	
	
	
					

		echo '<div class="pdf">';
		echo '<div class="prevnext">';
		
		if ($nump > 0) {
			echo '<a href="article.php?article_id='.$article_prev.'">Previous Article</a> |'; 
		}	
		if ($numn > 0) {
		
			echo '| <a href="article.php?article_id='.$article_next.'">Next Article</a>';
		
		}

		echo '</div>';

				echo '<span class="cite"><a href="cite.php?article_id='.$article_id.'"';
				?>
				 onclick="positionedPopup(this.href,'myWindow','700','150','100','200','yes');return false"
				<?php
				
				echo '><span class="cite">MLA Citation</span></a>';
			if (isset($article_pdf) && trim($article_pdf) != '') {	
				echo '<a href="pdf/'.$article_pdf.'" target="_blank"><span class="download"> Download PDF</span></a>';

				echo '<object data="pdf/'.$article_pdf.'" type="application/pdf" width="800px" height="800px">';
				echo '<p>It appears you don\'t have a PDF plugin for this browser. However, you can <a href="pdf/'.$article_pdf.'">click here to download the PDF file.</a></p>';
				echo '</object>';	
			}			
									
		echo '</div>';

		
		
		
?>
			</div>
		</div>
	
	<script language="javascript">
	var popupWindow = null;
	function positionedPopup(url,winName,w,h,t,l,scroll){
	settings =
	'height='+h+',width='+w+',top='+t+',left='+l+',scrollbars='+scroll+',resizable'
	popupWindow = window.open(url,winName,settings)
	}
	</script>	
	</body>


</html>	

