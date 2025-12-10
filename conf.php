<?
/*
1.Подключение настроек БД
2.HTML-шаблон
3.Хедер с информацией о пользователе
4.Навигационное меню
5.Обработка step и idcategory
6.Создание корзины
7.Добавление товара и переход к следующей категории
8.Проверка окончания конфигурации
9.Форма с товарами и фильтрацией
10.Таблица с товарами
11.Кнопки управления
12.Футер
13.Cкрипты
*/
require "option.php";//файл с параметрами подключения к БД
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<title>Магазин компьютерных комплектующих</title>

 		<!-- Google font -->
 		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

 		<!-- Bootstrap -->
 		<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>

 		<!-- Slick -->
 		<link type="text/css" rel="stylesheet" href="css/slick.css"/>
 		<link type="text/css" rel="stylesheet" href="css/slick-theme.css"/>

 		<!-- nouislider -->
 		<link type="text/css" rel="stylesheet" href="css/nouislider.min.css"/>

 		<!-- Font Awesome Icon -->
 		<link rel="stylesheet" href="css/font-awesome.min.css">

 		<!-- Custom stlylesheet -->
 		<link type="text/css" rel="stylesheet" href="css/style.css"/>

 		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
 		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
 		<!--[if lt IE 9]>
 		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
 		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
 		<![endif]-->

    </head>
	<body>
		<!-- HEADER -->
		<header>
			<!-- TOP HEADER -->
			<div id="top-header">
				<div class="container">
					<ul class="header-links pull-left">
						<li><a href="#"><i class="fa fa-phone"></i> 778833</a></li>
						<li><a href="#"><i class="fa fa-envelope-o"></i> comp@ya.ru</a></li>
						<li><a href="#"><i class="fa fa-map-marker"></i> г.Москва ул. Кропоткина 44</a></li>
					</ul>
					<ul class="header-links pull-right">
 <?
 	if ($Mode!="")
						{
 ?>
						<li><a href="#"><i class="fa fa-user-o"></i> <? echo $fio." ($Mode)";?></a></li>

<?
						}
						?>                        
					</ul>
				</div>
			</div>
			<!-- /TOP HEADER -->

			<!-- MAIN HEADER -->
			<div id="header">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<!-- LOGO -->
						<div class="col-md-3">
							<div class="header-logo">
								<a href="#" class="logo">
									<img src="./img/logo.png" alt="">
								</a>
							</div>
						</div>
						<!-- /LOGO -->

						<!-- SEARCH BAR -->
						<div class="col-md-6">
							<div class="header-search">
								<form>

									<input class="input" placeholder="Поиск">
									<button class="search-btn">Искать</button>
								</form>
							</div>
						</div>
						<!-- /SEARCH BAR -->

						<!-- ACCOUNT -->
						<div class="col-md-3 clearfix">
							<div class="header-ctn">


<?
 	if ($Mode=="Покупатель")
						{
$r=mysqli_query($dbcnx,"select detail.iddetail FROM detail, sale  WHERE sale.idsale=detail.idsale and iduser=$iduser and kind='Корзина'");
 $count=mysqli_num_rows($r);
 ?>
								<!-- Wishlist -->
                                								<div>
									<a href="sale2.php">
										<i class="fa fa-shopping-cart"></i>
										<span>Корзина</span>
										<div class="qty"><? echo $count;?></div>
									</a>
								</div>
                                
			
								<!-- /Wishlist -->
<?
						}
