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
	
	require_once('includes/loginid.php');
	

	if ((!isset($_SESSION['user_id'])) || ($_SESSION['admin'] != 1)) {
		echo 'You do not have access to this page';
		exit();
	}
	
	include_once('../includes/pub.php');
	
	if (isset($_POST['submit'])) {
		

		$title = $_POST['pubtitle'];
		$ystart =$_POST['ystart'];
		$yend = $_POST['yend'];
			
		$tupdate = $dbc->prepare("insert into pub (title, ystart, yend) values (?,?,?)");
		$tupdate->bind_param("sss", $title, $ystart, $yend);
		$tupdate->execute();
		$tupdate->close();
			
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $pubtitle; ?> - Site Management</title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
<link rel="stylesheet" type="text/css" href="../css/custom.css" />

</head>
<body>
<?php 
	require_once('includes/loggedin.php');
	include_once('includes/admintop.php'); 
?>	

	<div class="container">
		<div class="mainmenu">	
		
			<h4>Manage Title History</h4>
				<table>	
					<tr class="header"><td>Start Year&nbsp;</td><td>End Year&nbsp;</td><td>Title &nbsp;</td><td></td></tr>
			<?php
			$titlehist = $dbc->query("select title_id, title, ystart, yend from pub");
			while ($row = $titlehist->fetch_assoc()) {
				
				$title_id = $row['title_id'];
				$pubtitle = $row['title'];
				$ystart = $row['ystart'];
				$yend = $row['yend']; 
				
				echo '<tr><td>'.$ystart. '</td><td> '. $yend . '</td><td>'. $pubtitle. ' &nbsp;</td><td> <a href="edh.php?title_id='.$title_id.'"/>Edit</a> | <a href="delh.php?title_id='.$title_id.'">Delete</a> </td></td>';
					
			}
			
			?>
			</table>
			<p/>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<fieldset>
					<legend>Add Title Change</legend>


						<label for "pubtitle">Publication Title</label><br/>
						<input type="text" size="50" id="pubtitle" name="pubtitle" placeholder="New Title"/> Start Date:<span class="alert">*</span> <input type="text" size="10" name="ystart" /> End Date:<span class="alert">*</span> <input type="text" size="10" name="yend" /><br/>
						<span class="alert">*Use format 0000-00-00 or 00000000 (Year-month-day)</span>
					<p/>
				</fieldset>
				<p/>
				<input type="submit" name="submit" value="Submit" />
			</form>
		</div>	
	
	</div>
</body>
</html>	