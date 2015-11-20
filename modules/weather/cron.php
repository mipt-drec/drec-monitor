<?php
// инициализация сеанса
$ch = curl_init();

// установка URL и других необходимых параметров
@curl_setopt($ch, CURLOPT_URL, "http://api.openweathermap.org/data/2.5/weather?q=Dolgoprudniy&lang=ru&units=metric&appid=c99cd50b0e98e47e67d497d48f135eaa");
@curl_setopt($ch, CURLOPT_HEADER, 0);
@curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt"); 
@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
@curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
// загрузка страницы и выдача её браузеру
$page = @curl_exec($ch);

// завершение сеанса и освобождение ресурсов
curl_close($ch);
//$page = join("", file("http://api.openweathermap.org/data/2.5/weather?q=Dolgoprudniy&lang=ru&units=metric"));
$data = json_decode($page);
$temp = str_replace(".", ",", 0.1*round($data->main->temp*10));
$ret = array(
	"temp" => ($data->main->temp > 0) ? "+".$temp : $temp,
	"ico" => substr($data->weather[0]->icon, 0, 2).".png",
	"wind" => round($data->wind->speed),
	"water" => $data->main->humidity
);
 $handle = fopen ($path."res/data.txt", "wb");
 fwrite ($handle, json_encode($ret));
 fclose($handle);
?>
