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
			$uploaddir = './modules/photo/res/upload/';
			$uploadfile = $uploaddir . basename($_FILES['photo']['tmp_name']);

			if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile)) {
				$photos = simplexml_load_file("./modules/photo/res/photo.xml");
				$title = htmlentities($_POST['title']);
				$photo = $photos->addChild('photo');
				$photo->addAttribute('active', 'True');
				$photo->addAttribute('id', (string) time());
				$photo->addAttribute('type', "photo");
				$photo->addChild('title', $title);
				$photo->addChild('source', $_FILES['photo']['tmp_name']);
				$photos->asXML("./modules/photo/res/photo.xml");
				unset($_SESSION);
				$success = "Файл корректен и был успешно загружен.";
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Add Photo</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/editor.css">
	<link rel="icon" type="image/x-icon" href="img/favicon.png">
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/add.js"></script>
</head>
<body>
	<div class="container">
		<h1>Добавления фотографии <small>информационный монитор ФРТК</small></h1>
		<hr>
		<div class="row">
			<form action="" method="post" class="col-lg-6" id="add-news" enctype="multipart/form-data">
				<?=$error?>
				<?=$success?>
				<div class="form-group">
					<label for="title">Заголовок*</label>
					<input name="title" type="text" class="form-control" id="title" value="<?=$_SESSION['title']?>" required>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label for="photo">Фото*</label>
							<input name="photo" type="file" class="form-control" id="photo" required>
						</div>
					</div>
					<div class="col-lg-6">
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
					<p>Наилучшее разрешения для добавления фотографий следует выбирать между <code>1280px * 720px</code> и <code>1920px * 1080px</code>.</p>
					<p class="text-warning">Просим, не распростронять пароль лицам, не участвующим в добавлении новостей.</p>
				</div>
			</div>
		</div>
		<hr>
		<nav>
			<ul class="pager">
				<li><a href="addNews.php">Добавление новостей</a></li>
				<li><a href="editNews.php">Редактирование новостей</a></li>
				<li class="active"><a>Добавить фотографию</a></li>
				<li><a href="deletePhoto.php">Удалить фотографию</a></li>
				<li><a href="/dashboard/new-fix/">Монитор</a></li>
			</ul>
		</nav>
	</div>
</body>
</html>