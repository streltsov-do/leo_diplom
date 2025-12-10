<?

require "option.php";//файл с параметрами подключения к БД

$step=$_REQUEST["step"];
if ($step==1)
{
$login=$_POST["login"];
$parol=$_POST["parol"];
$SET_user=mysqli_query($dbcnx,"select * from user where login='$login' and parol='$parol'");
$COUNT_user=mysqli_num_rows($SET_user);
if  (($COUNT_user==0)&&($COUNT_worker==0))
{
?>
<meta charset="utf-8">
<script language="javascript">
alert("Не верный ввод!");
history.back();
</script>
<?
exit();
} 
if ($COUNT_user>0)
{
		$f=mysqli_fetch_array($SET_user);//считывание текующей записи
		$fio=$f["fio"];
		setcookie ( 'fio', $fio); 
		$iduser=$f["iduser"];
		setcookie ( 'iduser', $iduser); 
		$Mode=$f["permission"];		
		setcookie ( 'Mode', $Mode); 
		//echo $Mode;
		//echo $iduser;		
	 ?>
			<script language="javascript">			
 			location.href='index.php';
			</script>
	 <?
}
}

if ($step==2)//выход
{
setcookie ( 'Mode', ""); 
$Mode="";
$step=0;
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
						<li ><a href="#auth">Авторизация</a></li>  
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

		<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<!-- shop -->
					<div class="col-md-4 col-xs-6">
						<div class="shop">
							<div class="shop-img">
								<img src="./img/proce.jpg" alt="">
							</div>
							<div class="shop-body">
								<h3>Коллекция<br>Процессоров</h3>
								<a href="sale1.php?filter=1&checkbox4=on" class="cta-btn">Купить <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
					<!-- /shop -->

					<!-- shop -->
					<div class="col-md-4 col-xs-6">
						<div class="shop">
							<div class="shop-img">
								<img src="./img/vidio.jpg" alt="">
							</div>
							<div class="shop-body">
								<h3>Коллекция<br>Видеокарт</h3>
								<a href="sale1.php?filter=1&checkbox3=on" class="cta-btn">Купить <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
					<!-- /shop -->

					<!-- shop -->
					<div class="col-md-4 col-xs-6">
						<div class="shop">
							<div class="shop-img">
								<img src="./img/front.jpg" alt="">
							</div>
							<div class="shop-body">
								<h3>Коллекция<br>Мониторов</h3>
								<a href="sale1.php?filter=1&checkbox9=on" class="cta-btn">Купить <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
					<!-- /shop -->
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
											$idmerch=$f["idmerch"];
											
 											$s="SELECT ROUND(AVG(mark), 2) as avgmark FROM response WHERE idmerch=$idmerch";
											$SET_response=mysqli_query($dbcnx,$s);
											$d=mysqli_fetch_array($SET_response);//считывание текующей записи
											
												
											?>
										<!-- product -->
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
											<div class="add-to-cart">
												<button onclick="location.href='sale1.php'; " class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> Купить</button>
											</div>
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
    
  
        
 <?                  if ( $Mode=="")
{
?>
		<!-- SECTION -->
		<a name="auth"></a>
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
                


	<form name="form2" onaction="this.form.action='index.php?step=1'" method="post"  >		
							<table align="center">				
							<tr>
							<td>
							Авторизация:
							</td>
                                                        </tr>							
                                                        <tr>
							<td>
							Логин:
							</td>
                                                        </tr>
                            <tr>
							<td>
<input    name="login"   value=""  type="text" >
							</td>
							</tr>
							
							<tr>
							<td>
							Пароль:
							</td>
                            </tr>
                            <tr>
							<td>
<input    name="parol"    value=""  type="password" >							
							</td>
							</tr>	
	<tr>
		<td align="right"><input  name="button"  type="button"   onClick="this.form.action='index.php?step=1>'; this.form.submit();" value="Вход" width="600"><input  name="button"  type="button"   onClick="this.form.action='reg.php?reg=1>'; this.form.submit();" value="Регистрация" width="600"></td>											
					</tr>
							</table>
</form>	


				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->
<?
}
?>       	

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
