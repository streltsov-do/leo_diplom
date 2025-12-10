<?
/*
1.Подключение настроек БД
2.Получение ID заказа для удаления
3.Выполнение SQL-запроса на удаление
4.Редирект обратно на страницу заказов
*/
require "option.php";
?>

<?
$Arr=$_POST['Arrsale'];
$idsale=$Arr[0];
mysqli_query($dbcnx,"DELETE  FROM sale where idsale=$idsale");
?>
 <script language="javascript">
location.href='sale.php';
 </script>
