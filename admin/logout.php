<?php

	session_start();
	//adding session variables to the logout script
	
// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  GPL                                                                *
//                                                                              *
// ******************************************************************************


	if (isset($_SESSION['user_id'])) {

	//delete all session variables
		$_SESSION = array();


	//if user is logged in, delete the cookie to log them out


		if (isset($_COOKIE[session_name()])) {

		setcookie(session_name(),'',time() - 3600);
		}
	//destroy the session
		session_destroy();
	}

	//delete user cookies

	setcookie('user_id','',time() - 3600);
	setcookie('user_name','',time() - 3600);

	//redirect 
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
	header('Location: ' . $home_url);

?>