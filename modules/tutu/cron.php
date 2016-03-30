<?php
include "config.php";

$DOL = "s9600766"; // Dolgoprudniy
$NOV = "s9601261"; // Novodachnaya
$MRK = "s9602214"; // Mark
$TIM = "s9602463"; // Timeryasevskay

$STATIONS = array($DOL, $NOV, $MRK, $TIM);

date_default_timezone_set('Europe/Moscow');

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

    $now = new DateTime();
    $flagFile = $path . "flag.txt";
    $handler = fopen($flagFile, "r");
    $allowGet = fread($handler, 1) == "1"; // "1" - for get, "0" - for miss
    fclose($handler);

    // Разрешаем загрузку только с 3:00 и до 4:00.
    $am3 = new DateTime($now->format('Y-m-d') . ' 3:00:00');
    $am4 = new DateTime($now->format('Y-m-d') . ' 4:00:00');

    if ($am3 < $now && $now < $am4) {
        if ($allowGet) {
            getThreads("search/?apikey=" . $RASP_API . "&from=" . $DOL . "&to=" . $TIM);
            setFlag("0");
        }
    } else {
        setFlag("1");
    }
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

// Фильтруем станции.
function needStations($stop) {
    global $STATIONS;
    return in_array($stop->station->code, $STATIONS);
}

tryToGetThreads();

$threads = readThreads();
// Получаем UID нитей.
$uids = array_map("getUids", array_values(array_filter($threads, "closestThreads")));


// Формируем данные.
$ret = array(
    "data" => array()
);
for ($i = 0; $i < 4; $i++) {
    $j = $i + 1;
    $ret["data"][$j] = array();

    $uid = $uids[$i];

    // Информация по отдельному рейсу.
    $thread = getPage("thread/?apikey=" . $RASP_API . "&format=json&uid=" . $uid["uid"] . "&lang=ru&date=" . $uid["date"]);
    $stops = array_values(array_filter($thread->stops, "needStations"));

    foreach ($stops as $stop) {
        $arrival = new DateTime($stop->arrival);
        $departure = new DateTime($stop->departure);
        $time = $departure->format("H:i");
        $index = array_search($stop->station->code, $STATIONS) + 1;
        if ($index && $departure > $arrival) {
            $ret["data"][$j][$index] = $time;

            // Время ожидания для Долгопрудной и Новодачной.
            if ($stop->station->code == $DOL || $stop->station->code == $NOV) {
                $now = new DateTime();
                $wait = $now->diff($departure);
                $ret["data"][$j][$index * 10 + 1] = $wait->format("%i");
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
