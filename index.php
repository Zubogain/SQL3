<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Список дел :) + рег.</title>
	<link rel="stylesheet" type="text/css" href="css/table.css">
</head>
<body>
	<div class="main">
	<?php
	// Главная страница и есть основной шаблон
	require_once __DIR__ . '/application/bootstrap.php';
	if (isset($_SESSION['user_login'])) {
		echo "<a href=\"?/logout\">Выйти</a>";
	}
	?>
	</div>
</body>
</html>