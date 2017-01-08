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
	$article->bind_result($article_title, $creator_id, $first_nm, $middle, $last_nm, $fac_ind, $article_pdf, $type_name, $fic_ind, $issue_ed, $issue_id, $number, $volume, $year, $length, $arange, $rights, $abstract);
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


?>
<!DOCTYPE html>
	<html>
	<head>
	<title><?php echo $pubtitle. ' - Administration: ' .$article_title . ' - ' .$first_nm. ' '.$middle.' '.$last_nm;  ?></title>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
	<link rel="stylesheet" type="text/css" href="../css/custom.css" />
	</head>
	<body>
<?php
	require_once('includes/loggedin.php');
	include_once('includes/admintop.php');
?>	
		<div class="content">
			<div class="metadata">
<?php

		echo '<h4>'.$article_title.' - <a href="issue_admin.php?issue_id='.$issue_id.'">' . $issue_ed . '</a></h4>';
		echo '<div class="ed"><span class="ed"><a href="aedit.php?article_id='. $article_id .'">Edit</a></span> <span class="ed"><a href="ad.php?article_id='.$article_id.'&issue_id='.$issue_id.'">Delete</a></span></div>';
		echo '<div id="table">';
			echo '<div class="row">';
				echo '<span class="leftcell"> Creator: </span>';
				echo '<span class="rightcell"><a href="acsearch.php?creator_id='.$creator_id.'">' .$first_nm. ' '.$middle.' '.$last_nm. '</a></span>';
			echo '</div>';
			echo '<div class="row">';
				echo '<span class="leftcell"> Type: </span>';
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
			echo '</div>';				
			echo '<div class="row">';
				echo '<span class="leftcell"> Rights: </span>';
				echo '<span class="rightcell">' .$rights. '</span>';
			echo '</div><p/>';	
			echo '<div class="row">';
				echo '<span class="leftcell">People: </span>';
				echo '<span class="rightcell">';
					while ($per->fetch()) {
					  
								echo '<a href="asubsearch.php?subject_id='.$subject_id.'">'.$subject_name . '</a> <br/>';								
						}		
					$per->close();		
		
			echo '</span><span class="edit"><a href="subp.php?article_id='.$article_id.'"> Manage People</a></span>';
			echo '</div><p/>';
			echo '<div class="row">';
				echo '<span class="leftcell">Subjects: </span>';
				echo '<span class="rightcell">' ; 
				
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

				
					while ($subj->fetch()) {
					  
								echo '<a href="asubsearch.php?subject_id='.$subject_id.'">'.$subject_name . '</a> <br/>';									
						}		
					$subj->close();
					
			echo '</span><span class="edit"><a href="sub.php?article_id='.$article_id.'">Manage Subjects</a></span>';		
			echo '</div>';				
		echo '</div><p/>';		
		echo '<div class="row">';
			echo '<span class="leftcell"> Abstract: </span>';
			echo '<span class="rightcell">' .$abstract. '</span>';
		echo '</div><p/>';			

		echo '<div class="pdf">';
			if (isset($article_pdf)) {
				echo '<a href="../pdf/'.$article_pdf.'" target="_blank"> <span class="download"> Download PDF</span></a>';
			}

			echo '<object data="../pdf/'.$article_pdf.'" type="application/pdf" width="800px" height="800px">';
			echo 'alt: <p>It appears you don\'t have a PDF plugin for this browser. However, you can <a href="../pdf/'.$article_pdf.'">click here to download the PDF file.</a></p>';
			echo '</object>';						
									
		echo '</div>';

		
		
		
?>
			</div>
		</div>
		
	</body>


</html>	

