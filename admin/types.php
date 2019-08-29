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


	if ((!isset($_SESSION['user_id'])) || ($_SESSION['admin'] != 1)) {
		echo 'You do not have access to this page';
		exit();
	  }


	if (isset($_POST['update'])) {
	
	
		foreach($_POST['display'] as $type_code => $display_ind) {

			$udis = $dbc->prepare("update type
								   set display = ?
								   where type_code = ?");
			$udis-> bind_param("is",$display_ind, $type_code);
			$udis->execute();
			$udis->close();
		}
						   
echo '<div class="alert">Display types updated</div>';		
	}

	if (isset($_POST['submit'])) {
	
		
		$type_code = $_POST['type_code'];
		$type_name = $_POST['type_name'];
		
		
		if ((empty($type_code)) || (empty($type_name))) {
			echo '<div class="alert">You must enter both type code and type name</div>';
		}
		
		
		else {
			$typeadd = $dbc->prepare("insert into type (type_code, type_name) values (?,?)");
			$typeadd->bind_param("ss", $type_code, $type_name);
			$typeadd->execute();
			$typeadd->close();
		

		}
	}
	
	$typeq = "select type_code, type_name, display from type order by type_name";
	$types = $dbc->query($typeq);
	
	
?>	
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $pubtitle; ?> - Manage Types</title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
<link rel="stylesheet" type="text/css" href="../css/custom.css" />
</head>
<body>

<?php 
	require_once('includes/loggedin.php');
	include_once('includes/admintop.php'); 


?>
	<div class="main">
	<h3>Manage Article Types</h3>
		<div class="mainmenu">
			<div class="top">
			&nbsp;
			</div>
			<div class="alert">Important:
			<ul>
				<li>Please check before adding a type to make sure it does not already exist.</li>
				<li>All new types must have a unique type code.</li>
			</ul>
			</div>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<table>
				<tr class="header"><td>Type Code</td><td>Type Name</td><td>Display</td><td></td></tr>

			<?php


				
				while ($row = $types->fetch_assoc()) {
				
				$type_code = $row['type_code'];


					echo '<tr><td><input type="hidden" name="type_code[]" value="' . $type_code . '"/>' .$row['type_code'].'</td><td>'.$row['type_name'].'</td><td>Yes <input type="radio" name="display['.$type_code.']" value="1"';
						if ($row['display'] == 1) {echo ' checked';} 

					echo '/> No <input type="radio" name="display['.$type_code.']" value="0"';
						if ($row['display'] == 0) {echo ' checked';} 
					
					
					echo '/></td><td><a href="delt.php?type_code='.$type_code.'">Delete</a></td></tr>';
				
				}
			
			?>

			<tr class="header"><td>Update Display:</td><td></td><td><input type="submit" name="update" value="Update" /></td><td></td><td></td></tr>			
			<tr><td></td><td></td><td></td></tr>		
			<tr class="header"><td>Add New Type:</td><td></td><td></td></tr>
	
			<tr><td><input type="text" name="type_code" placeholder="Type Code" /></td><td><input type="text" name="type_name" placeholder="Type Name" /></td><td></td></tr>
			</table>
			<input type="submit" name="submit" value="Add new article type" />
			</form>
		
		</div>	
	
	</div>
</body>
</html>