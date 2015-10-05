<?php
date_default_timezone_set("Europe/Moscow");
$text = <<<TXT
<span class="fright" id="curr-date-time">
<span id="curr-date"></span>
<span class="sun-flower" id="curr-time"></span>
</span>
TXT;
echo json_encode(array(
	text => $text,
	unixtime => time()
));
?>