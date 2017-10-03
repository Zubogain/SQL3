<?php
$router->get('/', 'MainController@index');
$router->get('/logout', 'MainController@logout'); // session_destroy
$router->get('/auth', 'AuthController@auth'); // Регистрация и Авторизация
$router->post('/auth', 'AuthController@auth'); // Регистрация и Авторизация
$router->get('/task', 'TaskController@index');
$router->post('/task', 'TaskController@index');
$router->get('/?/task/action=(\d+)/done', 'TaskController@index');
$router->get('/?/task/action=(\d+)/edit', 'TaskController@index');
$router->get('/?/task/action=(\d+)/delete', 'TaskController@index');