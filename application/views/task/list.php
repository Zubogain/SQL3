<?php
$form = '
	<div style="display: inline-block; margin-left: 20px;">
    <form method="POST">
        <label for="sort">Сортировать по:</label>
        <select name="sort_by">
            <option value="date_added">Дате добавления</option>
            <option value="is_done">Статусу</option>
            <option value="description">Описанию</option>
        </select>
        <input type="submit" name="sort" value="Отсортировать">
    </form>
</div>

<div class="table">
	<table border="1">
		<thead>
			<tr>
				<td>Описание задачи</td>
				<td>Дата добавления</td>
				<td>Статус</td>
        <td>Ответственный</td>
				<td></td>
				<td>Закрепить задачу за пользователем</td>
			</tr>
		</thead>
		<tbody>';


// Список дел именно которые создал сам пользователь
foreach ($todo as $row) 
{
    if ($row['u_id'] == $_SESSION['user_id'])
    {
        $form .= '<tr>';
        $form .= '<td>'. $row['description'] .'</td>';
        $form .= '<td>'. $row['date_added'] .'</td>';


        // проверка выполнена ли задача
        if ($row['is_done'] == 1) 
        {
            $form .= '<td style=\'color: green;\'>Выполнено</td>';
        }
        else
        {
            $form .= '<td style=\'color: red;\'>Не выполнено</td>';
        }


        // Спустя пару часов безделья так и не додумался до адекватной реализации 
        // Вывод всех кто ответственен за задание
        foreach ($db->query($sqlGetAllUsers) as $key) 
        {
            if ($row['assigned_user_id'] == $key['id']) 
            {
                if ($row['assigned_user_id'] == $_SESSION['user_id']) 
                {
                    $form .= "<td>Вы</td>";
                }
                else
                {
                    $form .= "<td>$key[login]</td>";
                }
            }
        }
            
        $id = $row['id'];
        $form .= "<td><a href='?id=$id&action=edit'>Изменить</a>";


        // Пороверка если за задание отвечаю я то вывести ссылку на выполнение
        if ($row['assigned_user_id'] == $_SESSION['user_id']) 
        {
            $form .= " <a href='?id=$id&action=done'>Выполнить</a>";
        }
        $form .= " <a href='?id=$id&action=delete'>Удалить</a></td>";
        $form .= "<td><form method='POST'><select name='assigned_user_id'>";


        // Спустя пару часов безделья так и не додумался до адекватной реализации 
        // Цикл перебора всех пользователей в системе
        foreach ($db->query($sqlGetAllUsers) as $users)
        {
            $form .= "<option value='$users[id]/$row[id]'>$users[login]</option>";
        }
        $form .= "</select> <input type='submit' name='assign' value='Переложить ответственность'></form></td>";
        
        $form .= '</tr>';
    }
}


$form .= '
		</tbody>
	</table>
</div>';
$form .= '</table>
 <h3>Также, посмотрите, что от Вас требуют другие люди:</h3>
 <table>
   <thead>
    <tr>
      <td class="table-head">Описание задачи</td>
      <td class="table-head">Дата добавления</td>
      <td class="table-head">Статус</td>
      <td class="table-head">Автор</td>
      <td class="table-head"></td>
    </tr>
  </thead>';


// Список дел пользователей которые требуют от тебя
// Список не выводится :-( , и ошибки тоже нет. Хотя все вроде правильно
foreach ($todo as $row) 
{
    if ($row['assigned_user_id'] == $_SESSION['user_id'] and $row['user_id'] != $_SESSION['user_id']) 
    {
        $form .= '<tr>';
        $form .= '<td>'. $row['description'] .'</td>';
        $form .= '<td>'. $row['date_added'] .'</td>';


        // проверка выполнена ли задача
        if ($row['is_done'] == 1) 
        {
            $form .= '<td style=\'color: green;\'>Выполнено</td>';
        }
        else
        {
            $form .= '<td style=\'color: red;\'>Не выполнено</td>';
        }


        $form .= '<td>'. $row['login'] .'</td>';
        $id = $row['id'];
        $form .= "<td><a href='?id=$id&action=edit'>Изменить</a> <a href='?id=$id&action=done'>Выполнить</a> <a href='?id=$id&action=delete'>Удалить</a></td>";
    }
}


$form .= '</table>';
echo $form;