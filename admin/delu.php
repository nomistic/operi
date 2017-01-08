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

error_reporting(E_ALL);
ini_set( 'display_errors','1'); 

	require_once('../includes/connect.php');
	require_once('includes/loginid.php');



$user_id = $_GET['user_id'];

	if ((!isset($_SESSION['user_id'])) || ($_SESSION['admin'] != 1)) {
		echo 'You do not have access to this page';
		exit();
	  }
	  
	  //connect to the database
	$dbc = new mysqli($host, $username, $password, $dbname);

	if ($dbc->connect_error) {
		die("Connection failed: " . $dbc->connect_error);
	}

	//identify current user from session id
	$cur_user = $_SESSION['user_id'];
	
	if (isset($_POST['delete'])) {
	
		$delu = $dbc->prepare("Delete FROM user where user_id = ?");				
		$delu->bind_param("i", $user_id);
		$delu->execute();
		$delu->close();	
	
		header('Location: ' . 'useradmin.php');
	
	}
	elseif (isset($_POST['cancel'])) {
	
			header('Location: ' . 'useradmin.php');
	}
	
	else {
	
		$user = $dbc->prepare("select first_name, last_name from user where user_id = ?");
		$user->bind_param("i", $user_id);
		$user->execute();
		$user->store_result();
		$user->bind_result($first_name,$last_name);
			while ($user->fetch()) {
				echo '<div class="error">Are you sure you want to delete ' . $first_name .  ' '. $last_name .'? <p /> This cannot be undone.<br /><br /></div>';
				
			}
			
?>			

<?php			
		$user->free_result();
		$user->close();		
	}
	
?>
<html>
<head>
<title>Delete User</title>
</head>
<body>
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?user_id=' . $user_id; ?>">

				<input type="submit" name="delete" value="Delete" /> <input type="submit" name="cancel" value="Cancel" />

			</form>
			
</body>
</html>			