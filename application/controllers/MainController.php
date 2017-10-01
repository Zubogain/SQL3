<?php
/**
* controller Main
*/
class MainController
{
	public function logout()
	{
		session_destroy();
		header('location: /');
	}


	public function index()
	{
		echo "<a href=\"?/auth\">Войдите на сайт</a>";
	}
}