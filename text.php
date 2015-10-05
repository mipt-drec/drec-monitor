<?php
date_default_timezone_set("Europe/Moscow");
$text = <<<TXT
<span class="fleft" id="weather"></span>
<span class="fright" id="curr-date-time"></span>
TXT;
echo json_encode(array(
	text => $text,
	unixtime => time()
));
?>