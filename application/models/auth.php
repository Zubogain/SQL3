<?php
/**
* Модель Auth
*/
class AuthModel
{
	private $db;

	public function __construct($db)
	{
		$this->db = $db;
	}


	/**
	* count users logins
	*/
	public function countLogins($login)
	{
		$sth = $this->db->prepare('SELECT COUNT(`login`) FROM `user` WHERE `login` = ? ');
		if ($sth->execute(array((string) $login)))
		{
			return (int) $sth->fetchColumn();
		}
		return false;
	}


	/**
	* Авторизация
	*/
	public function signIn($login)
	{
		$sth = $this->db->prepare('SELECT `id`, `login`, `password` FROM `user` WHERE `login` = ? ');
		if ($sth->execute(array((string) $login )))
		{
			$sth->setFetchMode(PDO::FETCH_ASSOC);

			return $sth;
		}
		return false;
	}


	/**
	* Регистрация
	*/
	public function register($login, $password)
	{
		$password = password_hash($password, PASSWORD_DEFAULT);

		$sth = $this->db->prepare('INSERT INTO `user` (`id`, `login`, `password`) VALUES (NULL, ? , ? )');
		if ($sth->execute(array( (string) $login, $password )))
		{
			return true;
		}
		return false;
	}
}