<?	
	header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . 'Счет.xls');
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
			  
 		 $Arr=$_POST['Arrsale'];
		 $idsale=$Arr[0];
		 $s="select merch.idmerch as idmerch, merch, fio, datesale, category, price, countmerch from detail, sale, merch, category, user where merch.idmerch=detail.idmerch and user.iduser=sale.iduser and detail.idsale=sale.idsale and merch.idcategory=category.idcategory and sale.idsale=$idsale order by iddetail";		
		 $r=mysqli_query($dbcnx,$s);
		 $f=mysqli_fetch_array($r);//считывание текующей записи		
?>

    		<div align="left">	
Дата продажи:    <? echo $f["datesale"];?>    <br>
Покупатель:       <? echo $f["fio"];?>  <br> <br>

              </div>   
              
              
  <table WIDTH=100% border=1 cellspacing=0 cellpadding=3>
									<tr>
                               
		<td ><h5>Товар</h5></td>
		<td ><h5>Категория</h5></td>        
		<td ><h5>Цена</h5></td>			
		<td ><h5>Количество</h5></td>		        	
			</tr>
        
        
      <?


				 
				 
			$r=mysqli_query($dbcnx,$s);	 
			$sumsale=0;			 
			for ($i=0;$i<mysqli_num_rows($r);$i++)//вывод данных в цикле по количеству записей
			  {
				$f=mysqli_fetch_array($r);//считывание текующей записи				
				echo "<tr>";


	
			
			
				echo "
				<td> $f[merch] </td>
				<td> $f[category]</td>				
				<td> $f[price]</td>	
				<td> $f[countmerch]</td>				
						
				";								
				echo "</tr>";
				$sumsale=$sumsale+$f["countmerch"]*$f["price"];
			  }		 

		?>
    		<tr>
            <td colspan="4" align="left">
            <? echo "Общая сумма: $sumsale";?>
            </td>
		</tr>
		
  </table> 
		
		
						

</body>
</html>
