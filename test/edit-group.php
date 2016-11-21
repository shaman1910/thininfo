<?php

	$open     = $_POST["open"];
        $save     = $_POST["save"];
	$data     = str_replace(array("\r\n", "\r", "\n"), "\n", strip_tags($_POST['data']));
	$id	  = $_GET["id"];
	if($id != 1) {
		$ext = $_POST["ext"];
	} else {
		$ext     = $_GET["ext"];
	}
	$filename = "configs/$ext";

	
	if($save) {
		file_put_contents($filename, $data);
		header("Location: edit-group.php");
	} else {
	

		if(is_file($filename)) {
			$file = file_get_contents($filename);
		} else { $file = ""; }
?>
    <html>

    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        <title>TSMon: Редактировать thinstation.conf.group</title>
        <style type="text/css">
            table.gridtable {
                font-family: verdana, arial, sans-serif;
                font-size: 11px;
                color: black;
                border-width: 0px;
                border-color: black;
                border-collapse: collapse;
            }
            
            table.gridtable td {
                border-width: 1px;
                font-size: 9px;
                padding: 8px;
                border: none;
                background-color: #ffffff;
                text-align: center;
            }
            
            textarea {
                font-family: verdana, arial, sans-serif;
                font-size: 12px;
                border: solid black 1px;
            }
        </style>
    </head>

    <body>
        <?php

		echo "<center><form action=edit-group.php method=post>";
		echo "<h3>Редактирование $ext</h3>";
		echo "<table class=gridtable>";
		echo "<tr>";
		echo "<td colspan=2><textarea rows=20 cols=95 name=data>$file</textarea>";
		echo "<tr>";
		echo "<td colspan=3><textarea placeholder='Для создания нового конфигурационнного файла группы введите имя...' cols=40 name=ext>$ext</textarea>";
		echo "<tr><td><center><input type=submit name=open value=\"Открыть файл\">";
		echo "<tr><td><center><input type=submit name=save value=\"Создать файл или сохранить изменения\">";
		echo "<tr><td><center><input type=button name=cancel value=\"Выйти\" onclick=\"javascript: window.location='admin.php'\"></form></table>";
	}
?>
            <h4>Имеющиеся групповые файлы:</br></br>
<?php
if ($handle = opendir('configs/'))
{
 while (false !== ($file = readdir($handle)))
        {
 if(preg_match('/\.(group)/', $file))
	{			
$arResult['ITEMS'][]=array('NAME'=>$file,'TYPE'=>filetype('configs/'.$file));
	}
	}    
        closedir($handle);
	}
	?>

<div>

<?php foreach($arResult['ITEMS'] as $arItem):?>
<li>
<a href="edit-group.php/?id=1\&ext=<?=''.$arItem['NAME']?>"><?=$arItem['NAME']?></a>
</li>
<?php endforeach?>

</div>
</h4> </body>

    </html>