<?php
#
# TSmon by Chris Nelson
# Monitoring and control for Thinstation based thinstations.
# Copyrigth 2012 Chris Nelson, sleekmountaincat@gmail.com. Published under GNU License ver. 2
#


	$link = mysqli_connect("localhost","tsmon","tsmon123","tsmon");
	if(mysqli_connect_errno())
	{
		echo 'ошибка подключения к базе данных';
		exit();
	}
	$cmd       = ($_GET["cmd"]) ? $_GET["cmd"] : $_POST["cmd"];
	$ts         = implode(",", $_POST["tsselect"]);
	mysqli_query($link,"UPDATE ts SET command='$cmd' WHERE id IN ($ts)");


	mysqli_close($link);

	header("Location: admin.php");
?>

	
