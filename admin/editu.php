<?php
session_start();
	require_once('../includes/connect.php');
	require_once('includes/loginid.php');
	include_once('../includes/pub.php');
	
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
	
		if (isset($_POST['submit'])) {
			
			$first_name = trim($_POST['first_name']);
			$last_name = trim($_POST['last_name']);
			$email = trim($_POST['email']);
			
			if ((isset($_POST['admin'])) || ($user_id == $cur_user)){
					$admin = 1;
				}
				else {
					$admin = 0;
				}	
				
				echo $user_id . $first_name .$last_name.$admin;

					
				$updu = $dbc->prepare("UPDATE user 
									SET first_name= ?, 
									last_name = ?, 
									email = ?,
									admin = ?
									where user_id = ?");
				var_dump($updu);					
				$updu->bind_param("sssii", $first_name, $last_name, $email, $admin, $user_id);
				$updu->execute();
				$updu->close();
			
			header('Location: ' . 'useradmin.php');
			}

	$userq = $dbc->prepare("select user_id, user_name, first_name, last_name, email, admin
		   from user where user_id = ?");
		   
	$userq->bind_param("i", $user_id);
	$userq->execute();
	$userq->bind_result($user_id,$user_name,$first_name,$last_name, $email, $admin);
	
	



		//get user info from GET variable

		//$userq->close();
		
		while ($userq->fetch()) {
		//	$user_id = $row['user_id'];
		//	$user_name = $row['user_name'];
		//	$first_name = $row['first_name'];
		//	$last_name = $row['last_name'];
		//	$admin = $row['admin'];
		
		
?>

<html>
	<head>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
	</head>
	<body>

<?php 

	require_once('includes/loggedin.php');
	include_once('includes/admintop.php');
?>	
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?user_id='.$user_id; ?>">
			<fieldset>
				<legend>Edit Information for <?php echo $first_name . ' ' . $last_name ?></legend>
					<label for="first_name">First Name</label><br/>
					<input tyle="text" id="first_name" name="first_name" value="<?php if(!empty($first_name)) echo $first_name; ?>"/><br/>
					<label for="last_name">Last Name</label><br/>
					<input tyle="text" id="last_name" name="last_name" value="<?php if(!empty($last_name)) echo $last_name; ?>"/><br/>
					<label for="email">Email Address</label><br/>
					<input type="email" id="email" name="email" value="<?php if(!empty($email)) echo $email; ?>" size="30" placeholder="Enter a valid email address" required/><br/>						
					<?php 
					if ($user_id != $cur_user) {
					?>
					Admin: <input type="checkbox" name="admin" <?php if ($admin == 1) {echo 'checked="checked"';} ?>/>  <p/>
					<?php 
					}
					?>

			</fieldset>
			<p/>
			<input type="submit" value="update" name="submit" />
		</form>	
	
	</body>
</html>


<?php

	}
	
	$userq->free_result();	
	$userq->close();
	
?>