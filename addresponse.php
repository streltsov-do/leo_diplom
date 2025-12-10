<?
/*
1.Подключение настроек БД
2.Получение данных отзыва
3.Установка текущей даты
4.Добавление отзыва в базу данных
5.Редирект с сообщением об успехе
*/
require "option.php";//файл с параметрами подключения к БД
$idmerch=$_REQUEST["idmerch"];
$response=$_REQUEST["response"];
$mark=$_REQUEST["mark"];

//забираем текущую дату
date_default_timezone_set("Europe/Moscow");
$Now=date("Y")."-".date("m")."-".date("d")." ".date("H").":".date("i").":".date("s");    

mysqli_query($dbcnx,"insert into response (iduser, idmerch, dateresponse, response, mark) values ($iduser, $idmerch, '$Now', '$response', '$mark')");
//phpinfo(32);
?>
	 <meta charset="utf-8">
	 <script language="javascript">
	 alert ("Ваш отзыв принят! Спасибо!");
	 location.href='annotation.php?idmerch=<? echo $idmerch;?>';
	 </script>

