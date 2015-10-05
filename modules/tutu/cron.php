<?php

// инициализация сеанса
$ch = curl_init();

// установка URL и других необходимых параметров
@curl_setopt($ch, CURLOPT_URL, "http://tutu.mipt.ru");
@curl_setopt($ch, CURLOPT_HEADER, 1);
@curl_setopt($ch, CURLOPT_COOKIE, "showstations=dol%2Cnov%2Cmar%2Ctim");
@curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt"); 
@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
@curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
// загрузка страницы и выдача её браузеру
$page = @curl_exec($ch);

// завершение сеанса и освобождение ресурсов
curl_close($ch);


$page = iconv("CP1251", "UTF-8", $page);
$page = str_replace("\n", "", $page);
$page = explode('<td align="left" valign="middle" style="padding-top: 6px; padding-bottom: 8px; padding-left: 10px;">', $page);
$page = $page[1];
preg_match_all("/<tr>.*?<td.*?>([0-9:&nbsp;]+?)<\/td>.*?<td.*?>([0-9:&nbsp;]+?)<\/td>.*?<td.*?>([0-9:&nbsp;]+?)<\/td>.*?<td.*?>([0-9:&nbsp;]+?)<\/td>.*?<td.*?>(.*?)<\/td>.*?<\/tr>/", $page, $m);
$ret = array(data=>array());
for ($j=0; $j<4; $j++){
	$i = $j+1;
	$ret[data][$i] = array(
		1 => $m[1][$j],
		2 => $m[2][$j],
		3 => $m[3][$j],
		4 => $m[4][$j],
		21 => $m[5][$j],
	);
	if (strpos($ret[data][$i][21], "ч")){
		$ret[data][$i][21] = "";
	}else{
		$ret[data][$i][21] = explode(" ", $ret[data][$i][21]);
		$ret[data][$i][21] = $ret[data][$i][21][0];
	}
}
 $handle = fopen ($path."res/data.txt", "wb");
 fwrite ($handle, json_encode($ret));
 fclose($handle);
?>