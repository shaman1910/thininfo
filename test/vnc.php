<?php

$ip = $_GET["ip"];
$pass = admin123;
header("Location: http://$ip:6080/vnc.html?host=$ip&port=6080&password=$pass&autoconnect=true");
	
?>

