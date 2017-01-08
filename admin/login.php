<?php
session_start();

    require_once('../includes/connect.php');
	include_once('../includes/pub.php');

// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  GPL                                                                *
//                                                                              *
// ******************************************************************************


//clear the error message variable

$error = "";


//if user isn't logged in , try to log them in.


if (!isset($_SESSION['user_id'])) {
	if (isset($_POST['submit'])) {
	

		//user entered data
		$username = trim($_POST['user_name']);
		$password = trim($_POST['password']);
		
		if(!empty($username) && !empty($password)) {
		//check username and password from database

  
		
		$uq = $dbc->prepare("SELECT user_id, user_name, admin 
					FROM user
					WHERE user_name = ?
					AND password = SHA( ? )");
		$uq->bind_param("ss", $username, $password);
		$uq->execute();
		$uq->store_result();
		$num = $uq->num_rows;
		$uq->close();
		
		$qd = "SELECT user_id, user_name, admin 
					FROM user
					WHERE user_name = '$username'";
		
		$ud = $dbc->query($qd);

			if ($num == 1) {
				//if login is okay, and there is a row, set user ID and username variables
				$row = $ud->fetch_assoc();

				
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['user_name'] = $row['user_name'];
				$_SESSION['admin'] = $row['admin'];
				

				header('Location: ' . '.');


			}
			else {

				$error = 'Sorry, you must enter a valid username and password to log in.';
			}
		}
		else {
		
		//user name and password were not entered, so error message
		
		$error = 'Sorry, you must enter your user id and password to log in.';
		
		}
	
	}

}

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $pubtitle ?> Login</title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
<link rel="stylesheet" type="text/css" href="../css/custom.css" />
</head>
<body>
	<div class="container">

		<div class="mainmenu">
		<div class="login">
			<div class="top">
	<h3><?php echo $pubtitle ?> Administration - Login</h3>
			</div>
			
			



<?php 
	// if the cookie is empty, show errors and the login form, otherwise confirm login
	if (!isset($_SESSION['user_id'])) {
		echo '<p>' . $error . '</p>';
		
?>	
<!-- <p><a href="signup.php">Create a new account</a> or: </p>	-->
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?> ">
		<fieldset>
			<legend align="center">Log In</legend>
			<label for "user_name">Username:</label><br/>
			<input type="text" id="user_name" name="user_name" /><br/>
			<label for "password">Password:</label><br/>
			<input type="password" id="password" name="password" />
		</fieldset><p/>
		<input type="submit" value="Log In" name="submit" />
	</form>
	<p><a href="pwf.php">Forgot your password?</a></p>


<?php
	}
	else {
	//confirm login
	echo('<p class="login">You are logged in as ' . $_SESSION['user_name'] . '.</p>');
	header('Location: ' . '.');
	}
?>
		</div>
		</div>
	</div>
</body>
</html>