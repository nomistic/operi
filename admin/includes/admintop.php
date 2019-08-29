<?php
// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  GPL                                                                *
//                                                                              *
// ******************************************************************************

echo '<div class="top"><span id="adminhead"><a href="."><h3>&nbsp;'. $pubtitle . ' Archive Administration</h3></a><span></div>';

echo '<nav class="navbar navbar-dark bg-dark">';

		echo '<ul>';
			echo '<li><a href="article_new.php">Add a new article</a></li>';
			
			echo '<li><a href="creators.php">Manage Creators</a></li>';

			if ($_SESSION['admin'] == 1) {
				echo '|  <li><a href="useradmin.php">Manage Users</a></li>';
				
				echo '<li><a href="types.php">Manage Types</a></li>';
				
				echo '<li><a href="site.php">Site Management</a></li>';
				
			}	
		echo '</ul>';			
echo '</nav>';



?>