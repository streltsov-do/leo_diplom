<?
/*
1.Установка заголовков для экспорта в Excel
2.Подключение настроек БД
3.Получение параметров фильтрации и сортировки
4.Формирование SQL-запроса с фильтрацией
5.Выполнение SQL-запроса
6.Создание таблицы Excel с данными о продажах
*/
	header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . 'Продажи и заказы магазина.xls');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0'); 
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

require "option.php";//файл с параметрами подключения к БД
        ?>
					   


<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>Магазин компьютерных комплектующих</title>
</head>
<body>

  <?
		$filter=$_GET["filter"];//считывание параметра фильтра
		$sort=$_GET["sort"];//считывание параметра фильтра		

if ($filter==1)/*есть ли фильтрация данных*/
{
		 
$value1 = $_POST['FilterValue1'];//значение первого поля
$s="SELECT datesale, datedelivery, kind, fio FROM sale  INNER JOIN user ON user.iduser=sale.iduser where UPPER(fio)" ;
if ($value1!="Все")
$s=$s." LIKE UPPER('%$value1"."%') ";
Else
$s=$s."=UPPER(fio) ";
}
else
		 $s="SELECT datesale, datedelivery, kind, fio FROM sale INNER JOIN user ON user.iduser=sale.iduser";


if ($sort==1)/*есть ли сортировка данных*/
{
$fieldsort = $_POST['sortname'];//первое поле
$s=$s." order by $fieldsort";
}

	 ?>
     
  <table WIDTH=100% border=1 cellspacing=0 cellpadding=3>
									<tr>
                             
		<td ><h5>Дата продажи</h5></td>
		<td ><h5>Категория</h5></td>        
		<td ><h5>Покупатель</h5></td>	
        <td ><h5>Доставка</h5></td>     			
			</tr>     

<?


$r=mysqli_query($dbcnx,$s);

			for ($i=0;$i<mysqli_num_rows($r);$i++)//вывод данных в цикле по количеству записей
			  {
				$f=mysqli_fetch_array($r);//считывание текующей записи				
				echo "<tr>";	
				echo "
				<td> $f[datesale]</td>
				<td> $f[kind]</td>				
				<td> $f[fio]</td>	
				";				
				if ($f['datedelivery']!='0000-00-00 00:00:00')
		echo "<td> $f[datedelivery]</td>";
	else	
		echo "<td> Нет доставки</td>";						
				echo "</tr>";
			  }		 
		?>
      
  </table> 
		
		
						

</body>
</html>
