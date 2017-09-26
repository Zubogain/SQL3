<?php
/**
* controller auth
*/
class AuthController
{
	private $model = null;

	function __construct($db)
	{
		$this->model = new AuthModel($db);
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
	 * Регистрация или авторизация
	 */
	public function getAuth()
	{
		$view = ''; // Здесь весь сгенерированный html


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
							header('location: index.php');
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


		/**
		 * Регистрация
		 */
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
							header('location: index.php');
						}
					}
				}
				else
				{
					$view .= "<p>Такой пользователь уже существует в базе данных.</p>";
				}
			}
		}

		
		$view .= $this->render( __DIR__ . '/../views/auth/auth.php');
		echo $view;
	}
}