<?
require "option.php";//файл с параметрами подключения к БД

$step=$_REQUEST['step'];
	 
		 if ($step==3)
		  	  {
				 			$s="select iddetail from detail, sale, merch, category where merch.idmerch=detail.idmerch and detail.idsale=sale.idsale and merch.idcategory=category.idcategory and sale.iduser=$iduser and kind='Корзина' order by iddetail";
		 
						    $r=mysqli_query($dbcnx,$s);
				   			for ($i=0;$i<mysqli_num_rows($r);$i++)//вывод данных в цикле по количеству записей
			  				{
							$f=mysqli_fetch_array($r);//считывание текующей записи		
							mysqli_query($dbcnx,"update detail set countmerch=".$_REQUEST['ArrCount'][$i]." where iddetail=".$f["iddetail"]);
							} 
				  
				  
				  
				$s="update sale set kind='Заказ' where sale.iduser=$iduser and kind='Корзина' ";	

		 		mysqli_query($dbcnx,$s);
		 ?>
          <script language="javascript">
		    alert("Ваш заказ принят! Наш менеджер в ближайшее время свяжется с Вами!");
			location.href='sale1.php?category=merch.idcategory';
		 </script>
         <? 
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
						<li  class="active"><a href="sale2.php">Корзина</a></li>
						<li ><a href="conf.php?idcategory=1">Конфигуратор</a></li>
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
                
<h3 class="breadcrumb-header">Корзина покупателя</h3>
<form name="form2"  method="post"  >
 <?
 	//забираем текущую дату
	date_default_timezone_set("Europe/Moscow");
	$Now=date("Y")."-".date("m")."-".date("d");   
	
 		 $s="select  fio from user where iduser=$iduser";
 
		 $r=mysqli_query($dbcnx,$s);
		 $f=mysqli_fetch_array($r);//считывание текующей записи	
 ?>

         
     		<div align="left">	
Дата продажи:    <? echo $Now;?>    <br>
Покупатель:       <? echo $f["fio"];?>  <br> <br>

              </div>   
              
              
  <table WIDTH=100% border=1 cellspacing=0 cellpadding=3>
									<tr>
		<td  ><font color=white>&nbsp;</font></td>                                    
		<td ><h5>Товар</h5></td>
		<td ><h5>Категория</h5></td>        
		<td ><h5>Цена</h5></td>			
		<td ><h5>Количество</h5></td>		        	
			</tr>
        
        
      <?

		 if ($step==2)
		  	  {
				  $s="select iddetail from detail, sale, merch, category where merch.idmerch=detail.idmerch and detail.idsale=sale.idsale and merch.idcategory=category.idcategory and sale.iduser=$iduser and kind='Корзина' order by iddetail";
		 
				 $r=mysqli_query($dbcnx,$s);
				   			for ($i=0;$i<mysqli_num_rows($r);$i++)//вывод данных в цикле по количеству записей
			  				{
							$f=mysqli_fetch_array($r);//считывание текующей записи		
							mysqli_query($dbcnx,"update detail set countmerch=".$_REQUEST['ArrCount'][$i]." where iddetail=".$f["iddetail"]);
							}
			  
			  }
			  
 		 $s="select merch.idmerch as idmerch, merch, category, price, countmerch from detail, sale, merch, category where merch.idmerch=detail.idmerch and detail.idsale=sale.idsale and merch.idcategory=category.idcategory and sale.iduser=$iduser and kind='Корзина' order by iddetail";		
		 $r=mysqli_query($dbcnx,$s);
				 
				 
				 
	$sumsale=0;			 
			for ($i=0;$i<mysqli_num_rows($r);$i++)//вывод данных в цикле по количеству записей
			  {
				$f=mysqli_fetch_array($r);//считывание текующей записи				
				echo "<tr>";


	
			
	if ($i==0)
    echo "<td><input type=radio checked=checked name=ArrMerch[] value=".$f["idmerch"]."> </td>";
	else
    echo "<td><input type=radio name=ArrMerch[] value=".$f["idmerch"]."> </td>";
				?>				
				<td>
				<a href="annotation.php?idmerch=<? echo $f["idmerch"]?>"><? echo $f["merch"];?></a>
				</td>				
				<?
				echo "
				<td> $f[category]</td>				
				<td> $f[price]</td>	
				<td width=5%><input type=text name=ArrCount[] value= $f[countmerch] align=right> </td>				
						
				";								
				echo "</tr>";
				$sumsale=$sumsale+$f["countmerch"]*$f["price"];
			  }		 

		?>
    		<tr>
            <td colspan="5" align="left">
            <? echo "Общая сумма: $sumsale"?>
            </td>
		</tr>
		
  </table> 
<br>
<input   type="button"  name="button" <? if (mysqli_num_rows($r)==0) {?>    disabled="disabled"<? }?>  onclick="qwest=window.confirm('Подтверждаете заказ?');  if (qwest) {this.form.action='sale2.php?step=3'; this.form.submit();}" value="Заказать">  
  
 <input  type="button"  name="button4"  <? if (mysqli_num_rows($r)==0) {?>    disabled="disabled"<? }?>    onclick="this.form.action='sale2.php?step=2'; this.form.submit();" value="Сохранить">
<input  type="button"  name="button" <? if (mysqli_num_rows($r)==0) {?>    disabled="disabled"<? }?>  onclick="qwest=window.confirm('Вы действительно хотите очистить корзину?');  if (qwest) {this.form.action='clearsale.php'; this.form.submit();}" value="Очистить">    



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
