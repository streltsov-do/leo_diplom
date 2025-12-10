	<?
/*
1. Подключение к БД
2. Получение ID пользователя из массива POST и выполнение SQL-запроса на удаление
3. JavaScript редирект на страницу user.php после удаления
*/
require "option.php";

$Arr=$_POST['Arr'];
$id=$Arr[0]; 
mysqli_query($dbcnx,"DELETE FROM user  WHERE iduser=$id");

?>
 <script language="javascript">
 location.href='user.php';
 </script>
