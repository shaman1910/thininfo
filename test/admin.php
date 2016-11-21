<?php
#
# TSmon by Chris Nelson
# Monitoring and control for Thinstation based thinstations.
# Copyrigth 2012 Chris Nelson, sleekmountaincat@gmail.com. Published under GNU License ver. 2
#

	$link = mysqli_connect("localhost","tsmon","tsmon123","tsmon");
	

	$sort    = $_GET["sort"];
	$cursort = $_GET["cursort"];
	$ord     = $_GET["ord"];

	switch($sort) {
		case "ident":
			$order = " ident ";
			break;
		case "mac":
			$order = " mac ";
			break;
		case "hostname":
			$order = " hostname ";
			break;
		case "ts_version":
			$order = " ts_version ";
			break;
		case "session0":
			$order = " session0 ";
			break;
		case "ram":
			$order = " ram_per ";
			break;
		case "cpu":
			$order = " cpu ";
			break;
		case "uptime":
			$order = " cast(uptime as decimal) ";
			break;
		case "boot_cnt":
			$order = " boot_cnt ";
			break;
		case "last_boot":
			$order = " last_boot ";
			break;
		case "last_checkin":
			$order = " last_checkin ";
			break;
		case "first_seen":
			$order = " first_seen ";
			break;
		case "ip":
			$order = " ip ";
			break;
		default:
			$sort  = "mac";
			$order = " mac ";
	}


	if($cursort==$sort) { 
		$ord = !$ord;
	}
	if(!$ord) { 
		$dir = " ASC";
	} else {
		$dir = " DESC";
	}
	
	$order .= $dir;
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <title> Thinstation info</title>
        <link rel="stylesheet" href="style.css" />
        <link href="images/icon.png" rel="shortcut icon" type="image/x-icon" />
        <script type="text/javascript" src="/tsmon/test/js/jquery-1.7.1.js"></script>
        <script type="text/javascript">
            function toggle(source) {
                var checkboxes = document.getElementsByName('tsselect[]');
                for (var i = 0; i < checkboxes.length; i++) checkboxes[i].checked = source.checked;
            }

            function command(id) {
                cmd = prompt("Введите команду:", "");
                if (cmd) {
                    href = "cmd.php?id=" + id + "&cmd=" + cmd;
                    window.location = href;
                }
            }

            function sendcmdmult() {
                cmd = prompt("Введите команду:");
                if (cmd) {
                    var data = {
                        'tsselect[]': []
                        , 'cmd': cmd
                    };
                    $("input:checked").each(function () {
                        data['tsselect[]'].push($(this).val());
                    });
                    $.ajax({
                        type: "POST"
                        , url: "cmdmult.php"
                        , data: data
                    });
                    window.location = "admin.php";
                    return false;
                }
            }
        </script>
    </head>

    <body>
        <header>
            <div class="headerleft">
                <a href="admin.php"><img src="images/logo.png" height="50px" align="left"></a>
            </div>
            <div class="headerright">
                <?php
            $res = mysqli_query($link,"SELECT COUNT(1) FROM ts");
            $allklient = mysqli_fetch_array($res);            
            $res = mysqli_query($link,"SELECT COUNT(1) FROM ts WHERE last_checkin > (NOW() - INTERVAL 40 SECOND)");
            $online = mysqli_fetch_array($res);            
            $res = mysqli_query($link,"SELECT COUNT(1) FROM ts WHERE last_checkin < (NOW() - INTERVAL 40 SECOND)");
            $offline = mysqli_fetch_array($res);
            echo "Общее кол-во $allklient[0] Онлайн $online[0] Оффлайн $offline[0]";
            echo "<br> Время на сервере: ";
            echo date("Y-m-d H:i:s"); 
            ?>
            </div>
        </header>
        <div class="tablica">
            <table border="1" , width="100%">
                <tr>
                    <td>
                        <div class="klienti">
                            <form id="klient" method=post action=cmdmult.php>
                                <input type="checkbox" onClick="toggle(this)">Выбрать всех
                                <br>
                                <?php
            # Список клиентов (с права)
                $result = mysqli_query($link,"SELECT * FROM `ts` WHERE 1 ORDER BY `hostname`");
                $cnt = 1;
                while($row = mysqli_fetch_array($result)) {	
                    echo "<input type=checkbox name=\"tsselect[]\" value=\"" . $row["id"] . "\">";
                    if ($row["last_checkin"] > date("Y-m-d H:i:s", time() - 35)) {
                         echo "<a href=vnc.php?ip=" . $row["ip"] . " target=_blank>"; 
                        echo "<img src=images/online.png width=18 height=18 border=0 onclick=\"return confirm('Подключиться к клиенту по VNC?')\" title=\"VNC\"></a>";
                                                 }
                        else {
                            echo '<img src="images/offline.png" width=16 height=16>';
                            }
                        echo "<a target=\"frameall\" href='frameall.php?id=" . $row["id"] .  "'>$row[hostname]</a>", '<br>';
                    }            
            ?>
                            </form>
                        </div>
                    </td>
                    <td rowspan="2" valign="top" width="60%">
                        <div>
                            <iframe allowtransparency frameborder="0" src="frameall.php" name="frameall" height="600" width="100%"> </iframe>
                        </div>
                    </td>
                    <td rowspan="2" valign="top" valign="right" width="20%">
                        <div align="top">
                            <select name="menu3" onchange="top.location.href = this.options[this.selectedIndex].value;">
                                <option value="edit-network.php" selected>Редактировать thinstation.conf.network</option>
                                <option value="edit-group.php">Редактировать thinstation.conf.group</option>
                                <option value="edit-hosts.php">Редактировать thinstation.hosts</option>
                                <option disabled selected>Выберете файл для редактирования</option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input form="klient" type="text" name=cmd size="40" value='export DISPLAY=:0; Xdialog --msgbox "             " 0 0 0'>
                        <input form="klient" type="submit" value="Отправить">
                        <br>
                        <input form="klient" type=submit name=cmd value="poweroff" onclick="return confirm('Выключить клиентов?')">
                        <input form="klient" type=submit name=cmd value="reboot" onclick="return confirm('Перезагрузить клиентов?')"> </td>
                </tr>
            </table>
            <br>
            <div align="left"> </div>
        </div>
    </body>

    </html>