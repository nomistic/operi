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
	  
	  //connect to the database


	if (isset($_POST['submit'])) {
	

		$contact_id = $_POST['contact_id'];

		$update_contact = $dbc->prepare("update contact set contact_id = ?");
		$update_contact->bind_param("i", $contact_id);
		$update_contact->execute();
		$update_contact->close();
		
echo '<div class="alert">Contact updated </div>';
	} 

	//identify current user from session id
	$cur_user = $_SESSION['user_id'];
	
	//users
	$userq = "SELECT u.user_id, u.user_name, u.email, u.first_name, u.last_name, u.admin, c.contact_id
				FROM user u
				LEFT join contact c
				ON u.user_id = c.contact_id";

	$uresult = $dbc->query($userq);
	


?>

<!DOCTYPE html>
<html> 
<head>
	<title><?php echo $pubtitle; ?> - User Administration</title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js">
</script>
	<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
	<link rel="stylesheet" type="text/css" href="../css/custom.css" />
</head>
<body>

<?php
	require_once('includes/loggedin.php');
	include_once('includes/admintop.php');
 ?>
	<div class="container">
		<h3>User Administration</h3>
		<div class="mainmenu">
		
		
		<p><a href="signup.php">Add new user</a></p>
		All users have access to 
		<ul>

			<li>Add new articles</li>
			<li>Manage Creators</li>
		</ul>	
			Admin users have access to edit
		<ul>	
			<li>Manage Users</li>
			<li>Manage Types</li>
			<li>Site Management</li>
		</ul>
		
			<div class="users">
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<table>
				<tr class="header"><td>Username</td><td>First Name</td><td>Last Name</td><td>Email</td><td>Admin</td><td>Contact</td><td></td><td></td></tr>
			<?php


				
				while ($row = $uresult->fetch_assoc()) {

					if ($row['admin'] == 1) {
						$administrator = 'Y';
					}
					else {
						$administrator = '';
					}
					
					$contact_id = $row['contact_id'];
					$user_id = $row['user_id'];

					echo '<tr><td>' .$row['user_name'].'</td><td>'.$row['first_name'].'</td><td>'.$row['last_name'].'</td><td>'.$row['email'].'</td><td>'.$administrator.' </td>';
					echo '<td><input type="radio" name="contact_id"';
					if ($contact_id == $user_id) {
                       echo ' checked'; 
					}   
					echo ' value= "'.$user_id. '"/></td>';
					echo '<td><a href="editu.php?user_id='.$user_id.'">Edit</a></td><td>';

						if ($row['user_id'] != $cur_user) {
							echo '<a href="delu.php?user_id='.$user_id.'">Delete</a>';
							}
						echo '</td></tr>';
				
				}
			
			?>
				
			</table>
			<p />
			<input type="submit" name="submit" value="Update Primary Contact" />
			</form>
			</div>
			
		</div>	
	


	</div>
</body>
</html>