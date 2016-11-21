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
    $ram_per = $row["ram_per"];
    $cpu = $row["cpu"]*100;

	mysqli_close($link);	

	if($save) {
		file_put_contents($filename, $data);
		header("Location:frameall.php?id=$id");
	} else {
	

		if(is_file($filename)) {
			$file = file_get_contents($filename);
		} else { $file = ""; }
        }
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <title> Thinstation info</title>
        <link rel="stylesheet" href="style.css"> </head>

    <body>
        <div class="menu1">
            <br id="tab2" />
            <br id="tab3" /> <a href="#tab1">Информация о клиенте</a><a href="#tab2">Графический редактор</a><a href="#tab3">Текстовое редактирование</a>
            <div>
                <?php
        echo '<br>';
        echo " <fieldset style='width: 100%'>";
        echo "  <legend>Инфо о сетевых настройках</legend>";
            echo "ip адрес:  "; echo $row["ip"];
            echo '<br>';
            echo "mac адрес:  "; echo $row["mac"];
            echo '<br>';
        echo " </fieldset>"; 
        echo '<br>';
                    
         echo " <fieldset style='width: 100%'>";
         echo "  <legend> Инфо о загрузке системы  </legend>";
            list($tot, $used, $free) = preg_split("/,/", $row['ram']);  
            echo "CPU:"; echo " ";   echo " <progress value='$cpu' max='100'> </progress> "; echo $cpu . "%";
            echo '<br>';
            echo "RAM:"; echo  "<progress value='$used' max='$tot'> </progress> "; echo $row["ram_per"] . "%";
            echo '<br>';
            echo "  (Используется $used из $tot, свободно $free)";            
        echo " </fieldset>";  
        echo '<br>';
                    
         echo " <fieldset style='width: 100%'>";
         echo "  <legend> Параметры сессии  </legend>";
            echo "Имя хоста:  "; echo $row["hostname"];
            echo '<br>'; 
            echo "Группа хоста:  "; echo $row["ident"];
            echo '<br>';
            echo "Версия thinstation:  "; echo $row["ts_version"];
            echo '<br>';
            echo "Имя сессии:  "; echo $row["session0"];
            echo '<br>';
        echo " </fieldset>";   
        echo '<br>';
           
                    
        echo " <fieldset style='width: 100%'>";
        echo "  <legend>Статистика по времени </legend>";
            echo "Uptime:  "; echo sec2hms(round($row['uptime']));
            echo '<br>';
            echo "Последняя проверка:  ";	echo date("d/m/Y, H:i:s", strtotime($row['last_checkin'])); 
            echo '<br>';
            echo "Последняя загрузка:  "; echo date("d/m/Y, H:i:s", strtotime($row['last_boot']));
            echo '<br>';
            echo "Общее кол-во загрузок:  ";		 echo $row['boot_cnt'];
            echo '<br>';
            echo "Дата первой загрузки:  "; echo date("d/m/Y, H:i:s", strtotime($row['first_seen']));
        echo " </fieldset>";  
                    
  

                    
# Функция обработки  дат  
    function sec2hms($s) {
	$str = "";
	$d = intval($s/86400);
	$s -= $d*86400;
	$h = intval($s/3600);
	$s -= $h*3600;

	$m = intval($s/60);
	$s -= $m*60;

	if ($d) $str = $d . 'd ';
	if ($h) $str .= $h . 'h ';
	if ($m) $str .= $m . 'm ';
	if ($s) $str .= $s . 's';

	return $str;
}
      
?>
            </div>
            <div> </div>
            <div>
                <?php
		

		echo "<br><br><center><form action=edit.php method=post>";
		echo "<table class=gridtable>";
		echo "<th colspan=2>Клиент $hostname с MAC - адресом $mac";
		echo "<tr>";
		echo "<td colspan=2><textarea name=data>$file</textarea>";
		echo "<tr><td><input type=submit name=save value=\"Сохранить изменения\">";
		echo "<input type=hidden name=id value=\"$id\"></form></table>";
	
    
?>
            </div>
        </div>
    </body>

    </html>