?> 
														<!-- Menu Toogle -->
								<div class="menu-toggle">
									<a href="#">
										<i class="fa fa-bars"></i>
										<span>Menu</span>
									</a>
								</div>
								<!-- /Menu Toogle -->
							</div>
						</div>
						<!-- /ACCOUNT -->
					</div>
					<!-- row -->
				</div>
				<!-- container -->
			</div>
			<!-- /MAIN HEADER -->
		</header>
		<!-- /HEADER -->

		<!-- NAVIGATION -->
		<nav id="navigation">
			<!-- container -->
			<div class="container">
				<!-- responsive-nav -->
				<div id="responsive-nav">
					<!-- NAV -->
					<ul class="main-nav nav navbar-nav">
						<li><a href="index.php">На главную</a></li>                                              
						<li ><a href="sale1.php">Выбор товаров</a></li>     
						<li ><a href="sale2.php">Корзина</a></li>
						<li class="active"><a href="conf.php?idcategory=1">Конфигуратор</a></li>
						<li><a href="saleuser.php">Заказы</a></li>

						<li ><a href="index.php?step=2">Выход</a></li>                            
					</ul>
					<!-- /NAV -->
				</div>
				<!-- /responsive-nav -->
			</div>
			<!-- /container -->
		</nav>
		<!-- /NAVIGATION -->

		

		<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
          <?
          $step=$_GET["step"];
          $idcategory=$_GET["idcategory"];
          
          
           if ($idcategory==1)//заводим корзину
           {       
           mysqli_query($dbcnx,"DELETE  FROM sale where iduser=$iduser and kind='Корзина'");  
           //забираем текущую дату
 	   date_default_timezone_set("Europe/Moscow");
	   $Now=date("Y")."-".date("m")."-".date("d")." ".date("H").":".date("i").":".date("s");    
	   mysqli_query($dbcnx,"insert into sale (iduser, kind, datesale) values ($iduser, 'Корзина', '$Now')"); 	
 	   }
	
	
	
          if ($step==2)//отмена
          {
          mysqli_query($dbcnx,"DELETE  FROM sale where iduser=$iduser and kind='Корзина'");
          ?>
           <script language="javascript">
		 location.href='sale1.php';
	   </script>
          <?
          exit();
          }
          
          if ($step==1)//следующий шаг
          {           
           $Arr=$_POST['ArrMerch'];
	   $idmerch=$Arr[0];
	   $r=mysqli_query($dbcnx,"select * FROM sale  WHERE iduser=$iduser and kind='Корзина'");
	   $f=mysqli_fetch_array($r);//считывание текующей записи	
	   $idsale=$f["idsale"];
	
	   mysqli_query($dbcnx,"insert into detail (idsale, idmerch, countmerch) values ($idsale, $idmerch, 1)");	

	   
           $idcategory=$idcategory+1;         
          }
                   
          $s="SELECT category from category where idcategory=$idcategory";
          $r=mysqli_query($dbcnx,$s);
          $f=mysqli_fetch_array($r);//считывание текующей записи	

          if (mysqli_num_rows($r)==0)//конец
          {
          ?>
	     <meta charset="utf-8">
  	     <script language="javascript">
		alert("Конфигурация собрана!");
		location.href='sale2.php';
	     </script>
          <?
          exit();
          }          
          ?>      
<h3 class="breadcrumb-header">Выберите комплектующие из категории "<? echo $f["category"]?>"</h3>
<form name="form2"  method="post"  >
 
   <?
		$filter=$_GET["filter"];//считывание параметра фильтра
		$sort=$_GET["sort"];//считывание параметра фильтра		

$s="SELECT idmerch, merch,  price, annotation, file, (SELECT ROUND(AVG(mark), 2) from response where response.idmerch=merch.idmerch) as avgmark  FROM merch where idcategory=$idcategory and UPPER(merch)" ;

if ($filter==1)/*есть ли фильтрация данных*/
{ 
$value1 = $_POST['FilterValue1'];//значение первого поля
$s=$s." LIKE UPPER('%$value1"."%')  ";
}
else
$s=$s."=UPPER(merch) ";

if ($idcategory>1 && isset($idsale) && $idsale > 0)//ограничение по разъёмам
$s=$s." and idmerch IN (SELECT idmerch from merchconnector where idconnector in (SELECT idconnector FROM merchconnector, merch, detail WHERE merchconnector.idmerch= merch.idmerch and merch.idmerch=detail.idmerch and idsale=$idsale))";
if ($sort==1)/*есть ли сортировка данных*/
{
$fieldsort = $_POST['sortname'];//первое поле
$s=$s." order by $fieldsort";
}
else
$s=$s." order by avgmark DESC";



		 $r=mysqli_query($dbcnx,$s);
	 ?>
         
     		<div align="right">	
