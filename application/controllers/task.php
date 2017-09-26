<?php
/**
* controller Task
*/
class TaskController
{
	private $model = null;
	private $db;

	function __construct($db)
	{
		$this->db = $db;
		$this->model = new TaskModel($db);
	}

	/**
	 * Отображаем шаблон
	 * @param $template
	 * @param $params
	 */
	private function render($template, $params = [])
	{
		if (is_file($template))
		{
			ob_start();
			if (count($params) > 0) // Если кол-во параметров больше чем 0 то преобразуем параметры в переменные.
			{
				extract($params);
			}
			include $template;
			return ob_get_clean();
		}
	}


	/**
	 * Показывем список дел и не только :-)
	 */
	public function getTodoList()
	{
		$view = '<h3>Дела созданные вами:</h3>'; // Здесь весь сгенерированный html


		/**
		 * Запрос на добавление задачи
		 */
		if (isset($_POST['do_task']) and !empty($_POST['new_task']))
		{
			$this->model->addTask($_SESSION['user_id'], $_POST['new_task']);
		}


		/**
		 * Запрос на изменение текста задачи
		 */
		if (isset($_POST['do_task']) and isset($_POST['edit_task']) and isset($_POST['id']))
		{
			$this->model->editTask($_POST['edit_task'], $_POST['id']);
		}


		
		/**
		 * Проверка событий выполнить и удалить.
		 */
		if (isset($_GET['id']) and isset($_GET['action']))
		{
			if ((string) $_GET['action'] == 'done') // запрос на выполнение задания
			{
				$this->model->doneTask($_GET['id']);
			}


			if ((string) $_GET['action'] == 'delete') // запрос на удаление задания
			{
				$this->model->deleteTask($_GET['id']);
			}
		}


		/**
		 * Генерация формы на добавление или изменение в зависимости от события
		 */
		if (isset($_GET['id']) and isset($_GET['action']) and (string) $_GET['action'] == 'edit')
		{
			$descriptionId = '';
			$descriptionEdit = '';
			foreach ($this->model->selectTask($_GET['id']) as $value)
			{
				$descriptionId = $value['id'];
				$descriptionEdit = $value['description'];
			}
			$view .= $this->render(__DIR__ . '/../views/task/edit.php', ['descriptionId' => $descriptionId, 'descriptionEdit' => $descriptionEdit]);
		}
		else
		{
			$view .= $this->render(__DIR__ . '/../views/task/add.php');
		}


		/**
		 * Показываем весь список задач :)
		 */
		if (!empty($_POST['sort']) and !empty($_POST['sort_by']))
		{
			switch ($_POST['sort_by'])
			{
				case 'is_done':
					$todoSort = $this->model->sortTaskDone();
					$view .= $this->render(__DIR__ . '/../views/task/list.php', ['todo' => $todoSort, 'db' => $this->db, 'sqlGetAllUsers' => "SELECT user.id, user.login FROM user"]);
					break;

				case 'description':
					$todoSort = $this->model->sortTaskDescription();
					$view .= $this->render(__DIR__ . '/../views/task/list.php', ['todo' => $todoSort, 'db' => $this->db, 'sqlGetAllUsers' => "SELECT user.id, user.login FROM user"]);
					break;
				
				default:
					$todoSort = $this->model->sortTaskDateAdded();
					$view .= $this->render(__DIR__ . '/../views/task/list.php', ['todo' => $todoSort, 'db' => $this->db, 'sqlGetAllUsers' => "SELECT user.id, user.login FROM user"]);
					break;
			}
		}
		else
		{
			$todo = $this->model->findAll();
			$allUsers = $this->model->selectAllUsers();
			$view .= $this->render(__DIR__ . '/../views/task/list.php', ['todo' => $todo, 'db' => $this->db, 'sqlGetAllUsers' => "SELECT user.id, user.login FROM user"]);
		}


		echo $view;
	}
}