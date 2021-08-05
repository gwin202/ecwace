<?php
	
	$db = new mysqli('localhost', 'root', '', 'ecwace');
	if($db->connect_errno){
		echo $db->connect_error;
			die("<br>sory we are having problem with the db connection");
		}
	
?> 