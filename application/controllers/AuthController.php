<?php
/**
* controller auth
*/
class AuthController
{
	private $model = null;

	function __construct($db)
	{
		include __DIR__ . '/../models/auth.php';
		$this->model = new AuthModel($db);
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
	 * Регистрация и Авторизация
	 */
	public function auth()
	{
		$view = '';
		if (isset($_SESSION['user_login'])) {
			header('Location: /?/task');
			die;
		}


		if (!empty($_POST['register']))
		{
			if (empty($_POST['login']) or empty($_POST['password']))
			{
				$view .= 'Ошибка регистрации. Введите все необхдоимые данные.';
			}
			else
			{
				if ($this->model->countLogins($_POST['login']) === 0)
				{
					if ($this->model->register($_POST['login'], $_POST['password']))
					{
						foreach ($this->model->signIn($_POST['login'], $_POST['password']) as $userInfo)
						{
							$_SESSION['user_login'] = $userInfo['login'];
							$_SESSION['user_id'] = $userInfo['id'];
							header('Location: /?/task');
						}
					}
				}
				else
				{
					$view .= "<p>Такой пользователь уже существует в базе данных.</p>";
				}
			}
		}



		/**
		 * Авторизация
		 */
		if (!empty($_POST['sign_in']))
		{
			if (empty($_POST['login']) or empty($_POST['password']))
			{
				$view .= 'Ошибка входа. Введите все необхдоимые данные.';
			}
			else
			{
				if ($this->model->countLogins($_POST['login']) === 1)
				{
					foreach ($this->model->signIn($_POST['login'], $_POST['password']) as $userInfo)
					{
						if (password_verify($_POST['password'], $userInfo['password']))
						{
							$_SESSION['user_login'] = $userInfo['login'];
							$_SESSION['user_id'] = $userInfo['id'];
							header('Location: /?/task');
						}
						else
						{
							$view .= "<p>Такой пользователь не существует, либо неверный пароль.</p>";
							break;
						}
					}
				}
				else
				{
					$view .= "<p>Такой пользователь не существует, либо неверный пароль.</p>";
				}
			}
		}
		$view .= $this->render('auth/auth.php');
		echo $view;
	}
}