<?php
class DataBase
{
	/**
	 * Подключение к базе данных mysql
	 * @param $host адрес
	 * @param $dbname название базы
	 * @param $user пользователь
	 * @param $pass пароль
	 */
	public static function connect($host, $dbname, $user, $pass)
	{
		try {
			$db = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $pass);
		} catch (PDOException $e) {
			die('Database error: '.$e->getMessage().'<br/>');
		}
		return $db;
	}
}