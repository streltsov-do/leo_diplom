<?php
/*
1.Чтение данных пользователя из куки
2.Настройки подключения к базе данных MySQL
3.Подключение к базе данных MySQL
*/
$Mode=$_COOKIE["Mode"];  
$fio=$_COOKIE["fio"];  
$iduser=$_COOKIE["iduser"];  
$idmerch=$_COOKIE["idmerch"];  

$dblocation = "MySQL-8.0";
$dbname = "comp";
$dbuser = "root";
$dbpasswd = "";


$dbcnx = mysqli_connect($dblocation,$dbuser,$dbpasswd,$dbname);

    if(!$dbcnx)
    {
    ?>
    <meta charset="utf-8">
    <?
      echo 'Невозможно соединиться с БД';
      exit;
	}


?>
