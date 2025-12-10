	<?
/*
1.Подключение к БД
2.Получение ID категории из POST
3.Выполнение SQL-запроса на удаление категории
4.JavaScript редирект на страницу category.php
*/
require "option.php";

$Arr=$_POST['Arr'];
$id=$Arr[0]; 
mysqli_query($dbcnx,"DELETE FROM category  WHERE idcategory=$id");

?>
 <script language="javascript">
 location.href='category.php';
 </script>
