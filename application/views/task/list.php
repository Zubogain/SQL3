<div style="display: inline-block; margin-left: 20px;">
    <form method="POST" action="?/task">
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
        <tbody>
<?php
$form = '';
$arrayAllUsers = [];
foreach ($getAllUsers as $value)
{
    $arrayAllUsers[] = $value;
}


$todoCopy = [];
// Список дел именно которые создал сам пользователь
foreach ($todo as $row) 
{
    $todoCopy[] = $row;
    if ($row['u_id'] == $_SESSION['user_id'])
    {
        $form .= '<tr>';
        $form .= '<td>'. $row['description'] .'</td>';
        $form .= '<td>'. $row['date_added'] .'</td>';


        // проверка выполнена ли задача
        if ($row['is_done'] == 1) 
        {
            $form .= '<td style="color: green;">Выполнено</td>';
        }
        else
        {
            $form .= '<td style="color: red;">Не выполнено</td>';
        }


        // Вывод всех кто ответственен за задание
        foreach ($arrayAllUsers as $key) 
        {
            if ($row['assigned_user_id'] == $key['id']) 
            {
                if ($row['assigned_user_id'] == $_SESSION['user_id']) 
                {
                    $form .= '<td>Вы</td>';
                }
                else
                {
                    $form .= '<td>'. $key['login'] .'</td>';
                }
            }
        }
            
        $id = $row['id'];
        $form .= "<td><a href=\"?/task/action={$id}/edit\">Изменить</a>";


        // Пороверка если за задание отвечаю я то вывести ссылку на выполнение
        if ($row['assigned_user_id'] == $_SESSION['user_id']) 
        {
            $form .= " <a href='?/task/action={$id}/done'>Выполнить</a>";
        }
        $form .= " <a href=\"?/task/action={$id}/delete\">Удалить</a></td>";
        $form .= "<td><form method='POST' action=\"?/task\"><select name='assigned_user_id'>";


        // Цикл перебора всех пользователей в системе
        foreach ($arrayAllUsers as $users)
        {
            $form .= "<option value=\"{$users['id']}/{$row['id']}\">{$users['login']}</option>";
        }
        $form .= '</select> <input type="submit" name="assign" value="Переложить ответственность"></form></td>';
        
        $form .= '</tr>';
    }
}


$form .= '
		</tbody>
	</table>
</div>';
echo $form;
$form = '';
?>
<div>
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
        </thead>
<?php
// Список дел пользователей которые требуют от тебя
foreach ($todoCopy as $row)
{
    if ($row['assigned_user_id'] == $_SESSION['user_id'] and $row['user_id'] != $_SESSION['user_id']) 
    {
        $form .= '<tr>';
        $form .= '<td>'. $row['description'] .'</td>';
        $form .= '<td>'. $row['date_added'] .'</td>';


        // проверка выполнена ли задача
        if ($row['is_done'] == 1) 
        {
            $form .= '<td style="color: green;">Выполнено</td>';
        }
        else
        {
            $form .= '<td style="color: red;">Не выполнено</td>';
        }


        $form .= '<td>'. $row['login'] .'</td>';
        $id = $row['id'];
        $form .= "<td><a href='?/task/action={$id}/edit'>Изменить</a> <a href='?/task/action={$id}/done'>Выполнить</a> <a href='?/task/action={$id}/delete'>Удалить</a></td>";
    }
}


$form .= '</table>';
echo $form;