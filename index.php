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
	<title><?php echo $pubtitle; ?></title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
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

<?php

echo '<header>';


?>

<div class="search">
	<form method="post" action="search.php">
		<fieldset>
		<input type="text" name="keyword" size="50" placeholder="Search the <?php echo $pubtitle; ?>"/>  <input type="submit" class="btn btn-primary" name="submit" value="Search" />
		</fieldset>
	</form>
</div>
<a href="."><?php echo $pubtitle; ?> Main Page</a>

<?php
echo '</header>';
echo '<nav class="navbar navbar-dark bg-dark"><ul><li></li><li><a href="type.php">Content Type</a></li><li><a href="clist.php">Author</a></li><li><a href="slist.php">Subject</a></li></ul></nav>';

?>

<div class="container">


<?php



	$current = $dbc->query("select issue_id, issue_ed, volume, pub_ind, number, year, issue_cover 
							from issue 
							where volume = 
							(SELECT max(volume)
							from issue
							where pub_ind = 1)
                            order by volume, number desc
                            limit 1");
			
	$row = $current->fetch_assoc(); 
	$issue_id =$row['issue_id'];
	$issue_cover = $row['issue_cover'];
	$issue_ed = $row['issue_ed'];
	$volume = $row['volume'];
	$number = $row['number'];
	$year = $row['year'];
	
	$headers =  $dbc->prepare("select distinct h.header_id, h.header_name
								from header h
								join article a
								on h.header_id = a.header_id
								join type t
								on a.type_code = t.type_code
								where a.header_id = h.header_id
								and a.issue_id = ?
								and t.display = 1
								and t.type_code != 'MA'
								order by a.order_in_issue");
	$headers->bind_param("i",$issue_id);
	$headers->execute();
	$headers->bind_result($header_id, $header_name);
	$headers->store_result();



		

	?>
	
	<div class="main">
	
		<div class="front">
			
				
			<div class="mleft">

		<?php

			if (!empty($issue_cover)) {

				echo '<div class="coveri"><a href="issue.php?issue_id='.$issue_id.'"><img src="images/'.$issue_cover.'" id="main" alt="'.$issue_ed.'" title="'.$issue_ed.'" /><br/>Current Issue - '.$issue_ed.'</a></div>';
			}	
		?>
			</div>
			<div class="mright">
				<div class="text">
				<?php 
				
				$mp = $dbc->prepare("select maintext from site_data");
				$mp->execute();
				$mp->bind_result($maintext);
				$mp->fetch();
								
				echo $maintext; 
				
				$mp->close(); 
				?>
				</div>
				<ul class="links">
				<li><a href="all_issues.php">Browse the Archive</a></li>
				<li><a href="issue.php?issue_id=<?php echo $issue_id ?>">View the Current Issue</a> (<?php echo 'V. ' . $volume . ' Issue ' . $number . ', ' . $year . ' - '. $issue_ed; ?>)<br/></li>
				
				</ul>
				
				<div id="contents">
				<h4>Table of Contents</h4>
				<?php
	
		
		while ($headers->fetch()) {
			echo '<div class="mheaders">'.$header_name.'</div>';
		
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
										and a.type_code != 'MA'
										order by order_in_issue");
			$articles->bind_param("ii",$issue_id, $header_id);
			$articles->execute();
			$articles->bind_result($article_id, $article_title, $first_nm, $middle, $last_nm, $header_id, $header_name, $order_in_issue, $article_pdf, $issue_id);			
		
			while ($articles->fetch())  {
				
				
				
				$creator = $first_nm . ' ' .$middle. ' ' . $last_nm;
				echo '<div class="mrecord">';
					echo '<span id="title">'. $article_title . '</span> - '. $creator;
	


				echo '</div>';
				

				} 
			
		$articles->free_result();
		$articles->close();
		
	
	}	
				
				?>
			
				</div>
				
			</div>
		</div>



</div>


</body>
</html>