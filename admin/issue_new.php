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
<div class="alert">Please verify that the issue does not already exist prior to entering a new one.</div>
<?php
session_start();
require_once('includes/loginid.php');
	//connection variables
	require_once('../includes/connect.php');
	
// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  GPL                                                                *
//                                                                              *
// ******************************************************************************
	
	
	
	$dbc = new mysqli($host, $username, $password, $dbname);


	if  ($dbc->connect_error) {
		die("Connection failed: " . $dbc->connect_error);
	}
	
	if (!isset($_SESSION['user_id'])) {
		echo 'You do not have access to this page';
		exit();
	  }
	
	
	//if form is submitted
	if (isset($_POST['submit'])) {
	
		//initialize issue variables 
		$issue_ed = $_POST['issue_ed'];
		$number = $_POST['number'];
		$volume = $_POST['volume'];
		$issue_length  = $_POST['issue_length']; 
		$year = $_POST['year']; 
		//$cover_id = $_POST['cover_id'];  

		$isi = $dbc->prepare("INSERT INTO issue (issue_id, issue_ed, number, volume, issue_length, year) VALUES (NULL,?,?,?,?,?)");
		$isi->bind_param("siiii",  $issue_ed, $number, $volume, $issue_length, $year);

		$isi->execute();
		$isi->close();
		
		echo "<script>window.close();</script>";
	
	}
	

	
	?>
	
	
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
	<legend>New Issue</legend>


	Issue Name:<br/>
	<input type="text" name="issue_ed" /><br/>
	Year:<br/>  
	<input type="text" name="year" /><br/>
	Volume: <br/> <!-- input - enter as number-->
	<input type="text" name="volume" /><br/>
	Number:<br/> <!-- option box?  should also have an explanation for what this is -->
	<input type="text" name="number" /><br/>
	Length:<br/>
	<input type="text" name="issue_length" /><br/>
	<p/>
	<input type="submit" name="submit" value="Add new issue" />



	</fieldset>
</form>

<?php
 mysqli_close($dbc);
?>
</body>
</html>