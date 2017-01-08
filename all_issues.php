<?php
// ******************************************************************************
// Software: Periodical Digitization System                                     *
// Version:  1.0                                                                *
// Date:     2015-07-01                                                         *
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
<title><?php echo $pubtitle; ?> - All Issues</title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="css/digpub.css" />
<link rel="stylesheet" type="text/css" href="css/custom.css" />

<script>
    menu_status = new Array();

    function showHide(theid){
        if (document.getElementById) {
        var switch_id = document.getElementById(theid);

            if(menu_status[theid] != 'show') {
               switch_id.className = 'show';
               menu_status[theid] = 'show';
            }else{
               switch_id.className = 'hide';
               menu_status[theid] = 'hide';
            }
        }
    }
</script>
</head>
<body>

<?php  include_once('includes/docheader.php'); ?>

<div class="container">


<?php

	$lastiss = $dbc->query("select issue_id, issue_ed, volume, number, issue_cover 
				from issue 
				where pub_ind = 1
				order by year desc, volume desc, number desc
				LIMIT 4");
				
	echo '<div class="lastiss">';	
	echo '<ul>';
	while ($r = $lastiss->fetch_assoc()) {
			echo '<li><a href="issue.php?issue_id='.$r['issue_id'].'"><img src="images/' .$r['issue_cover'].'" alt="'.$r['issue_ed'].'" title="'.$r['issue_ed'].'" />';
			echo '<figcaption>'.$r['issue_ed'] .'</figcaption></a></li>';
			//echo '<p/>';
			}
	echo '</ul>';			
	echo '</div>';		



	$years = "SELECT distinct year FROM issue order by year desc";
	$yeare = $dbc->query($years);
	
	$i = 0;
	
	echo '<table class="islist">';
	echo '<caption>All Issues</caption>';
	
	
	while ($row = $yeare->fetch_assoc()) {

		$year = $row['year'];
		
		if ($i % 4 == 0) {
		
			echo '<tr>';
		}
		
		
		echo '<td><button onclick="showHide(\''.$year. '\')">' . $year . '</button>';		
		$yeared = $dbc->prepare("SELECT issue_id, year, issue_ed, number, year 
					FROM issue
					where year= ?
					and pub_ind = 1
					order by volume, number");
		$yeared->bind_param("i", $year);
		$yeared->execute();
		$yeared->store_result();
		$yeared->bind_result($issue_id, $year, $issue_ed, $number, $year);
		echo '<ul class="hide" id="'.$year.'">';
			while ($yeared->fetch()) {
			
				echo '<li class="issuea"><a href="issue.php?issue_id='. $issue_id .'">'.$issue_ed . '</a></li>';
			}
		echo '</ul>';
		
	
		$yeared->free_result();
		$yeared->close();
		
		echo '</td>';

		if ($i % 4 == 4) {
		
			echo '</tr>';
		}	
			$i++;
	}
	

	echo '</table>';


?>

</div>
</body>
</html>