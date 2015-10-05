<?php
$text = <<<TXT
<div class="carousel-inner" role="listbox">
</div>
TXT;
echo json_encode(array(
	text => $text
));
?>