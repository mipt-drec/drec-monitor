<?php
date_default_timezone_set("Europe/Moscow");
	$dir = scandir("modules");
	$ret = array();
	foreach ($dir as $v){
		if ($v[0] != "."){
			$tmp = array(name => $v, version => join("", file("modules/".$v."/version")));
			$ret[] = $tmp;
		}
	}
	$ret[number] = count($ret);
	echo json_encode($ret);
?>