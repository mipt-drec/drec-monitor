<?php

$nowday = $day_names[$today];
$text = <<<TXT
<span class="fleft" id="weather">
<span class="sun-flower">
<span id="curr-temp">+0</span>°
</span>
<span>C</span>
<span id="curr-weather-icon"></span>
</span>
TXT;
echo json_encode(array(
	html => $text
));
?>