<?php
date_default_timezone_set("Europe/Moscow");
$error = "";
$success = "";
$srcPhoto = "./modules/photo/res/photo.xml";
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
		if ($key != "passwd" && $value == "del") {
			$delPhoto[] = $key;
		}
	}

	if (!$flagError) {
		if ($_POST['passwd'] == $passwd) {
			$uploaddir = './modules/photo/res/upload/';
			if (count($delPhoto) > 0) {
				$photos = simplexml_load_file("./modules/photo/res/photo.xml");
				foreach ($delPhoto as $key => $value) {
					$curr = $photos->xpath("//photo[@id='".$value."']")[0];
					$curr['active'] = "0";
					unlink($uploaddir . (string) $curr->source);
					// $outPOST = "<pre>".print_r($curr, true)."</pre>";
				}
				$photos->asXML("./modules/photo/res/photo.xml");
				$success = "Фотографии удалены.";
			} else {
				$error = "Wrong!";
			}
		} else {
			$error = "Wrong password!";
		}
	}
}
if ($error) {
	$error = '<p class="alert alert-warning">'.$error.'</p>';
}
if ($success) {
	$success = '<p class="alert alert-success">'.$success.'</p>';
}
$divphoto = "";
$photo = simplexml_load_file($srcPhoto);
$res = array();
$count = 0;
foreach ($photo->photo as $key => $value) {
	$active = (bool) ((string) $value['active']);

	if ($active) {
		$count += 1;
		$curr = array();
		$curr['id'] = (string) $value['id'];
		$curr['source'] = (string) $value->source;
		$curr['type'] = (string) $value['type'];
		$curr['title'] = (string) $value->title;
		$res[] = $curr;
		$divphoto .= "<div class='col-lg-3'>" . 
			"<img src='/dashboard/new/modules/photo/res/upload/" . 
			$curr['source'] . "' alt='" . $curr['title'] . "' " . 
			"class='img-thumbnail' id='".$curr['id']."'>" . 
			"<div class='text-center'>" . $curr['title'] . "</div>" .
			"</div>";

		if ($count % 4 == 0) {
			$divphoto .= "</div><hr><div class='row'>";
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Delete Photo</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/editor.css">
	<link rel="icon" type="image/x-icon" href="img/favicon.png">
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/delete.js"></script>
</head>
<body>
	<div class="container">
		<h1>Удаление фотографий <small>информационный монитор ФРТК</small></h1>
		<hr>
		<?=$error?>
		<?=$success?>
		<div id="edit-photo">
			<div class="row">
				<?=$divphoto?>
			</div>
		</div>
		<hr>
		<div class="row">
			<form class="col-lg-4" method="post" id="form-photo">
				<div class="form-group">
					<label for="passwd">Пароль редактора*</label>
					<input type="password" id="password" name="passwd" class="form-control" required>
				</div>
				<div class="help-block bg-info">Для отправки необходимо заполнить все поля.</div>
				<div class="btn-group" role="group">
					<button type="submit" class="btn btn-danger btn-lg" id="save">Удалить</button>
					<button type="reset" class="btn btn-default btn-lg" id="reset">Сбросить</button>
				</div>
			</form>
			<div class="col-lg-8">
				<div class="well">
					<h2>Редактору <small>напоминание</small></h2>
					<p>При добавлении новостей заполняйте поля помеченный звездочкой.</p>
					<p>Выделяйте необходимые к удалению фотографию и сохраняйте свои действия.</p>
					<p class="text-warning">Просим, не распростронять пароль лицам, не участвующим в добавлении новостей.</p>
				</div>
			</div>
		</div>
		<hr>
		<nav>
			<ul class="pager">
				<li><a href="addNews.php">Добавление новостей</a></li>
				<li><a href="editNews.php">Редактирование новостей</a></li>
				<li><a href="addPhoto.php">Добавить фотографию</a></li>
				<li class="active"><a>Удалить фотографию</a></li>
				<li><a href="/dashboard/new-fix/">Монитор</a></li>
			</ul>
		</nav>
	</div>
</body>
</html>