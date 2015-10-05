<?php
date_default_timezone_set("Europe/Moscow");
	$dir = scandir("modules/");
	$ret = array();
	foreach ($dir as $v){
		if ($v[0] != "."){
			if (file_exists("modules/".$v."/cron.php")){
				$path = "modules/".$v."/";
				include "modules/".$v."/cron.php";
			}
		}
	}
?>