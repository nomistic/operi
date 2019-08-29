<!DOCTYPE html>
<html>
<head>
	<title><?php echo $pubtitle; ?></title>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/digpub.css" />
	<link rel="stylesheet" type="text/css" href="css/custom.css" />
</head>
<body>
<?php
// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  Personal                                                           *
//                                                                              *
// ******************************************************************************

echo '<header>';
//	<span><h2>The Falconer Online</h2></span> 

?>

<div class="search">
	<form method="post" action="search.php">
		<fieldset>
		<input type="text" name="keyword" size="50" placeholder="Search <?php echo $pubtitle; ?>"/>  <input type="submit" name="submit" value="Search" />
		</fieldset>
	</form>
</div>
<a href=".">Falconer Main Page</a>
</p>
<?php
echo '</header>';
echo '<nav class="navbar navbar-dark bg-dark"><ul><li><a href=".">Home</a></li> <li><a href="type.php">Content Type</a></li><li><a href="clist.php">Author</a></li><li><a href="slist.php">Subject</a></li></ul></nav>';

?>