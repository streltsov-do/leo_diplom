<?
/*
1.Подключение настроек БД
2.Получение параметров сортировки и фильтрации
3.Обработка добавления товара в корзину (step=2)
4.HTML-шаблон с CSS
5.Хедер с информацией о пользователе
6.Навигационное меню в зависимости от роли
7.Форма с фильтрами товаров
8.Фильтр по названию товара
9.Фильтр по категориям (чекбоксы)
10.Фильтр по цене (диапазон)
11.Секция "Лидеры продаж"
12.Настройки сортировки товаров
13.Формирование SQL-запроса с фильтрами
14.Вывод товаров в виде карточек
15.Отображение рейтинга товаров
16.Кнопка "В корзину" для покупателей
17.Футер и скрипты
*/
require "option.php";//файл с параметрами подключения к БД
$sort=$_GET["sort"];//считывание параметра фильтра	
$filter=$_GET["filter"];//считывание параметра фильтра	

if ($filter==1)/*есть ли фильтрация данных*/
$merch=$_POST["merch"];



$step=$_REQUEST["step"];
if ($step==2)
{
$idmerch=$_GET["idmerch"];
$r=mysqli_query($dbcnx,"select * FROM sale  WHERE iduser=$iduser and kind='Корзина'");


	if (mysqli_num_rows($r)==0)//завести корзину
	{

	//забираем текущую дату
	date_default_timezone_set("Europe/Moscow");
	$Now=date("Y")."-".date("m")."-".date("d")." ".date("H").":".date("i").":".date("s");    
	mysqli_query($dbcnx,"insert into sale (iduser, kind, datesale) values ($iduser, 'Корзина', '$Now')");

	$idsale=mysqli_insert_id($dbcnx);
	mysqli_query($dbcnx,"insert into detail (idsale, idmerch, countmerch) values ($idsale, $idmerch, 1)");		

	}

	if (mysqli_num_rows($r)>0)//корзина есть
	{
	$f=mysqli_fetch_array($r);//считывание текующей записи	
	$idsale=$f["idsale"];
	$r=mysqli_query($dbcnx,"select * FROM detail  WHERE idsale=$idsale and idmerch=$idmerch");
		if (mysqli_num_rows($r)==0)//не наименования
		mysqli_query($dbcnx,"insert into detail (idsale, idmerch, countmerch) values ($idsale, $idmerch, 1)");		
	}
	
}


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
						<li ><a href="index.php">На главную</a></li>
						<li class="active"><a href="#">Выбор товаров</a></li>  
                          <?
							if ($Mode=="Покупатель")
							{
						 	?>  
						<li ><a href="sale2.php">Корзина</a></li>
						<li ><a href="conf.php?idcategory=1">Конфигуратор</a></li>
						<li><a href="saleuser.php">Заказы</a></li>
                        <?
							}
						?>                                                 
                          <?
							if ($Mode!="")
							{
						 	?>  
						<li ><a href="index.php?step=2">Выход</a></li>    
                        <?
							}
						?>  
					</ul>
					<!-- /NAV -->
				</div>
				<!-- /responsive-nav -->
			</div>
			<!-- /container -->
		</nav>
		<!-- /NAVIGATION -->


<form name="form2"  method="post"  >
<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<!-- ASIDE -->
					<div id="aside" class="col-md-3">
						<!-- aside Widget -->
						<div class="aside">
                        
                        
                        
                        
                        <h3 class="aside-title">Наименование</h3>
                        
                         
									<input  name="merch" value="<? if ($filter==1)/*есть ли фильтрация данных*/ echo "$merch"; ?>" class="input" placeholder="Поиск">

                                    
                                    
                                    
							<h3 class="aside-title">Категории</h3>
                            
