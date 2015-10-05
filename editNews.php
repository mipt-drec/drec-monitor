<?php
date_default_timezone_set("Europe/Moscow");
$error = "";
$success = "";
$srcNews = "./modules/news/res/news.xml";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	include 'config.php';
	$flagError = false;
	// $outPOST = "<pre class=''>".print_r($_POST, true)."</pre>";
	$delPhoto = array();
	if ($_POST['passwd'] == "") {
		$flagError = true;
		$error = "Введите пароль.";
	}
	foreach ($_POST as $key => $value) {
		if (!$value) {
			$error = "Заполните все поля.";
			$flagError = true;
			break;
		} else {
			$_SESSION[$key] = $value;
		}
	}

	if (!$flagError) {
		if ($_POST['passwd'] == $passwd) {
			$allnews = simplexml_load_file($srcNews);
			$curr = $allnews->xpath("//news[@id='".$_POST['id']."']")[0];
			$active = ($_POST['active'] == "on") ? "True" : 0;
			$title = htmlentities(htmlentities($_POST['title']));
			$text = htmlentities(htmlentities($_POST['text']));
			try {
				$date = new DateTime($_POST['date']);
				$date = $date->format("Y-m-d");
				if (isset($_POST['dateend'])) {
					$end = new DateTime($_POST['dateend']);
					$end = $end->format("Y-m-d");
				} else {
					$end = $date;
				}
			} catch (Exception $e) {
				$error = "Неправильная дата.";
				$date = "";
				$end = "";
			}
			if ($date && $end) {
				$curr['active'] = $active;
				$curr['date'] = $date;
				$curr['end'] = $end;
				$curr->title = $title;
				$curr->text = $text;
				$allnews->asXML($srcNews);
				$success = "Сохранено.";
			} else {
				$error = "Something wrong! :56";
			}
			unset($_SESSION);
		} else {
			$error = "Wrong password!";
		}
	}
}
if ($error) {
	$error = '<p class="help-block bg-danger">'.$error.'</p>';
}
if ($success) {
	$success = '<p class="help-block bg-success">'.$success.'</p>';
}
$divnews = "";
$allnews = simplexml_load_file($srcNews);
$res = array();
$count = 0;
$outPOST = "<pre class='hidden'>".print_r(count($allnews->news), true)."</pre>";
foreach ($allnews->news as $key => $value) {
	$active = ((bool) ((string) $value['active'])) ? "Да" : "Нет";
	$count += 1;
	$curr = array();
	$curr['id'] = (string) $value['id'];
	$curr['title'] = html_entity_decode((string) $value->title);
	$curr['text'] = html_entity_decode((string) $value->text);
	$curr['date'] = (string) $value['date'];
	$curr['end'] = (string) $value['end'];
	$res[] = $curr;
	$divnews .= "<div class='col-lg-4 edit-news' id='" . $curr['id'] . "'>" . 
		"<h3 class='curr-title'>" . $curr['title'] . "</h3>" . 
		"<p class='curr-text'>" . $curr['text'] . "</p><hr>" . 
		"<p><b>Дата мероприятия:</b> <span class='curr-date'>" . $curr['date'] . "</span></p>" . 
		"<p><b>Дата удаления:</b> <span class='curr-end'>" . $curr['end'] . "</span></p>" . 
		"<p><b>Активна:</b> <span class='curr-active'>" . $active . "</span></p>" . 
		"</div>";

	if ($count % 3 == 0) {
		$divnews .= "</div><hr><div class='row'>";
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Edit News</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/datepicker.css">
	<link rel="stylesheet" href="css/editor.css">
	<link rel="icon" type="image/x-icon" href="img/favicon.png">
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/edit.news.js"></script>
</head>
<body>
	<div class="container">
		<?=$outPOST?>
		<h1>Редактирование новостей <small>информационный монитор ФРТК</small></h1>
		<hr>
		<?=$error?>
		<?=$success?>
		<div id="edit-news">
			<div class="row">
				<?=$divnews?>
			</div>
			<hr>
		</div>
		<div class="row">
			<form class="col-lg-6 hidden" method="post" id="form-news">
				<div class="form-group">
					<label for="title">Заголовок*</label>
					<input name="title" type="text" class="form-control" id="title" value="<?=$_SESSION['title']?>" required>
				</div>
				<div class="form-group">
					<label for="text">Тело сообщения*</label>
					<textarea name="text" id="text" rows="4" class="form-control" required><?=$_SESSION['text']?></textarea>
				</div>
				<div class="row">
					<div class="col-lg-4">
						<div class="form-group">
							<label for="date">Дата мероприятия*</label>
							<input name="date" type="text" class="form-control" id="date" placeholder="yyyy-mm-dd" value="<?=$_SESSION['date']?>" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
						</div>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
							<label for="end">Дата удаления</label>
							<input name="dateend" type="text" class="form-control" id="end" placeholder="yyyy-mm-dd" value="<?=$_SESSION['dateend']?>" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
						</div>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
							<label for="passwd">Пароль редактора*</label>
							<input name="passwd" type="password" class="form-control" id="passwd" required>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox" name="active" id="active">
								</span>
								<label for="active" class="form-control">Новость активна?</label>
							</div>
						</div>
					</div>
				</div>
				<div class="help-block bg-info">Для отправки необходимо заполнить все поля.</div>
				<div class="btn-group" role="group">
					<button type="submit" class="btn btn-success btn-lg" id="save">Сохранить</button>
					<button type="reset" class="btn btn-default btn-lg" id="reset">Сбросить</button>
				</div>
			</form>
			<div class="col-lg-6">
				<div class="well">
					<h2>Редактору <small>напоминание</small></h2>
					<p>При добавлении новостей заполняйте поля помеченный звездочкой.</p>
					<p>Выбирите новость, которую вы хотите отредактировать. После выбора откроется форма.</p>
					<p>Если вы хотите, чтобы новость не показывалась после ее даты, то поле &laquo;Дата удаления&raquo; можно не заполнять. Чтобы новость не отображалось, просто снемите галочку с поля &laquo;Новость активна?&raquo;.</p>
					<p class="text-warning">Просим, не распростронять пароль лицам, не участвующим в добавлении новостей.</p>
				</div>
			</div>
		</div>
		<hr>
		<nav>
			<ul class="pager">
				<li><a href="addNews.php">Добавление новостей</a></li>
				<li class="active"><a>Редактирование новостей</a></li>
				<li><a href="addPhoto.php">Добавить фотографию</a></li>
				<li><a href="deletePhoto.php">Удалить фотографию</a></li>
				<li><a href="/dashboard/new-fix/">Монитор</a></li>
			</ul>
		</nav>
	</div>
	<script>
	$(document).ready(function () {
		$("#date, #end").datepicker({
			format: "yyyy-mm-dd",
			todayHighlight: true
		});
	});
	</script>
</body>
</html>