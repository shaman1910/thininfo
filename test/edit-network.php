<?php

        $save     = $_POST["save"];
	$data     = str_replace(array("\r\n", "\r", "\n"), "\n", strip_tags($_POST['data']));
	$filename = "configs/thinstation.conf.network";
	
	if($save) {
		file_put_contents($filename, $data);
		header("Location: edit-network.php");
	} else {
	

		if(is_file($filename)) {
			$file = file_get_contents($filename);
		} else { $file = ""; }
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<title>TSMon: Редактировать thinstation.conf.network</title>
<style type="text/css">
table.gridtable {
        font-family: verdana,arial,sans-serif;
        font-size:11px;
        color:black;
        border-width: 1px;
        border-color: black;
        border-collapse: collapse;
}
table.gridtable td {
        border-width: 1px;
        font-size:9px;
        padding: 8px;
        border: none;
	background-color: #ffffff;
        text-align: center;
}
textarea {
        font-family: verdana,arial,sans-serif;
	width: 700px;
	height: 400px;
        font-size:11px;
	border: solid black 2px;
}
</style>
</head>
<body>
<br>
<h3 style="text-align: center;"><strong>Редактирование thinstation.conf.network</strong></h3>
<?php

		echo "<br><br><center><form action=edit-network.php method=post>";
		echo "<table class=gridtable>";
		echo "<tr>";
		echo "<td colspan=2><textarea name=data>$file</textarea>";
		echo "<tr><td><input type=submit name=save value=\"Сохранить изменения\">";
		echo "<td><input type=button name=cancel value=\"Выйти\" onclick=\"javascript: window.location='admin.php'\"></form></table>";
	}

?>

</body>
</html>
