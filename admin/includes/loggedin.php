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
		echo '<p class="login"><a href="login.php">log in</a></p>';
		exit();
	  }
	  else {
		echo('<nav class="navbar navbar-dark bg-dark"><ul><li>Logged in: <span class="alert">' . $_SESSION['user_name'] . '</span></li><li><a href=".">Back to Admin page</a></li> <li><a href="logout.php">Log out</a></li></ul></nav>');
	  }

?>