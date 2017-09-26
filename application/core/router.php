<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<pre>";
var_dump($_SESSION);
echo "</pre><hr>";
/**
* class Router по крайне мере должен быть :)
* Моя лень мне не дала адекватно написать class Router, понять простить :-)
*/
class Router
{
	static function logout() // Лучше чем в Router это запихнуть не придумал
	{
		session_destroy();
		header('location: index.php');
	}


	static function start($db)
	{
		if (!empty($_SESSION['user_login'])) // проверка на авторизацию
		{
			$controller = new TaskController($db);
			$controller->getTodoList();


			/**
			* Событие выйти
			*/
			echo '<a href=\'?action=logout\'>Выйти</a>';
		}
		else
		{
			if (!empty($_GET['action']) and (string) $_GET['action'] == 'auth')
			{
				require_once __DIR__ . '/../controllers/auth.php';
				require_once __DIR__ . '/../models/auth.php';

				$controllerAuth = new AuthController($db);
				$controllerAuth->getAuth();
			}
			else
			{
				echo '<a href=\'?action=auth\'>Войдите на сайт</a>';
			}
		}


		/**
		* Проверка события выйти
		*/
		if (!empty($_GET['action']) and (string) $_GET['action'] == 'logout')
		{
			self::logout();
		}
	}
}