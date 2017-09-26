<?php
$form = '</table>
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


        $form .= "<td>$row[login]</td>";
        $id = $row['id'];
        $form .= "<td><a href='?id=$id&action=edit'>Изменить</a> <a href='?id=$id&action=done'>Выполнить</a> <a href='?id=$id&action=delete'>Удалить</a></td>";
    }
}


$form .= '</table>';
echo $form;