<?
 $s="SELECT idcategory, category FROM category";
  $y=mysqli_query($dbcnx,$s);
  
  		$filter=$_GET["filter"];//считывание параметра фильтра


			if (empty ($_REQUEST["pricemin"]) )
				$pricemin=0;
			else
				$pricemin=$_REQUEST["pricemin"];
	
			if (empty ($_REQUEST["pricemax"]) )
				$pricemax=200000;	
			else
				$pricemax=$_REQUEST["pricemax"];

			if ($filter==0)
			{
				$pricemin=0;
				$pricemax=200000;	
			}
			
?>                            
							<div class="checkbox-filter">
  
                                                                      <?
										for ($i=0;$i<mysqli_num_rows($y);$i++)//вывод данных в цикле по количеству записей
										  {
											$u=mysqli_fetch_array($y);//считывание текующей записи		
											?>
								<div class="input-checkbox">
									<input  type="checkbox" <? if ( ($filter==1) && ($_REQUEST["checkbox".$u["idcategory"]]=="on")) echo "checked";?>  name="<? echo "checkbox".$u["idcategory"]?>" id="<? echo "checkbox".$u["idcategory"]?>">
									<label for="<? echo "checkbox".$u["idcategory"]?>">
										<span></span>
										<? echo $u["category"]?>

									</label>
								</div>
<?
										  }
?>
							</div>
						</div>
						<!-- /aside Widget -->



						<!-- aside Widget -->
						<div class="aside">
							<h3 class="aside-title">Цена</h3>
							<div class="price-filter">
								<div id="price-slider"></div>
								<div class="input-number price-min">
									<input id="price-min" name="pricemin" type="number" value=<? echo $pricemin;?>>
									<span class="qty-up">+</span>
									<span class="qty-down">-</span>
								</div>
								<span>-</span>
								<div class="input-number price-max"> 
									<input  id="price-max" name="pricemax" type="number" value=<? echo $pricemax;?>>
									<span class="qty-up">+</span>
									<span class="qty-down">-</span>
								</div>
							</div>
						</div>
						<!-- /aside Widget -->

				<input  type="button"   name="button1"  onclick="this.form.action='sale1.php?filter=1&sort=<? echo $sort;?>'; this.form.submit();"   value="Фильтр">
				<input  type="button"  name="button2"  onclick="this.form.action='sale1.php?filter=0&sort=<? echo $sort;?>'; this.form.submit();"   value="Очистить">

<?
 $s="SELECT  idmerch, merch,  price, annotation, file, category FROM merch INNER JOIN category ON merch.idcategory=category.idcategory LIMIT 0, 3";
  $e=mysqli_query($dbcnx,$s);
?>


                                            
						<!-- aside Widget -->
						<div class="aside">
							<h3 class="aside-title">Лидеры продаж</h3>
							
                                                                      <?
										for ($i=0;$i<mysqli_num_rows($e);$i++)//вывод данных в цикле по количеству записей
										  {
											$f=mysqli_fetch_array($e);//считывание текующей записи		
											?>
                                            
                            <div class="product-widget">
								<div class="product-img">
									<img src="upload/<? echo $f["file"];?>" alt="">
								</div>
								<div class="product-body">
									<p class="product-category"><? echo $f["category"];?></p>
									<h3 class="product-name"><a href="#"><? echo $f["merch"];?></a></h3>
									<h4 class="product-price"><? echo $f["price"];?></h4>
								</div>
							</div>
<?

										  }?>
							
						</div>
						<!-- /aside Widget -->
					</div>
					<!-- /ASIDE -->


<?

									 if ($sort==1)/*есть ли сортировка данных*/
											$fieldsort = $_POST['sortname'];//первое поле
											
