<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
/*
 * Модули якобы ядра :-)
 */
require_once __DIR__ . '/core/db.php';
require_once __DIR__ . '/core/router.php';
/*
 * Конфиг для MySQL
 */
$config = include 'config.php';
$db = DataBase::connect(
	$config['mysql']['host'],
	$config['mysql']['dbname'],
	$config['mysql']['user'],
	$config['mysql']['pass']
);
/*
 * Возможные маршруты
 */
$router = new Router(__DIR__ . '/controllers/', $db);
require_once __DIR__ . '/routes.php';
/*
 * Удаляем "/?", потому что не сделали настройки на серверах
 */
$currentUrl = str_replace('/?', '', $_SERVER['REQUEST_URI']);
$router->run($currentUrl);