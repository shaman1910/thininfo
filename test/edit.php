<?php
#
# TSmon by Chris Nelson
# Monitoring and control for Thinstation based thinstations.
# Copyrigth 2012 Chris Nelson, sleekmountaincat@gmail.com. Published under GNU License ver. 2
#


      $link = mysqli_connect("localhost","tsmon","tsmon123","tsmon");


	$save     = $_POST["save"];
	$data     = str_replace(array("\r\n", "\r", "\n"), "\n", strip_tags($_POST['data']));
	$id       = ($_GET["id"]) ? mysqli_real_escape_string($link,$_GET["id"]) : mysqli_real_escape_string($link,$_POST["id"]);
	$row      = mysqli_fetch_array(mysqli_query($link,"SELECT * FROM ts WHERE id='$id'"));
	$filename = "configs/thinstation.conf-" . str_replace(":", "", $row["mac"]);
	$mac      = $row["mac"];
    $hostname = $row["hostname"];

	mysqli_close($link);	

	if($save) {
		file_put_contents($filename, $data);
		header("Location:edit.php?id=$id");
	} else {
	

		if(is_file($filename)) {
			$file = file_get_contents($filename);
		} else { $file = ""; }
?>

<html>
<head>
    
<base href="tsmon" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>TSMon: Редактировать по MAC</title>
<style type="text/css">
table.gridtable {
        font-family: verdana,arial,sans-serif;
        font-size:11px;
        color:black;
        border-width: 1px;
        border-color: black;
        border-collapse: collapse;
}
table.gridtable th {
        border-width: 1px;
        padding: 4px;
        border-style: solid;
        border-color: black;
        background-color: #a8a8a8;
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

<?php
		

		echo "<br><br><center><form action=edit.php method=post>";
		echo "<table class=gridtable>";
		echo "<th colspan=2>Клиент $hostname с MAC - адресом $mac";
		echo "<tr>";
		echo "<td colspan=2><textarea name=data>$file</textarea>";
		echo "<tr><td><input type=submit name=save value=\"Сохранить изменения\">";
		echo "<input type=hidden name=id value=\"$id\"></form></table>";
	}

?>

</body>
</html>
