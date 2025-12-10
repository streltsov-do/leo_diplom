<?
/*
1.Подключение к БД и получение параметра step
2.HTML-шаблон с подключением CSS
3.Верхний хедер с контактами
4.Основной хедер с лого, поиском и корзиной
5.Навигационное меню
6.Получение ID заказа из POST
7.Запрос данных о заказе из БД
8.Обработка step=1: получение данных заказа и текущей даты
9.Обработка step=2: обновление даты доставки в БД
10.Форма для указания даты доставки
11.Кнопки OK и Отмена
12.Футер
13.Подключение JS-скриптов
*/
require "option.php";//файл с параметрами подключения к БД
$step=$_REQUEST["step"];
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
		
          <?
 	$Arr=$_POST['Arrsale'];
	$idsale=$Arr[0];
		 $s="SELECT merch, category, price, countmerch FROM detail, merch, category where detail.idmerch=merch.idmerch and merch.idcategory=category.idcategory and idsale=$idsale";
	
	 ?>  
	       
<h3 class="breadcrumb-header">Доставка заказа №<? echo $idsale;?></h3>		
	
<?
   if ($step==1)
	{
	$Arr=$_POST['Arrsale'];
	$idsale=$Arr[0];
	$s="SELECT idsale, datesale, kind FROM sale WHERE idsale=$idsale";
	$r=mysqli_query($dbcnx,$s);
	$f=mysqli_fetch_array($r);//считывание текующей записи
 	//забираем текущую дату
	date_default_timezone_set("Europe/Moscow");
	$Now=date("Y")."-".date("m")."-".date("d")." ".date("H").":".date("i");    
 
	}
	

$step=$_REQUEST["step"];
if ($step==2)
{
$datedelivery =  $_POST["datedelivery"];
$idsale =  $_REQUEST["id"];
	

  {//формирование SQL-запроса на добавление данных
	$s= "UPDATE sale set datedelivery='$datedelivery' where idsale=$idsale";

	mysqli_query($dbcnx,$s);
	
	?>
	 <script language="javascript">
	 alert("Ваша заявка на доставку принята! Наш менеджер в ближайшее время свяжется с Вами!");
	 location.href='saleuser.php?filter=0';
	 </script>
	 <?
  }
exit();  
}
	
?>
		
<form name="form2" method="post"  enctype="multipart/form-data" >
				  <table width="422" border="0">
                    <tr>
                      <td width="107"><font color="#000000" >   Дата продажи </font> </td>
                      <td><? echo "$f[datesale]"; ?></td>
                    </tr>  	       
                    <tr>
                      <td width="107"><font color="#000000" >   Категория </font> </td>
                      <td><? echo "$f[kind]"; ?></td>
                    </tr>  	 

                    <tr>
                      <td width="107"><font color="#000000" >   Дата доставки </font> </td>
                      <td><input   name="datedelivery"   type="text"  value="<? echo "$Now"; ?>" size="13" ></td>
                    </tr>  	                                                                
                                       
                  </table>
				<br>
				<input  type="button"  name="button"   onclick="this.form.action='adddelivery.php?step=2&id=<? echo"$idsale";?>'; this.form.submit();"   value="OK" width="500">
				<input  type="button"  name="button"  onClick="javascript:history.back();"  value="Отмена">

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
				
