<?php
// Модули якобы ядра :-)
require_once __DIR__ . '/core/db.php';
require_once __DIR__ . '/core/router.php';


// Здесь этого не должно быть пути к контроллерам и моделям должен инклюдить Router
// Но моя лень мне не дала этого сделать :-)
// Понять простить :-)
require_once __DIR__ . '/controllers/task.php';
require_once __DIR__ . '/models/task.php';


// Конфиг для MySQL
$config = include 'config.php';
$db = DataBase::connect(
	$config['mysql']['host'],
	$config['mysql']['dbname'],
	$config['mysql']['user'],
	$config['mysql']['pass']
);
Router::start($db);