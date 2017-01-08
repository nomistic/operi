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


	$keyword = $_POST['keyword'];


?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $pubtitle. ' - Search Results for '. $keyword;  ?></title>
<link rel="stylesheet" type="text/css" href="css/digpub.css" />
<link rel="stylesheet" type="text/css" href="css/custom.css" />

</head>
<body>
<?php

include_once('includes/docheader.php'); 
echo '<div class="left">';




 	
	
	echo '<h3>Search Results: ' .$keyword  . '</h3>';
        $searchq = $dbc->prepare("select distinct a.article_id,
						   a.article_title, 
						   c.first_nm, 
						   c.middle, 
						   c.last_nm,
						   i.issue_ed,
						   match (a.article_title) against (? in boolean mode) as ascore,
						   match (c.first_nm, c.middle, c.last_nm) against (? in boolean mode) as cscore,
						   match (s.subject_name) against (? in boolean mode) as sscore
					from article a
					join issue i
					on a.issue_id = i.issue_id
					join creator c
					on a.creator_id = c.creator_id
					join type t
					on a.type_code = t.type_code
					left join article_subject sa
					on a.article_id = sa.article_id
					left join subject s
					on s.subject_id = sa.subject_id
					where t.display = 1
					and i.pub_ind = 1
					AND
						   (match (a.article_title) against (? in boolean mode) OR
						   match (c.first_nm, c.middle, c.last_nm) against (? in boolean mode) OR
						   match (s.subject_name) against (? in boolean mode))
					ORDER by ascore, cscore, sscore DESC");
					
   
		$searchq->bind_param("ssssss",$keyword,$keyword,$keyword,$keyword,$keyword,$keyword);

		$searchq->execute();
		$searchq->bind_result($article_id,$article_title,$first_nm,$middle,$last_nm,$issue_ed,$ascore,$cscore,$sscore);
		while ($searchq->fetch()) {
		$creator_name = $first_nm. ' '. $middle. ' '. $last_nm;

			echo '<div class="record">';
			echo '<div class="atitle"><a href="article.php?article_id='.$article_id.'">'. $article_title . '</a></div>';
			echo '<div class="data">';
			echo $creator_name. ' - '. $issue_ed;
			if (isset($article_pdf)) {
				echo ' <a href="pdf/'.$article_pdf.'" target="_blank">Download PDF</a>';
			}	
			echo '</div>';
		echo '</div>';
			
		
        }
		
		$searchq->close();
  
  

//mysqli_close($dbc);


?>

</div>


</body>
</html>