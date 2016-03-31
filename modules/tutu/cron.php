<?php
include "config.php";
date_default_timezone_set('Europe/Moscow');

global $path;

$DOL = "s9600766"; // Dolgoprudniy
$NOV = "s9601261"; // Novodachnaya
$MRK = "s9602214"; // Mark
$TIM = "s9602463"; // Timeryasevskay

$STATIONS = array($DOL, $NOV, $MRK, $TIM);

// Формируем данные.
$now = new DateTime();
$ret = array(
    "data" => array(),
    "copyright" => array()
);

function getPage($url) {
    $API_URL = "https://api.rasp.yandex.net/v1.0/";

    $ch = curl_init();

    $pageUrl = $API_URL . $url;
    // установка URL и других необходимых параметров
    @curl_setopt($ch, CURLOPT_URL, $pageUrl);
    @curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt");
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // загрузка страницы и выдача её браузеру
    $page = @curl_exec($ch);

    // завершение сеанса и освобождение ресурсов
    curl_close($ch);

    return json_decode($page);
}

function setFlag($value) {
    global $path;
    $handler = fopen($path . "flag.txt", "wb");
    fwrite($handler, $value);
    fclose($handler);
}

// Нужно ли грузить данные.
function tryToGetThreads() {
    global $path;
    global $RASP_API;
    global $DOL;
    global $TIM;
    global $ret;

    $now = new DateTime();
    $flagFile = $path . "flag.txt";
    $handler = fopen($flagFile, "r");
    $allowGet = fread($handler, 1) == "1"; // "1" - for get, "0" - for miss
    fclose($handler);

    // Разрешаем загрузку только с 3:00 и до 3:30.
    $am3 = new DateTime($now->format("Y-m-d") . " 3:00:00");
    $am4 = new DateTime($now->format("Y-m-d") . " 23:50:00");

    if ($am3 < $now && $now < $am4) {
        if ($allowGet) {
            getThreads("search/?apikey=" . $RASP_API . "&from=" . $DOL . "&to=" . $TIM);
            $ret["copyright"] = getPage("copyright/?apikey=" . $RASP_API . "&format=json")->copyright;
            setFlag("0");
        }
    } else {
        setFlag("1");
    }
}

// Ближайшие рейсы.
function closestThreads($thread) {
    $now = new DateTime("now");
    $departure = new DateTime($thread->departure);
    return ($now < $departure);
}

// Возращаем для отдельного рейса только UID и дату.
function getUids($thread) {
    $departure = new DateTime($thread->departure);
    return array(
        "uid" => $thread->thread->uid,
        "date" => $departure->format("Y-m-d")
    );
}

// Получаем список рейсов по АПИ.
function getThreads($url) {
    global $path;
    // Поиск поездов от Долгопрудной до Тимерязевской

    $now = new DateTime();
    $tomorrow = new DateTime("tomorrow");

    // Подгружаем результаты на сегодня и завтра, чтобы захватить
    $resultToday = getPage($url . "&lang=ru&format=json&transport_types=suburban&date=" . $now->format("Y-m-d"));
    $resultTomorrow = getPage($url . "&lang=ru&format=json&transport_types=suburban&date=" . $tomorrow->format("Y-m-d"));

    $result = array_merge($resultToday->threads, $resultTomorrow->threads);

    $handle = fopen($path . "today.txt", "wb");
    fwrite($handle, json_encode($result));
    fclose($handle);
}

// Считываем записи.
function readThreads() {
    global $path;

    $handle = fopen($path . "today.txt", "r");
    $threads = fread($handle, filesize($path . "today.txt"));
    fclose($handle);

    return json_decode($threads);
}

// Фильтруем станции.
function needStations($stop) {
    global $STATIONS;
    return in_array($stop->station->code, $STATIONS);
}

tryToGetThreads();

$dataFromToday = readThreads();
// Получаем UID нитей.
$threads = array_map("getUids", array_values(array_filter($dataFromToday, "closestThreads")));

for ($i = 0; $i < 4; $i++) {
    $j = $i + 1;
    $ret["data"][$j] = array();

    $thread = $threads[$i];

    $fileUid = $path . "tmp/" . $thread["uid"] . ".txt";

    $fileExits = file_exists($fileUid);
    $dateFile = new DateTime();

    if ($fileExits) {
        $handler = fopen($fileUid, "r");
        $dataFile = fread($handler, filesize($fileUid));
        fclose($handler);
        $infoThread = json_decode($dataFile);

        $dateFile = new DateTime($infoThread->date);
    }

    $stops = array();

    // Если файла нет или сейчас больше чем дата в файле, то грузим по АПИ.
    // Должно выполняться раз в сутки.
    if ($now > $dateFile || !$fileExits) {
        // Информация по отдельному рейсу.
        $threadApi = getPage("thread/?apikey=" . $RASP_API . "&format=json&uid=" .
            $thread["uid"] . "&lang=ru&date=" . $thread["date"]);
        $stops = array_values(array_filter($threadApi->stops, "needStations"));

        $toDataFile = array(
            "date" => $now->format("Y-m-d") . " 23:59:59",
            "stops" => $stops
        );

        $handler = fopen($fileUid, "w+");
        fwrite($handler, json_encode($toDataFile));
        fclose($handler);
    } else {
        $stops = $infoThread->stops;
    }


    foreach ($stops as $stop) {
        $arrival = new DateTime($stop->arrival);
        $departure = new DateTime($stop->departure);
        $time = $departure->format("H:i");
        $index = array_search($stop->station->code, $STATIONS) + 1;
        if ($index && $departure > $arrival) {
            $ret["data"][$j][$index] = $time;

            // Время ожидания для Долгопрудной и Новодачной.
            if ($stop->station->code == $DOL || $stop->station->code == $NOV) {
                $wait = $now->diff($departure);
                $minutes = $wait->h * 60 + $wait->i;
                $ret["data"][$j][$index * 10 + 1] = $minutes < 100 ? $minutes : "";
            }
        } else {
            $ret["data"][$j][$index] = "&mdash;";
        }
    }
}

$handle = fopen ($path . "res/data.txt", "wb");
fwrite ($handle, json_encode($ret));
fclose($handle);

?>
