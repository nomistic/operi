<?php
error_reporting(E_ALL);
	ini_set( 'display_errors','1'); 
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
	//get contact information and email base.
	require_once('includes/contact.php');	


?>

<html>
	<head>
		<title> <?php echo $pubtitle;  ?> Password Reset</title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
	</head>
	<body>
	<div class="login">



<?php


	
	if (isset($_POST['submit'])) {
	
	//data from submit form
		if ($ldom_ind == 0) {
			$e_mail = trim($_POST['e_mail']);
		}
		else {
			$user_name = trim($_POST['user_name']);
		}
		$token = uniqid(mt_rand(), true);
		
		
		if(!empty($e_mail) || !empty($user_name)) {
			
			//echo $e_mail;
			if ($ldom_ind == 0) {
				$uq = $dbc->prepare("SELECT user_id, user_name, email, first_name, last_name 
							FROM user
							WHERE email = ?");
				$uq->bind_param("s", $e_mail);
				$uq->execute();
				$uq->bind_result($user_id,$username, $email, $first_name,$last_name);
				$uq->store_result();
			}
			else {
				$uq = $dbc->prepare("SELECT user_id, user_name, email, first_name, last_name 
							FROM user
							WHERE user_name = ?");
				$uq->bind_param("s", $user_name);
				$uq->execute();
				$uq->bind_result($user_id,$username, $email, $first_name,$last_name);
				$uq->store_result();

				$e_mail = $user_name.'@'.$em_base;
					
			}
			
			

	
			while ($uq->fetch()) {
			
			
			//if the submitted username or email does not match the one in the tables (hold on, there may be a problem here;  maybe should be a count like on the original form, but it should work; either way it should generate an error)
				
				if (($ldom_ind ==0) && ($e_mail != $email)) { 
					echo 'That email is not in the system.';
				}
				elseif (($ldom_ind ==1) && ($user_name != $username)) {
					echo 'That username is not in the system.';
					
				}
				else {
				
					$resetlink = $reset . '?token='.$token;				

					$newtoken =$dbc->prepare("UPDATE user 
											  SET token= sha(?) 
											  WHERE user_id = ?");
					$newtoken->bind_param("si", $token, $user_id);
					$newtoken->execute();
					$newtoken->close();
	
					$headers = "From: $contact \r\n";
					$headers .= "Reply-To: $contact \r\n";
					$headers .= "Return-Path: $contact";			
					
					$to = $e_mail;
					$subject = $pubtitle . ' password reset';
					$msg = "Hi $first_name, you requested a new password. \n \n".
							"If you did not request this, please contact us at $contact. \n \n".
							"To reset your password, please follow this link: $resetlink \n";
				
					mail($to, $subject, $msg, $headers);

					
					echo 'Your request has been sent to '.$e_mail. '.  <a href="login.php">Login</a>';
					exit();
				}
			
		
			}
		$uq->free_result();	
		$uq->close();
		}
		else {
		echo 'Please enter your user name.';
		}
	
	}
	
	
?>


		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?> ">
			<fieldset>
				<legend align="center"> <?php echo $pubtitle;  ?> Password Reset</legend>
		<?php
			if ($ldom_ind == 0) {
				echo '<label for="e_mail">Enter your Email Address</label><br/>';
				echo '<input type="text" name="e_mail" id="e_mail" size="30"/>';
			}
			else {
				echo '<label for="user_name">Enter your username</label><br/>';
				echo '<input type="text" name="user_name" id="user_name" size="30"/>';				
			}
				
		?>		
				<p><input type="submit" value="Reset password" name="submit" /></p>
			
			</fieldset>
		</form>
		</div>

	</body>

</html>