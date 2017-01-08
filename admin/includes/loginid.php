<?php
// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  GPL                                                                *
//                                                                              *
// ******************************************************************************


if (!isset($_SESSION['user_id'])) {
	if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_name']) && isset($_COOKIE['admin'])) {
	
		$_SESSION['user_id'] = $_COOKIE['user_id'];
		$_SESSION['user_name'] = $_COOKIE['user_name'];	
		$_SESSION['admin'] = $_COOKIE['admin'];	
		
		$user_id = $_SESSION['user_id'];
		$user_name = $_SESSION['user_name'];
		$admin = $_SESSION['admin'];
	}
	else {
	$user_id ='';
	$admin ='';
	}
}
else {


	$user_id = $_SESSION['user_id'];
	$user_name = $_SESSION['user_name'];
	$admin = $_SESSION['admin'];


}

?>