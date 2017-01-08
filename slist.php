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
 
  ?>
 
<!DOCTYPE html>
<html>
<head>
<title><?php echo $pubtitle; ?> - Subject Terms</title>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="css/digpub.css" />
<link rel="stylesheet" type="text/css" href="css/custom.css" />
</head>
<body>
 <?php
include_once('includes/docheader.php'); 
 echo '<h4>Browse Subjects</h4>';
 
 ?>
 
  <table bgcolor="#cccccc" border="0" cellpadding="5" cellspacing="0" class="alpha">
<tbody>
<tr align="center" bgcolor="#ffffff">
<td><a href="#A"> <strong>A</strong></a></td>
<td><a href="#B"> <strong>B</strong></a></td>
<td><a href="#C"> <strong>C</strong></a></td>
<td><a href="#D"> <strong>D</strong></a></td>
<td><a href="#E"> <strong>E</strong></a></td>
<td><a href="#F"> <strong>F</strong></a></td>
<td><a href="#G"> <strong>G</strong></a></td>
<td><a href="#H"> <strong>H</strong></a></td>
<td><a href="#I"> <strong>I</strong></a></td>
<td><a href="#J"> <strong>J</strong></a></td>
<td><a href="#K"> <strong>K</strong></a></td>
<td><a href="#L"> <strong>L</strong></a></td>
<td><a href="#M"> <strong>M</strong></a></td>
</tr>
<tr align="center" bgcolor="#ffffff">
<td><a href="#N"><strong>N</strong></a></td>
<td><a href="#O"> <strong>O</strong></a></td>
<td><a href="#P"> <strong>P</strong></a></td>
<td><a href="#Q"> <strong>Q</strong></a></td>
<td><a href="#R"> <strong>R</strong></a></td>
<td><a href="#S"> <strong>S</strong></a></td>
<td><a href="#T"> <strong>T</strong></a></td>
<td><a href="#U"> <strong>U</strong></a></td>
<td><a href="#V"> <strong>V</strong></a></td>
<td><a href="#W"> <strong>W</strong></a></td>
<td><a href="#X"> <strong>X</strong></a></td>
<td><a href="#Y"> <strong>Y</strong></a></td>
<td><a href="#Z"> <strong>Z</strong></a></td>
</tr>
</tbody>
</table>
<p />

 <?php
	
	
	$per = $dbc->query("select distinct s.subject_id, s.subject_name 
						from subject s
						left join article_subject ars
						on s.subject_id = ars.subject_id
						left join article a
						on a.article_id = ars.article_id
						join issue i
						on i.issue_id = a.issue_id
						and i.pub_ind = 1
						order by subject_name");

	echo '<ul class="record">';

	$prev_row = '';	
	
	while ($row = $per->fetch_assoc()) {
		
		$letter = strtoupper(substr($row['subject_name'],0,1));

		if ($letter != $prev_row)  {
		
		echo '<a name="' . $letter . '"><h3>' . $letter . '</h3></a>';
		
		}		
		
		echo '<li class="subjects"><a href="subsearch.php?subject_id='.$row['subject_id'].'">'. $row['subject_name'] . '</a> </li>';
		
		$prev_row = $letter;
	}
	echo '</ul>';						
	

?>


</body>
</html>