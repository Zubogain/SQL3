<?php
/**
* controller Task
*/
class TaskController
{
	private $model = null;

	function __construct($db)
	{
		include __DIR__ . '/../models/task.php';
		$this->model = new TaskModel($db);
	}

	/**
	 * Отображаем шаблон
	 * @param $template
	 * @param $params
	 */
	private function render($template, $params = [])
	{
		$fileTemplate = __DIR__ . '/../views/'.$template;
		if (is_file($fileTemplate)) {
			ob_start();
			if (count($params) > 0) {
				extract($params);
			}
			include $fileTemplate;
			return ob_get_clean();
		}
	}


	/**
	 * Показывем список дел и не только :-)
	 */
	public function index()
	{
		if (!isset($_SESSION['user_login'])) {
			header('Location: /');
			die;
		}
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
		if (isset($_GET['/task/action']) and $pie = explode('/', $_GET['/task/action'])) {
			if ((string) $pie[1] == 'done') // запрос на выполнение задания
			{
				$this->model->doneTask($pie[0]);
			}


			if ((string) $pie[1] == 'delete') // запрос на удаление задания
			{
				$this->model->deleteTask($pie[0]);
			}
		}


		/**
		 * Генерация формы на добавление или изменение в зависимости от события
		 */
		if (isset($_GET['/task/action']) and $pie = explode('/', $_GET['/task/action']) and (string) $pie[1] == 'edit')
		{
			$descriptionId = '';
			$descriptionEdit = '';
			foreach ($this->model->selectTask($pie[0]) as $value)
			{
				$descriptionId = $value['id'];
				$descriptionEdit = $value['description'];
			}
			$view .= $this->render('task/edit.php', ['descriptionId' => $descriptionId, 'descriptionEdit' => $descriptionEdit]);
		}
		else
		{
			$view .= $this->render('task/add.php');
		}


		/**
		 * Переложение ответственности
		 */
		if (!empty($_POST['assign']) and !empty($_POST['assigned_user_id']))
		{
			$pie = explode('/', $_POST['assigned_user_id']);
			if (count($pie) == 2)
			{
				$this->model->assignUser($pie[0], $pie[1]);
			}
		}


		/**
		 * Показываем весь список задач :)
		 */
		$todoUsers = $this->model->selectAllUsers();
		if (!empty($_POST['sort']) and !empty($_POST['sort_by']))
		{
			switch ($_POST['sort_by'])
			{
				case 'is_done':

					$listSort = $this->model->sortTaskDone();

					$view .= $this->render('task/list.php', ['todo' => $listSort, 'getAllUsers' => $todoUsers]);
					break;

				case 'description':

					$listSort = $this->model->sortTaskDescription();

					$view .= $this->render('task/list.php', ['todo' => $listSort, 'getAllUsers' => $todoUsers]);
					break;
				
				default:

					$listSort = $this->model->sortTaskDateAdded();

					$view .= $this->render('task/list.php', ['todo' => $listSort, 'getAllUsers' => $todoUsers]);
					break;
			}
		}
		else
		{
			$todo = $this->model->findAll();

			$view .= $this->render('task/list.php', ['todo' => $todo, 'getAllUsers' => $todoUsers]);
		}
		echo $view;
	}
}