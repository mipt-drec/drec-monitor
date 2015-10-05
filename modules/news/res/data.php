<?php

	function cmp ($a, $b) {
		if ($a['time'] == $b['time']) {
			return 0;
		}
		return ($a['time'] < $b['time']) ? -1 : 1;
	}
	date_default_timezone_set("Europe/Moscow");
	$news = simplexml_load_file("news.xml");

	$res = array();
	foreach ($news->news as $key => $value) {
		$active = (bool) (string) $value['active'];

		if ($active) {
			$now = new DateTime("yesterday");
			$date = new DateTime($value['date']);
			$end = new DateTime($value['end']);
			if ($now < $end) {
				$curr = array();
				$curr['time'] = $date->format("U");
				$curr['day'] = $date->format("j");
				$curr['month'] = $date->format("m");
				$curr['title'] = html_entity_decode((string) $value->title);
				$curr['text'] = html_entity_decode((string) $value->text);
				$res[] = $curr;
			} else {
				$value['active'] = "0";
				$news->asXML("news.xml");
			}
		}
	}

	usort($res, "cmp");
	$res["number"] = count($res);
	// echo "<pre>".print_r($res, true)."</pre>";
	echo json_encode($res);
?>