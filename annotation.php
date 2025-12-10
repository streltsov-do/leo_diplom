<?
/*
1.Подключение к БД и начало HTML-шаблона
2.Верхний хедер с контактами
3.Основной хедер с лого и поиском
4.Навигационное меню
5.Получение данных о товаре
6.Отображение деталей товара
7.Расчет статистики отзывов
8.Блок с рейтингом товара
9.Вывод отзывов пользователей
10.Форма добавления отзыва
11.Блок "Лидеры продаж"
12.Футер
13.Подключение JS-скриптов
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

						<li class="active"><a href="index.php">На главную</a></li>
                          <?
							if ($Mode=="Администратор")
							{
						 	?>  
						<li><a href="user.php">Пользователи</a></li>
						<li><a href="category.php">Категории</a></li>
						<li><a href="merch.php">Товары</a></li>
						<li><a href="sale.php">Заказы</a></li>
                        <?
							}
						?>
                                                
						<li ><a href="sale1.php">Выбор товаров</a></li>     
                        
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
							else
							{
						?>
						<li ><a href="reg.php">Регистрация</a></li>  
						<li ><a href="index.php#bottom-footer">Авторизация</a></li>  
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

		

<?
$idmerch=$_REQUEST["idmerch"];

$SET_merch=mysqli_query($dbcnx,"SELECT  idmerch, merch,  price, annotation, file, category FROM merch INNER JOIN category ON merch.idcategory=category.idcategory  and idmerch='$idmerch' ");


		$f=mysqli_fetch_array($SET_merch);//считывание текующей записи




?>
		
    <!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<!-- Product main img -->
					<div class="col-md-5 col-md-push-2">
						<div id="product-main-img">
							<div class="product-preview">
								<img src="upload/<? echo $f["file"];?>" alt="">
							</div>


						</div>
					</div>
					<!-- /Product main img -->

					<!-- Product thumb imgs -->
					<div class="col-md-2  col-md-pull-5">

					</div>
					<!-- /Product thumb imgs -->

					<!-- Product details -->
					<div class="col-md-5">
						<div class="product-details">
							<h2 class="product-name"><? echo $f["merch"];?></h2>
							<div>

								
							</div>
							<div>
								<h3 class="product-price"><? echo $f["price"];?> рублей</h3>
						
							</div>
							<p><? echo $f["annotation"];?></p>

							
                                     <?
 	if ($Mode=="Покупатель")
						{
 ?>
									<div class="add-to-cart">
										<button onClick="window.location.href='sale1.php?idmerch=<? echo("$f[idmerch]");?>&step=2&sort=0&filter=0';" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> В корзину</button>
									</div>
                                    <?
						}
									?>
							

						</div>
					</div>
					<!-- /Product details -->


				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

<?
$s="
SELECT COUNT(idresponse) as countmark,  ROUND(AVG(mark), 2) as avgmark, 
(SELECT COUNT(idresponse) FROM response WHERE idmerch=$idmerch) as allmark, 
(SELECT COUNT(idresponse) FROM response WHERE idmerch=$idmerch and mark=5) as mark5, 
(SELECT COUNT(idresponse) FROM response WHERE idmerch=$idmerch and mark=4) as mark4,
(SELECT COUNT(idresponse) FROM response WHERE idmerch=$idmerch and mark=3) as mark3,
(SELECT COUNT(idresponse) FROM response WHERE idmerch=$idmerch and mark=2) as mark2,
(SELECT COUNT(idresponse) FROM response WHERE idmerch=$idmerch and mark=1) as mark1,
(SELECT COUNT(idresponse) FROM response WHERE idmerch=$idmerch and mark=0) as mark0
FROM response
WHERE idmerch=$idmerch and mark>0";
$SET_response=mysqli_query($dbcnx,$s);

$f=mysqli_fetch_array($SET_response);//считывание текующей записи

if ($f["countmark"]==0)
	{
	$percent5=0;
	$percent4=0;
	$percent3=0;
	$percent2=0;
	$percent1=0;
	$percent0=0;
	}
else
	{
	$percent5=$f["mark5"]*100/$f["allmark"];
	$percent4=$f["mark4"]*100/$f["allmark"];
	$percent3=$f["mark3"]*100/$f["allmark"];
	$percent2=$f["mark2"]*100/$f["allmark"];
	$percent1=$f["mark1"]*100/$f["allmark"];
	$percent0=$f["mark0"]*100/$f["allmark"];
	}
?>


<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">

<!-- tab3  -->
								<div id="tab3" class="tab-pane fade in">
									<div class="row">
										<!-- Rating -->
										<div class="col-md-3">
											<div id="rating">
												<div class="rating-avg">
													<span><? if ($f["countmark"]>0) echo $f["avgmark"]; else echo "Нет оценок"; ?> (<?  echo $f["countmark"]?>)</span>
													<div class="rating-stars">
 <?
                                                            for ($j=1;$j<=5;$j++)
															{
																if ($j	<= $f["avgmark"])																
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
												<ul class="rating">
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
														</div>
														<div class="rating-progress">
															<div style="width: <? echo $percent5;?>%;"></div>
														</div>
														<span class="sum"><? echo $f["mark5"]?></span>
													</li>
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star-o"></i>
														</div>
														<div class="rating-progress">
															<div style="width: <? echo $percent4;?>%;"></div>
														</div>
														<span class="sum"><? echo $f["mark4"]?></span>
													</li>
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
														</div>
														<div class="rating-progress">
															<div style="width: <? echo $percent3;?>%;"></div>
														</div>
														<span class="sum"><? echo $f["mark3"]?></span>
													</li>
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
														</div>
														<div class="rating-progress">
															<div style="width: <? echo $percent2;?>%;"></div>
														</div>
														<span class="sum"><? echo $f["mark2"]?></span>
													</li>
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
														</div>
														<div class="rating-progress">
															<div style="width: <? echo $percent1;?>%;"></div>
														</div>
														<span class="sum"><? echo $f["mark1"]?></span>
													</li>
													<li>
														<div class="rating-stars">
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
														</div>
														<div class="rating-progress">
															<div style="width: <? echo $percent0;?>%;"></div>
														</div>
														<span class="sum"><? echo $f["mark0"]?></span>
													</li>                                                    
												</ul>
											</div>
										</div>
										<!-- /Rating -->
<?
$s="
SELECT fio, response, dateresponse, mark 
FROM response, user
WHERE user.iduser=response.iduser and idmerch=$idmerch
ORDER BY dateresponse DESC";
$SET_response=mysqli_query($dbcnx,$s);


?>

										<!-- Reviews -->
										<div class="col-md-6">
                                        
											<div id="reviews">
												<ul class="reviews">
													
                                                    <?
                                                    for ($i=0;$i<mysqli_num_rows($SET_response);$i++)//вывод данных в цикле по количеству записей
													{
													$f=mysqli_fetch_array($SET_response);//считывание текующей записи
													?>
                                                    <li>
														<div class="review-heading">
															<h5 class="name"><? echo $f["fio"];?></h5>
															<p class="date"><? echo $f["dateresponse"];?></p>
															<div class="review-rating">
                                                            <?
                                                            for ($j=1;$j<=5;$j++)
															{
																if ($j	<= $f["mark"])																
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
														<div class="review-body">
															<p><? echo $f["response"];?></p>
														</div>
                                                        </li>
                                                    <?
													
													}
													?>
													
												</ul>
	
											</div>
										</div>
                                        
										<!-- /Reviews -->
                                       
<?
                        
							if ($Mode=="Покупатель")
							{
						 	
                            
$SET_user=mysqli_query($dbcnx,"SELECT  * FROM user WHERE iduser='$iduser' ");
$f=mysqli_fetch_array($SET_user);//считывание текующей записи
?>
										<!-- Review Form -->
										<div class="col-md-3">
											<div id="review-form">
												<form class="review-form" method="post"  enctype="multipart/form-data">
													<input name="name" class="input" readonly type="text" value="<? echo $f["fio"];?>" >
													<input name="email" class="input" readonly type="email" value="<? echo $f["mail"];?>">
													<textarea name="response" class="input" placeholder="Ваш отзыв"></textarea>
													<div class="input-rating">
														<span>Ваша оценка: </span>
														<div  class="stars">
															<input id="star5" name="mark" value="5" type="radio"><label for="star5"></label>
															<input id="star4" name="mark" value="4" type="radio"><label for="star4"></label>
															<input id="star3" name="mark" value="3" type="radio"><label for="star3"></label>
															<input id="star2" name="mark" value="2" type="radio"><label for="star2"></label>
															<input id="star1" name="mark" value="1" type="radio"><label for="star1"></label>
														</div>
													</div>

                                                    <input class="primary-btn" type="button"  name="button"   onclick="this.form.action='addresponse.php?idmerch=<? echo "$idmerch";?>'; this.form.submit();"   value="Оставить отзыв" width="500">
												</form>
											</div>
										</div>
										<!-- /Review Form -->
                                        
                                        <?
							}
										?>
                                        
									</div>
								</div>
								<!-- /tab3  -->
                                
 				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->                               

<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">

					<!-- section title -->
					<div class="col-md-12">
						<div class="section-title">
							<h3 class="title">Лидеры продаж</h3>

						</div>
					</div>
					<!-- /section title -->
<?
 $s="SELECT  idmerch, merch,  price, annotation, file, category FROM merch INNER JOIN category ON merch.idcategory=category.idcategory LIMIT 0, 5";
  $r=mysqli_query($dbcnx,$s);
?>
					<!-- Products tab & slick -->
					<div class="col-md-12">
						<div class="row">
							<div class="products-tabs">
								<!-- tab -->
								<div id="tab2" class="tab-pane fade in active">
									<div class="products-slick" data-nav="#slick-nav-2">
                                    
                                    
                                          <?
										for ($i=0;$i<mysqli_num_rows($r);$i++)//вывод данных в цикле по количеству записей
										  {
											$f=mysqli_fetch_array($r);//считывание текующей записи		
											?>
										<!-- product -->
										<div class="product">
											<div class="product-img">
												<img src="upload/<? echo $f["file"];?>" alt="">

											</div>
											<div class="product-body">
												<p class="product-category"><? echo $f["category"];?></p>
												<h3 class="product-name"><a href="annotation.php?idmerch=<? echo $f["idmerch"]?>"><? echo $f["merch"];?></a></h3>
												<h4 class="product-price"><? echo $f["price"];?> рублей</h4>
												<div class="product-rating">
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
												</div>
											</div>
                                     <?
 	if ($Mode=="Покупатель")
						{
 ?>
									<div class="add-to-cart">
										<button onClick="window.location.href='sale1.php?idmerch=<? echo("$f[idmerch]");?>&step=2&sort=0&filter=0';" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> В корзину</button>
									</div>
                                    <?
						}
									?>
										</div>
										<!-- /product -->
<?
										  }
?>
									</div>
									<div id="slick-nav-2" class="products-slick-nav"></div>
								</div>
								<!-- /tab -->
							</div>
						</div>
					</div>
					<!-- /Products tab & slick -->
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
