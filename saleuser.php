<?
/*
1.Подключение к БД и начало HTML-шаблона
2.Верхний хедер с контактами и информацией о пользователе
3.Основной хедер с лого, поиском и корзиной
4.Навигационное меню для покупателя
5.Получение заказов пользователя из БД
6.Обработка запроса на возврат товара (step=1)
7.Форма управления заказами с сортировкой
8.Таблица с заказами пользователя
9.Кнопки управления: Детали, Счет в Excel, Доставка
10.Футер
11.Подключение JS-скриптов
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
								<form method="post">

									<input  name="merch" class="input" placeholder="Поиск">
									<button onclick="this.form.action='sale1.php?filter=1&sort=<? echo $sort;?>'; this.form.submit();"class="search-btn">Искать</button>
								</form>
							</div>
						</div>
						<!-- /SEARCH BAR -->

					<?
 	if ($Mode=="Покупатель")
						{
$r=mysqli_query($dbcnx,"select detail.iddetail FROM detail, sale  WHERE sale.idsale=detail.idsale and iduser=$iduser and kind='Корзина'");
 $count=mysqli_num_rows($r);
 ?>
						<!-- ACCOUNT -->
						<div class="col-md-3 clearfix">
							<div class="header-ctn">
								<!-- Wishlist -->
                                								<div>
									<a href="sale2.php">
										<i class="fa fa-shopping-cart"></i>
										<span>Корзина</span>
										<div class="qty"><? echo $count;?></div>
									</a>
								</div>
                                
			
								<!-- /Wishlist -->

							
							</div>
						</div>
						<!-- /ACCOUNT -->
<?
						}
?>  
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
						<li ><a href="conf.php?idcategory=1">Конфигуратор</a></li>
						<li class="active"><a href="saleuser.php">Заказы</a></li>

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
                
<h3 class="breadcrumb-header">Заказы покупателя</h3>
<form name="form2"  method="post"  >
 
   <?
   $step=$_REQUEST["step"];

   if ($step==1)
	{
	$Arr=$_POST['Arrsale'];
	$idsale=$Arr[0];

	$s= "UPDATE sale set back='Да' where idsale=$idsale";
	mysqli_query($dbcnx,$s);
?>	
          <script language="javascript">
		    alert("Ваша заявка на возврат принята! Наш менеджер в ближайшее время свяжется с Вами!");
		 </script>	
         <?
	}



	//echo "UPDATE sale SET kind='Продано' where idsale=$idsale";
		$filter=$_GET["filter"];//считывание параметра фильтра
		$sort=$_GET["sort"];//считывание параметра фильтра		


		 $s="SELECT idsale, datesale, datedelivery, kind FROM sale where sale.iduser=$iduser";


if ($sort==1)/*есть ли сортировка данных*/
{
$fieldsort = $_POST['sortname'];//первое поле
$s=$s." order by $fieldsort";
}

	 ?>
         
     		<div align="right">	
Сортировка:
				<select name="sortname"  style="height:22; width:auto" onChange="this.form.action='saleuser.php?sort=1&filter=<? echo $filter;?>'; this.form.submit();" >
					<option value="datesale"  <? if ($fieldsort=="datesale") {?> selected="selected" <? }?>>Дата продажи </option>	
					<option value="kind"  <? if ($fieldsort=="kind") {?> selected="selected" <? }?>>Категория </option>	

                                       
				</select>            	

				<br>
          
              </div>   
              
              
  <table WIDTH=100% border=1 cellspacing=0 cellpadding=3>
									<tr>
		<td  ><font color=white>&nbsp;</font></td>                                    
		<td ><h5>Дата продажи</h5></td>
		<td ><h5>Категория</h5></td>   
		<td ><h5>Доставка</h5></td>          
		  </tr>
        
        
      <?
		 
		 $r=mysqli_query($dbcnx,$s);

			for ($i=0;$i<mysqli_num_rows($r);$i++)//вывод данных в цикле по количеству записей
			  {
				$f=mysqli_fetch_array($r);//считывание текующей записи				
				echo "<tr>";

			
	if ($i==0)
    echo "<td><input type=radio checked=checked name=Arrsale[] value=".$f["idsale"]."> </td>";
	else
    echo "<td><input type=radio name=Arrsale[] value=".$f["idsale"]."> </td>";				
				echo "
				<td> $f[datesale]</td>
				<td> $f[kind]</td>	";	
	if ($f['datedelivery']!='0000-00-00 00:00:00')
		echo "<td> $f[datedelivery]</td>";
	else	
		echo "<td> Нет доставки</td>";						
							
				echo "</tr>";
			  }		 
		?>
      
  </table> 
<br>

<input  type="button"  name="button" <? if (mysqli_num_rows($r)==0) {?>    disabled="disabled"<? }?>  onclick="this.form.action='detailuser.php'; this.form.submit();" value="Детали">
<input  type="button"  name="button" <? if (mysqli_num_rows($r)==0) {?>    disabled="disabled"<? }?>  onclick="this.form.action='chektoexcel.php?sort=<? echo $sort;?>&filter=<? echo $filter;?>'; this.form.submit();" value="Счет в Excel">   
<input  type="button"  name="button" <? if (mysqli_num_rows($r)==0) {?>    disabled="disabled"<? }?>  onclick="this.form.action='adddelivery.php?step=1'; this.form.submit();" value="Доставка">   

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
