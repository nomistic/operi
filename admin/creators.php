<?php
// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  GPL                                                                *
//                                                                              *
// ******************************************************************************
session_start();
	require_once('../includes/connect.php');
	include_once('../includes/pub.php');
	require_once('includes/loginid.php');

	
	if (!isset($_SESSION['user_id'])) {
		echo 'You do not have access to this page';
		exit();
	  }

?>

<!DOCTYPE html>
<html>

<head>
<title><?php echo $pubtitle; ?> - Manage Creators</title>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
<link rel="stylesheet" type="text/css" href="../css/custom.css" />

<script language="javascript">
var popupWindow = null;
function positionedPopup(url,winName,w,h,t,l,scroll){
settings =
'height='+h+',width='+w+',top='+t+',left='+l+',scrollbars='+scroll+',resizable'
popupWindow = window.open(url,winName,settings)
}
</script>

</head>
<body>

<?php

	require_once('includes/loggedin.php');
	include_once('includes/admintop.php');


	if (isset($_POST['delete'])) {
	
		foreach ($_POST['markdelete'] as $delete) {
		
			$c = $dbc->query("select c.creator_id, c.first_nm, c.middle, c.last_nm, c.fac_ind, a.article_title, i.issue_ed
							  from creator c
							  left join article a
							  on a.creator_id = c.creator_id
							  left join issue i
							  on a.issue_id = i.issue_id
							  where c.creator_id = $delete
							  and a.article_title is not null");
			$num = $c->num_rows;
			

			while ($row = $c->fetch_assoc()) {
			
			
			
				echo '<div class="alert">'. $row['first_nm'] . ' '. $row['middle'] . ' '. $row['last_nm']. ' is currently listed as the author for '. $row['article_title'] .' ('.$row['issue_ed'].'). <br/> If you wish to delete it, please edit the associated article.<p/></div>';
			
			}
		
		// only delete rows that are not associated with any articles
		
			if ($num == 0) {
	
				$del = $dbc->prepare("delete from creator where creator_id = ?");
				$del->bind_param("i",$delete);
				$del->execute();
				$del->close();
			
			}
		
		}
	
	}


	$creator = $dbc->query("select creator_id, first_nm, middle, last_nm, fac_ind
							from creator
							where last_nm is not null
							order by last_nm");
	

	
?>	
	

	<p/>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
	<fieldset><legend>Manage Creators</legend>

<a href="creator.php" onclick="positionedPopup(this.href,'myWindow','300','350','100','200','yes');return false">Add new creator</a><p/>

	<ul class="creators">
	<?php
	
	while ($row = $creator->fetch_assoc()) {
	
		echo '<li><input type="checkbox" value="'.$row['creator_id'].' "name="markdelete[]" /> <a href="cedit.php?creator_id='.$row['creator_id'].'"'; 
		?>
		onclick="positionedPopup(this.href,'myWindow','300','350','100','200','yes');return false"
		
		<?php 
		echo '>'.$row['last_nm'].', '.$row['first_nm'].' '.$row['middle'].'</a> </li>';
	}
	?>
	<p/>
	<input type="submit" name="delete" value="Delete selected creator(s)" />
	</ul>
	</fieldset>
	</form>	


</body>
</html>		