?>
					<!-- STORE -->
					<div id="store" class="col-md-9">
						<!-- store top filter -->
						<div class="store-filter clearfix">
							<div class="store-sort">
								<label>
									Сортировка:
									<select name="sortname"  class="input-select" onChange="this.form.action='sale1.php?sort=1&filter=<? echo $filter;?>'; this.form.submit();" >
										<option value="merch"  <? if ($fieldsort=="merch") {?> selected="selected" <? }?>>Наименование</option>
										<option value="price"  <? if ($fieldsort=="price") {?> selected="selected" <? }?>>Цена</option>
									</select>
								</label>



							
							</div>
							

						</div>
						<!-- /store top filter -->

						<!-- store products -->
						<div class="row">
                        
                        <?
						 $smerch="SELECT  idmerch, merch,  price, annotation, file, category FROM merch, category where merch.idcategory=category.idcategory  ";

						  
						//  phpinfo(32);
							if ($filter==1)
							{

								$smerch=$smerch." and UPPER(merch) LIKE UPPER('%$merch"."%')   ";

 							$scat="SELECT idcategory, category FROM category";
 								 $fcat=mysqli_query($dbcnx,$scat);
		
										$first=0;
										for ($i=0;$i<mysqli_num_rows($fcat);$i++)//вывод данных в цикле по количеству записей
										  {
											$rcat=mysqli_fetch_array($fcat);//считывание текующей записи		

												  if ($_REQUEST["checkbox".$rcat["idcategory"]]=="on")
												  	{
													if ($first==0)
														$smerch=$smerch. " and (";													
													if ($first==1)
														$smerch=$smerch." or ";	
													$first=1;	
												 	$smerch=$smerch." merch.idcategory = ".$rcat["idcategory"]; 
													}
										  }
										  
							if ($first==1)										  
							 $smerch=$smerch. " ) ";		
							$smerch=$smerch." and price>=$pricemin and price<=$pricemax";
							}
									 if ($sort==1)/*есть ли сортировка данных*/
											{
											$fieldsort = $_POST['sortname'];//первое поле
											$smerch=$smerch." order by $fieldsort";
											}

                                   // echo $smerch;
                                    
                      						  $fmerch=mysqli_query($dbcnx,$smerch);              
						
							if (mysqli_num_rows($fmerch) == 0)
								echo "Ничего не найдено, попробуйте другие параметры поиска";
						                                                                      
										for ($i=0;$i<mysqli_num_rows($fmerch);$i++)//вывод данных в цикле по количеству записей
										  {
											$f=mysqli_fetch_array($fmerch);//считывание текующей записи
											
											$idmerch=$f["idmerch"];
											
 											$s="SELECT ROUND(AVG(mark), 2) as avgmark FROM response WHERE idmerch=$idmerch";
											$SET_response=mysqli_query($dbcnx,$s);
											$d=mysqli_fetch_array($SET_response);//считывание текующей записи											
											
											
													
						?>
                        
							<!-- product -->
							<div class="col-md-4 col-xs-6">
								<div class="product">
									<div class="product-img">
										<img src="upload/<? echo $f["file"];?>" alt="">

									</div>
									<div class="product-body">
										<p class="product-category"><? echo $f["category"];?></p>
										<h3 class="product-name"><a href="annotation.php?idmerch=<? echo $f["idmerch"]?>"><? echo $f["merch"];?></a></h3>
										<h4 class="product-price"><? echo $f["price"];?></h4>
													<div class="product-rating">
															 <?
                                                            for ($j=1;$j<=5;$j++)
															{
																if ($j	<= $d["avgmark"])																
																{
																?>
																<i class="fa fa-star"></i>
																<?
                                                                }
																else
																{
																?>
																<i class="fa fa-star-o"></i>
	                                                            <?
																}
															}
															?>
                                                            
													</div>

									</div>
                                     <?
 	if ($Mode=="Покупатель")
						{
 ?>
									<div class="add-to-cart">
										<button onClick="this.form.action='sale1.php?idmerch=<? echo("$f[idmerch]");?>&step=2&sort=1&filter=<? echo $filter;?>'; this.form.submit();" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> В корзину</button>
									</div>
                                    <?
						}
									?>
								</div>
							</div>
							<!-- /product -->
											<?
											}?>



					
						</div>
						<!-- /store products -->


					</div>
					<!-- /STORE -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->
</form>

	
    

        
        	

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

