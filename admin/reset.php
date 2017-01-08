<?php

// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  GPL                                                                *
//                                                                              *
// ******************************************************************************

//error_reporting(E_ALL);
//	ini_set( 'display_errors','1'); 
	require_once('../includes/connect.php');
	include_once('../includes/pub.php');
	require_once('includes/contact.php');
//connect to the database

/*
$dbc = new mysqli($host, $username, $password, $dbname);

if ($dbc->connect_error) {
	die("Connection failed: " . $dbc->connect_error);
}
*/
//get contact information and email base
//require_once('includes/contact.php');

//$user_name = $_GET['user_name'];
//$user_id = $_GET['user_id'];

?>

<html>
	<head>
		<title><?php echo $pubtitle; ?> Password Reset</title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
	</head>
	<body>



<?php

if (isset ($_GET['token'])) {
$token = $_GET['token'];
}

//verify the token against the user name


	$chkuser = $dbc->prepare("select user_id, user_name, email, token from user where token = sha(?)");
	$chkuser->bind_param("s",$token);
	$chkuser->execute();
	$chkuser->store_result();
	$num = $chkuser->num_rows;
	$chkuser->bind_result($user_id,$user_name, $email, $tokenexist);	

	if ($num != 1) {
	
	echo 'I\'m sorry, either your reset session has expired, or this is not your account. <a href="pwf.php">Please try again</a>.';
	exit();

	} 	
	else {
		while ($chkuser->fetch()) {
		

	echo 'Reset password for ' . $user_name;
		}	

	}

	$chkuser->free_result();
	$chkuser->close();
	
	if (isset($_POST['submit'])) {
//check to see if new passwords match

		$password1 = trim($_POST['password1']);
		$password2 = trim($_POST['password2']);
		
		if (!empty($password2) && ($password1 == $password2)) {	
		
		//update password, clear token
				$pass = $dbc->prepare("update user
									   set password = sha(?), token= NULL
									   where user_id = ?");
				$pass->bind_param("si", $password1, $user_id);
				$pass->execute();
				$pass->close();
				
			echo '<p>You have successfully updated your password for ' . $pubtitle. '</p><p>You may <a href="login.php">Login</a> now.</p>';	

			if ($ldom_ind == 1) {
					$email = $user_name.'@'.$em_base;
			}

		//send email verification of update
		
			$headers = "From: $contact \r\n";
			$headers .= "Reply-To: $contact \r\n";
			$headers .= "Return-Path: $contact";		
		
			$to = $email;
			$subject =  $pubtitle .' Password Reset Notification';
			$msg = "The password for $user_name has just been reset. \n \n".
					"If you did not reset this password yourself, please contact us at $contact immediately. \n";
					
					
			//$token = '';
				
			mail($to, $subject, $msg, $headers);						
	
			exit();	
		}
		else {
		
		echo '<div class="error">Your passwords don\'t match.  Please try again</div>';
		}
	}	
?>


		<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?token='.$token; ?>">
			<fieldset>
				<legend>Reset <?php echo $pubtitle;  ?> Password</legend>
					<label for "password1">Password</label><br/>
					<input type="password" id="password1" name="password1" /><br/>
					<label for "password2">Password  (re-enter)</label><br/>
					<input type="password" id="password2" name="password2" /><br/>		
				
			</fieldset>
			<p/>
			<input type="submit" value="Set Password" name="submit" />
		</form>
		
	</body>

</html>