Сортировка:
				<select name="sortname"  style="height:22; width:auto" onChange="this.form.action='conf.php?sort=1&idcategory=<? echo $idcategory;?>&step=0&filter=<? echo $filter;?>'; this.form.submit();" >
					<option value="avgmark DESC"  <? if ($fieldsort=="avgmark DESC") {?> selected="selected" <? }?>>Рейтинг </option>	
					<option value="price"  <? if ($fieldsort=="price") {?> selected="selected" <? }?>>Цена</option>	
					<option value="merch"  <? if ($fieldsort=="merch") {?> selected="selected" <? }?>>Товар </option>						
                                       
				</select>            	
Название: 
                
				<input   name="FilterValue1"  onFocus="if (this.value=='Все') this.value=''"  value="<? if ($filter==1)/*есть ли фильтрация данных*/ echo "$value1"; else echo("Все"); ?>" onBlur="checkFilterValue1()"  type="text">


				<br>
				<input  type="button"  name="button1"  onclick="this.form.action='conf.php?filter=1&step=0&idcategory=<? echo $idcategory;?>&sort=<? echo $sort;?>'; this.form.submit();"   value="Фильтр">
				<input  type="button"  name="button2"  onclick="this.form.action='conf.php?filter=0&step=0&idcategory=<? echo $idcategory;?>&sort=0'; this.form.submit();"   value="Очистить">
           <br>            
              </div>   
              
 <input  type="button"  name="button4"   onclick="this.form.action='conf.php?step=1&idcategory=<? echo $idcategory;?>'; this.form.submit();" value="Далее">
<input  type="button"  name="button"   onclick="qwest=window.confirm('Вы действительно хотите отменить конфигурацию?');  if (qwest) {this.form.action='conf.php?step=2'; this.form.submit();}" value="Отмена">  


              
  <table WIDTH=100% border=1 cellspacing=0 cellpadding=3>
									<tr>
		<td  ><font color=white>&nbsp;</font></td>                                    
		<td ><h5>Товар</h5></td>
		<td ><h5>Рейтинг</h5></td>        
		<td ><h5>Цена</h5></td>	
		<td ><h5>Описание</h5></td>			
        	<td ><h5>Фото</h5></td>				
			</tr>
        
        
      <?
		 


			for ($i=0;$i<mysqli_num_rows($r);$i++)//вывод данных в цикле по количеству записей
			  {
				$f=mysqli_fetch_array($r);//считывание текующей записи				
				echo "<tr>";

			
	echo "<tr>";

if (($i==0)||($f['idmerch']==$idmerch))
    echo "<td><input type=radio checked=checked name=ArrMerch[] value=".$f['idmerch']."> </td>";
else
    echo "<td><input type=radio name=ArrMerch[] value=".$f['idmerch']."> </td>";				

echo "
    <td> " . $f['merch'] . "</td>
    <td> " . $f['avgmark'] . "</td>				
    <td> " . $f['price'] . "</td>
    <td> " . $f['annotation'] . "</td>	
    <td> <a href='upload/" . $f['file'] . "'> Фото</a> &nbsp;</td>					
";
echo "</tr>";
			  }		 
		?>
      
  </table> 


</form>	


				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

	
    

        
        	

		<!-- FOOTER -->
		<footer id="footer">
		

			<!-- bottom footer -->
			<div id="bottom-footer" class="section">
				<div class="container">
					<!-- row -->
					<div class="row">
						<div class="col-md-12 text-center">
							<ul class="footer-payments">
								<li><a href="#"><i class="fa fa-cc-visa"></i></a></li>
								<li><a href="#"><i class="fa fa-credit-card"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-paypal"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-mastercard"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-discover"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-amex"></i></a></li>
							</ul>
							<span class="copyright">
								<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
								Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
							<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
							</span>


						</div>
					</div>
						<!-- /row -->
				</div>
				<!-- /container -->
			</div>
			<!-- /bottom footer -->
		</footer>
		<!-- /FOOTER -->

		<!-- jQuery Plugins -->
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/slick.min.js"></script>
		<script src="js/nouislider.min.js"></script>
		<script src="js/jquery.zoom.min.js"></script>
		<script src="js/main.js"></script>

	</body>
</html>
