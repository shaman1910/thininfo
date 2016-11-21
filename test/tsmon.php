<?php
#
# TSmon by Chris Nelson
# Monitoring and control for Thinstation based thinstations.
# Copyrigth 2012 Chris Nelson, sleekmountaincat@gmail.com. Published under GNU License ver. 2
#



	$link = mysqli_connect("localhost","tsmon","tsmon123","tsmon");

	$mac        = mysqli_real_escape_string($link,$_GET["MAC"]);
	$ident      = mysqli_real_escape_string($link,$_GET["IDENT"]);
	$ip         = mysqli_real_escape_string($link,$_GET["IP"]);
	$bcast      = mysqli_real_escape_string($link,$_GET["BCAST"]);
	$hostname   = mysqli_real_escape_string($link,$_GET["HOSTNAME"]);
	$ts_version = mysqli_real_escape_string($link,$_GET["TS_VERSION"]);
	$session0   = mysqli_real_escape_string($link,$_GET["SESSION0"]);
	$ram        = mysqli_real_escape_string($link,$_GET["RAM"]);
	$cpu        = mysqli_real_escape_string($link,$_GET["CPU"]);
	$uptime     = mysqli_real_escape_string($link,$_GET["UPTIME"]);
	$mode       = mysqli_real_escape_string($link,$_GET["MODE"]);
      list($free, $used, $tot) = preg_split("/,/", $ram);
      $per = round(($used / $tot) * 100);

	if($mode=="boot") {
		$result = mysqli_query($link,"SELECT * FROM ts WHERE mac='$mac'");
		$row = mysqli_fetch_array($result);
		echo var_dump($row);
		if($row) { 
			$id = $row["id"];
			$sql  = "UPDATE ts SET ip='$ip', bcast='$bcast', hostname='$hostname', ts_version='$ts_version', ";
			$sql .= "session0='$session0', ram='$ram', cpu='$cpu', uptime='$uptime', last_boot=NOW(), ";
			$sql .= "last_checkin=NOW(), boot_cnt=boot_cnt+1, ident='$ident', command='', ram_per='$per' WHERE id='$id'";
			mysqli_query($link,$sql);
					
			
		}
		else { 
			$sql  = "INSERT INTO ts (mac, ip, bcast, hostname, ts_version, session0, ram, cpu, uptime, ";
			$sql .= "last_boot, last_checkin, first_seen, ident, ram_per) VALUES ('$mac', '$ip', '$bcast', '$hostname', ";
			$sql .= "'$ts_version', '$session0', '$ram', '$cpu', '$uptime', NOW(), NOW(), NOW(), '$ident', '$per')";
			mysqli_query($link,$sql);
		}
	} else if ($mode=="checkin") {
                $result = mysqli_query($link,"SELECT * FROM ts WHERE mac='$mac'");
                $row = mysqli_fetch_array($result);
		$cmd = $row["command"];
                $id = $row["id"];
		if($cmd) {
			echo "TSMON:$cmd\n";
		}
                $sql  = "UPDATE ts SET ip='$ip', bcast='$bcast', hostname='$hostname', ts_version='$ts_version', ";
                $sql .= "session0='$session0', ram='$ram', cpu='$cpu', uptime='$uptime', ";
                $sql .= "last_checkin=NOW(), ident='$ident', command='', ram_per='$per' WHERE id='$id'";
                mysqli_query($link,$sql);
	}

      	mysqli_close($link);

?>
