<?php
	date_default_timezone_set("Europe/Moscow");
	$photo = simplexml_load_file("photo.xml");
	// print_r($photo);
	$res = array();
	foreach ($photo->photo as $key => $value) {
		$active = (bool) (string) $value['active'];
		// var_dump($value);
		if ($active) {
			/*$now = new DateTime("now");
			$date = new DateTime($value['id']);
			if ($now < $date) {*/
				$curr = array();
				$curr['time'] = (string) $value['id'];
				$curr['source'] = (string) $value->source;
				$curr['type'] = (string) $value['type'];
				$curr['title'] = (string) $value->title;
				$res[] = $curr;
			/*} else {
				$value['active'] = "False";
			}*/
		}
	}

	// usort($res, "cmp");
	$res["number"] = count($res);
	// echo "<pre>".print_r($res, true)."</pre>";
	echo json_encode($res);
?>