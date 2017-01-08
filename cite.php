<!DOCTYPE html>
<html>
<head>
<title>MLA Citation</title>
<link rel="stylesheet" type="text/css" href="css/digpub.css" />
<link rel="stylesheet" type="text/css" href="css/custom.css" />
</head>
<body>


<?php

	require_once('includes/connect.php');
    include_once('includes/pub.php');

	//connect to the database
	$dbc = new mysqli($host, $username, $password, $dbname);
	
	if ($dbc->connect_error) {
		die("Connection failed: " . $dbc->connect_error);
		}

	$article_id = $_GET['article_id'];
	
	$article = $dbc->prepare("SELECT a.article_title, 
							  c.creator_id,
							  c.first_nm, 
							  c.middle,
							  c.last_nm,
							  i.number,
							  i.volume,
							  i.year,
							  a.arange
							  FROM article a
							  JOIN creator c
							  ON a.creator_id = c.creator_id
							  JOIN type t
							  ON a.type_code = t.type_code
							  JOIN issue i
							  ON a.issue_id = i.issue_id
							  WHERE article_id = ?");
	$article->bind_param("i", $article_id);
	$article->execute();
	$article->bind_result($article_title, $creator_id, $first_nm, $middle, $last_nm,  $number, $volume, $year,$arange);
	$article->fetch();
	$article->close();	
	
	echo '<h3>MLA Citation</h3>';
	echo $last_nm.', '.$first_nm;
		if (!empty($middle)) { echo ' '.$middle.'. ';}
		else {echo '. ';}
	echo '"'.$article_title.'." '. '<em>'.$pubtitle.'</em> '.$volume.'.'.$number.' ('.$year.'): '.$arange.' Web. '.date('d M Y.');

?>
<p><a href=""  onclick="window.close();">Close</a></p>
</body>
</html>