	<?
/*
1.Подключение к БД
2.Получение ID товара из POST
3.Выполнение SQL-запроса на удаление товара
4.JavaScript редирект на страницу merch.php
*/
require "option.php";

$Arr=$_POST['ArrMerch']; 
$id=$Arr[0];
mysqli_query($dbcnx,"DELETE FROM merch  WHERE idmerch=$id");

?>
 <script language="javascript">
 location.href='merch.php';
 </script>
