<?php
date_default_timezone_set("Europe/Moscow");
$error = "";
$success = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	include 'config.php';
	$flagError = false;
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
			$allnews = simplexml_load_file("./modules/news/res/news.xml");
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
				$news = $allnews->addChild('news');
				$news->addAttribute('active', 'True');
				$news->addAttribute('id', (string) time());
				$news->addAttribute('date', $date);
				$news->addAttribute('end', $end);
				$news->addChild('title', $title);
				$news->addChild('text', $text);
				$allnews->asXML("./modules/news/res/news.xml");
				unset($_SESSION);
				$success = "Сохранено.";
			}
		} else {
			$error = "Wrong password!";
		}
	}
}

if (!$_SESSION['date']) {
	// $now = new DateTime("now");
	// $_SESSION['date'] = $now->format("Y-d-m");
}
if ($error) {
	$error = '<p class="alert alert-warning">'.$error.'</p>';
}
if ($success) {
	$success = '<p class="alert alert-success">'.$success.'</p>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Add News</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/datepicker.css">
	<link rel="stylesheet" href="css/editor.css">
	<link rel="icon" type="image/x-icon" href="img/favicon.png">
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/add.js"></script>
</head>
<body>
	<div class="container">
		<h1>Добавления мероприятий <small>информационный монитор ФРТК</small></h1>
		<hr>
		<div class="row">
			<form action="" method="post" class="col-lg-6" id="add-news">
				<?=$error?>
				<?=$success?>
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
							<label for="dateend">Дата удаления</label>
							<input name="dateend" type="text" class="form-control" id="dateend" placeholder="yyyy-mm-dd" value="<?=$_SESSION['dateend']?>" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
						</div>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
							<label for="passwd">Пароль редактора*</label>
							<input name="passwd" type="password" class="form-control" id="passwd" required>
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
					<p>Если вы хотите, чтобы новость не показывалась после ее даты, то поле &laquo;Дата удаления&raquo; можно не заполнять.</p>
					<p class="text-warning">Просим, не распростронять пароль лицам, не участвующим в добавлении новостей.</p>
				</div>
			</div>
		</div>
		<hr>
		<nav>
			<ul class="pager">
				<li class="active"><a>Добавление новостей</a></li>
				<li><a href="editNews.php">Редактирование новостей</a></li>
				<li><a href="addPhoto.php">Добавить фотографию</a></li>
				<li><a href="deletePhoto.php">Удалить фотографию</a></li>
				<li><a href="/dashboard/new-fix/">Монитор</a></li>
			</ul>
		</nav>
	</div>
	<script>
	$(document).ready(function () {
		$("#date, #dateend").datepicker({
			format: "yyyy-mm-dd",
			todayHighlight: true
		});
	});
	</script>
</body>
</html>