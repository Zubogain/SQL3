<?php
/**
* controller Task
*/
class TaskController
{
	private $model = null;
	private $dir = __DIR__ . '/../views/task/';

	function __construct($db)
	{
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
			$view .= $this->render( $this->dir . 'edit.php', ['descriptionId' => $descriptionId, 'descriptionEdit' => $descriptionEdit]);
		}
		else
		{
			$view .= $this->render( $this->dir . 'add.php');
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

					$view .= $this->render( $this->dir . 'list.php', ['todo' => $listSort, 'getAllUsers' => $todoUsers]);
					break;

				case 'description':

					$listSort = $this->model->sortTaskDescription();

					$view .= $this->render( $this->dir . 'list.php', ['todo' => $listSort, 'getAllUsers' => $todoUsers]);
					break;
				
				default:

					$listSort = $this->model->sortTaskDateAdded();

					$view .= $this->render( $this->dir . 'list.php', ['todo' => $listSort, 'getAllUsers' => $todoUsers]);
					break;
			}
		}
		else
		{
			$todo = $this->model->findAll();

			$view .= $this->render( $this->dir . 'list.php', ['todo' => $todo, 'getAllUsers' => $todoUsers]);
		}


		echo $view;
	}
}