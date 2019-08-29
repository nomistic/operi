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
	include_once('../includes/pub.php');
	require_once('includes/loginid.php');



	//get contact information and email base.
	require_once('includes/contact.php');	
	
?>

<html>
<head>
	<title><?php echo $pubtitle; ?> Sign Up</title>
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

		<div class="mainmenu">
		
			<div class="top">
			&nbsp;
			</div>
			
			
<?php



if (isset($_POST['submit'])) {

	//Get the profile data from the input form
	$user_name = trim($_POST['user_name']);
	if ($ldom_ind == 1) {
		$email = $user_name.'@'.$em_base;
	}
	else {
		$email = trim($_POST['email']);
	}
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$password1 = trim($_POST['password1']);
	$password2 = trim($_POST['password2']);
	$token = uniqid(mt_rand(), true);
	
	if (isset($_POST['admin'])) {
			$admin = 1;
		}
		else {
			$admin = 0;
		}

	
	if (!empty($user_name) && !empty($email) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {
	
	//check to see if this user name is already being used
	$chkuser = "select user_name from user where user_name = ?";
	$cku = $dbc->prepare($chkuser);
	$cku->bind_param("s", $user_name);
	$cku->execute();
	$cku->store_result();
	
	//create variable to hold the number of rows with that user name. 
	$num = $cku->num_rows;
	$cku->close();
	
	//check to see if this email is being used
	$chkemail = "select email from user where email = ?";
	$cke = $dbc->prepare($chkemail);
	$cke->bind_param("s",$email);
	$cke->execute();
	$cke->store_result();
	
	//hold number of rows for email in a different variable
	$nume = $cke->num_rows;
	$cke->close();
	
		if ($num != 0) {
			echo 'That user name is currently already being used.  Please choose a different one.';
			$user_name = '';			

		}
		
		elseif ($nume != 0) {
			echo 'That email address is already being used';
			
		}
		else {
			//if no username exists, insert into the database
			$newu = $dbc->prepare("insert into user
								   (user_name, email, password, first_name, last_name, admin, token)
								   values (?, ?, sha(?), ?, ?, ?, sha(?))");
			$newu->bind_param("sssssis",$user_name, $email, $password1, $first_name, $last_name, $admin, $token);
			$newu->execute();
			$newu->close();
			
			//confirm success
			$user_id =  mysqli_insert_id($dbc);
			
			
			$resetlink = $reset . '?token='.$token;		


			$headers = "From: $contact \r\n";
			$headers .= "Reply-To: $contact \r\n";
			$headers .= "Return-Path: $contact";
	
							
			$to = $email;
			$subject = 'Welcome New ' . $pubtitle. ' Administrator';
			$msg = "Hi $first_name, welcome to $pubtitle database!. \n \n".
					"You now have access to add and edit records.  \n \n".
					"The first thing you will need to do is to set your password.  Your user name is $user_name. To set your password, please follow this link: $resetlink \n\n".
					"If you have any questions, or did not request this, please contact me at $contact  \n";
					
					

				
			mail($to, $subject, $msg, $headers);			
			
		

			$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/useradmin.php';
			header('Location: ' . $home_url);			
			echo '<p>You have successfully created an account.</p><p>You may <a href="login.php">Login</a> now.</p>';
			
			exit();
		}
	}
	else {
		echo 'You must enter all of the requested  information, including user name, email, and the password twice.';
	}
}
?>
<!-- <p>Already have an account?  <a href="login.php">Log in</a></p> -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<legend>Create an account</legend>
			<label for="first_name">First Name</label><br/>
			<input tyle="text" id="first_name" name="first_name" value="<?php if(!empty($first_name)) echo $first_name; ?>"/><br/>
			<label for="last_name">Last Name</label><br/>
			<input tyle="text" id="last_name" name="last_name" value="<?php if(!empty($last_name)) echo $last_name; ?>"/><br/>			
			<label for="user_name">Username</label><br/>
			<input type="text" id="user_name" name="user_name" value="<?php if(!empty($user_name)) echo $user_name; ?>" placeholder="Create a user name" required /><br/>
	<?php
			
		 
		if ($ldom_ind == 0) {			
			echo '<label for="email">Email Address</label><br/>';
			echo '<input type="email" id="email" name="email" value="';
			if(!empty($email)) {echo $email;}
			echo '" size="30" placeholder="Enter a valid email address" required/><br/>';	
		}	


	?>			
			<label for "password1">Password</label><br/>
			<input type="password" id="password1" name="password1" /><br/>
			<label for "password2">Password  (re-enter)</label><br/>
			<input type="password" id="password2" name="password2" /><br/>
			Admin: <input type="checkbox" name="admin" />  <p/>			

	</fieldset>
	<p/>
	<input type="submit" value="Sign Up" name="submit" />
</form>

		</div>
	</div>
</body>
</html>