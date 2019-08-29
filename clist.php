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
    include_once('includes/docheader.php');
 ?>

<div class="main">
    <h3>All Creators</h3>
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

	
	$creators = $dbc->query("select distinct c.creator_id, c.first_nm, c.middle, c.last_nm 
							from creator c
							join article a
							on c.creator_id = a.creator_id
							join type t
							on a.type_code = t.type_code
							join issue i
							on a.issue_id = i.issue_id
							where t.display = 1
							and i.pub_ind = 1
							and c.last_nm is not null 
							order by last_nm");

	echo '<ul class="record">';
	$prev_row = '';
	while ($row = $creators->fetch_assoc()) {

		
		$letter = strtoupper(substr($row['last_nm'],0,1));
		
		if ($letter != $prev_row)  {
		
		echo '<a name="' . $letter . '"><h3>' . $letter . '</h3></a>';
		
		}
		
		$creator_name = $row['last_nm']. ', '. $row['first_nm'] . ' '. $row['middle'];

		
		echo '<li class="creators"><a href="csearch.php?creator_id='.$row['creator_id'].'">'. $creator_name . '</a> </li>';
		
		$prev_row = $letter;
	}
	echo '</ul>';						
	
?>

</div>
</body>
</html>