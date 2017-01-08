<html>

<head>

<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
<script>
    window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }
</script>
</head>
<body>

<div class="alert">Please verify that the header does not already exist prior to entering a new one.</div>
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


	require_once('includes/loginid.php');
	require_once('../includes/connect.php');
	
	if (!isset($_SESSION['user_id'])) {
		echo 'You do not have access to this page';
		exit();
	  }
	  

		$dbc = new mysqli($host, $username, $password, $dbname);


		if  ($dbc->connect_error) {
			die("Connection failed: " . $dbc->connect_error);
		}
	
	if (isset($_POST['hsubmit'])) {
	
		$header_name = $_POST['header_name'];
	
		
		$h = $dbc->prepare("INSERT into header (header_id, header_name)
							 VALUES (NULL, ?)");
		$h->bind_param("s", $header_name);
		$h->execute();
		$h->close();
		
		echo "<script>window.close();</script>";
	}



?>
<p/>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset><legend>New Table of Contents Header:</legend>
<p/>
Header:<br/>
<input type="text" name="header_name" id="header_name" size= 30 />

<input type="submit" name="hsubmit" value="Add Header" />


	</fieldset>

</form>
<?php
 mysqli_close($dbc);
?>
</body>
</html>