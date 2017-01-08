<?php
// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  GPL                                                                *
//                                                                              *
// ******************************************************************************


$cq = "SELECT u.user_name, u.email 
       FROM user u
	   JOIN contact c
	   ON u.user_id = c.contact_id";
$contactu = $dbc->prepare($cq);
$contactu->execute();
$contactu->bind_result($user_name,$cemail);
$contactu->fetch();
$contactu->close();


$sl = "SELECT resetl, ldom_ind, ldom FROM site_data";
$resetlq = $dbc->query($sl);

while ($row  = $resetlq->fetch_assoc()) {
	$reset = 'http://'.$row['resetl'].'/admin/reset.php';
	$ldom_ind = $row['ldom_ind'];
	$em_base = $row['ldom'];
}

if ($ldom_ind ==1) {

	$contact = $user_name.'@'.$em_base;	
}
else {

	$contact = $cemail;		
}



?>