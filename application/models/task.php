<?php
/**
* Модель Task
*/
class TaskModel
{
	private $db;

	public function __construct($db)
	{
		$this->db = $db;
	}


	public function findAll() // Мда... если задач будет много то кердык скорости ответа от базы
	{
		$sth = $this->db->prepare('SELECT task.id, user.id AS u_id, user.login, task.id AS t_id, task.user_id, task.assigned_user_id, task.description, task.is_done, task.date_added FROM user INNER JOIN task ON user.id = task.user_id');
		if ($sth->execute())
		{
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			return $sth;
		}
		return false;
	}


	public function selectAllUsers()
	{
		$sth = $this->db->prepare('SELECT `id`, `login` FROM user');
		if ($sth->execute())
		{
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			return $sth;
		}
		return false;
	}


	public function addTask($userId, $description)
	{
		$sth = $this->db->prepare('INSERT INTO `task` (`user_id`, `assigned_user_id` , `description`, `is_done`, `date_added`) VALUES (? , ? , ? , 0 , now() )');
		if ($sth->execute(array((int) $userId, (int) $userId, (string) $description)))
		{
			return true;
		}
		return false;
	}


	public function deleteTask($id)
	{
		$sth = $this->db->prepare('DELETE FROM `task` WHERE `id` = ? ');
		if ($sth->execute(array((int) $id)))
		{
			return true;
		}
		return false;
	}


	public function doneTask($id)
	{
		$sth = $this->db->prepare('UPDATE `task` SET `is_done` = 1 WHERE `id` = ? ');
		if ($sth->execute(array((int) $id)))
		{
			return true;
		}
		return false;
	}


	public function selectTask($id)
	{
		$sth = $this->db->prepare('SELECT `id`, `description` FROM `task` WHERE `id` = ? ');
		if ($sth->execute(array((int) $id)))
		{
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			return $sth;
		}
		return false;
	}


	public function editTask($description, $id)
	{
		$sth = $this->db->prepare('UPDATE `task` SET `description` = ?, `is_done` = 0 WHERE `id` = ? ');
		if ($sth->execute(array($description, $id)))
		{
			return true;
		}
		return false;
	}


	public function sortTaskDone()
	{
		$sth = $this->db->prepare('SELECT task.id, user.id AS u_id, user.login, task.id AS t_id, task.user_id, task.assigned_user_id, task.description, task.is_done, task.date_added FROM user INNER JOIN task ON user.id = task.user_id ORDER BY `task`.`is_done` ASC');
		if ($sth->execute())
		{
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			return $sth;
		}
		return false;
	}


	public function sortTaskDescription()
	{
		$sth = $this->db->prepare('SELECT task.id, user.id AS u_id, user.login, task.id AS t_id, task.user_id, task.assigned_user_id, task.description, task.is_done, task.date_added FROM user INNER JOIN task ON user.id = task.user_id ORDER BY `task`.`description` ASC');
		if ($sth->execute())
		{
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			return $sth;
		}
		return false;
	}


	public function sortTaskDateAdded()
	{
		$sth = $this->db->prepare('SELECT task.id, user.id AS u_id, user.login, task.id AS t_id, task.user_id, task.assigned_user_id, task.description, task.is_done, task.date_added FROM user INNER JOIN task ON user.id = task.user_id ORDER BY `task`.`date_added` ASC');
		if ($sth->execute())
		{
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			return $sth;
		}
		return false;
	}


	public function assignUser($assignUserId, $userId)
	{
		$sth = $this->db->prepare('UPDATE `task` SET `assigned_user_id` = ? , `is_done` = 0 WHERE `id` = ? ');
		if ($sth->execute(array((int) $assignUserId, (int) $userId)))
		{
			return true;
		}
		return false;
	}
}