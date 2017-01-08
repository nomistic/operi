<?php
    header("Content-type: text/css; charset: UTF-8");

    $iinfo = $dbc->prepare("select logo
						    from site_data");
	$iinfo->execute();
	$iinfo->bind_result($logo);
	$iinfo->store_result();
	$iinfo->fetch();
	
?>

/* edit this form to modify css styles */


header {

	height:120px;
	background-repeat:no-repeat;
	border-bottom:3px solid green;
}

header a {

	background-image: url("../images/<?php echo $logo ?>");
	display:block;
	height:110px;
	width:250px;
	overflow:hidden;
	text-indent:100%;
	white-space:nowrap;
}

#contents {


    font-size: .8em;
}

header a:hover {
	background-color:none;
}

