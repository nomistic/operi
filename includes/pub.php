<?php


	$pub = $dbc->query("select title from pub where yend > curdate()");
	$row = $pub->fetch_assoc();
	$pubtitle = $row['title'];


?>