<?
require "option.php";//файл с параметрами подключения к БД

if (isset ($_POST['ArrMerch']))
{
 $Arr=$_POST['ArrMerch'];
 $idmerch=$Arr[0];
 setcookie ( 'idmerch', $idmerch); 
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
						<li><a href="user.php">Пользователи</a></li>
						<li><a href="category.php">Категории</a></li>
						<li class="active"><a href="merch.php">Товары</a></li>
						<li><a href="sale.php">Заказы</a></li>
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

		 $s="SELECT  merch FROM merch where  idmerch=$idmerch";
		 $r=mysqli_query($dbcnx,$s);
 		 $f=mysqli_fetch_array($r);//считывание текующей записи		

?>
<h3 class="breadcrumb-header">Разъемы товара (<? echo $f["merch"]?>)</h3>
 <form name="form2"  method="post"  >

<?

$s="SELECT connector, idmerchconnector, connector.idconnector as idconnector  FROM connector LEFT JOIN merchconnector ON connector.idconnector=merchconnector.idconnector and idmerch=$idmerch order by connector";		 

$r=mysqli_query($dbcnx,$s);
	
$step=$_REQUEST["step"];
if  ($step==2) //очищаем 
	mysqli_query($dbcnx,"delete from merchconnector where idmerch=$idmerch");
if ($step==1)//редактируем состав 
{

			for ($i=0;$i<mysqli_num_rows($r);$i++)//вывод данных в цикле по количеству записей
			  {
				$f=mysqli_fetch_array($r);//считывание текующей записи			
				$idconnector=	$f["idconnector"];
				mysqli_query($dbcnx,"delete from merchconnector where idmerch=$idmerch and idconnector=$idconnector");
				if ($_REQUEST["checkbox".$idconnector]=="on")
					mysqli_query($dbcnx,"insert into merchconnector (idconnector, idmerch) VALUES ($idconnector, $idmerch)");
				
			  }

}	
if ( ($step==1) || ($step==2) )
$r=mysqli_query($dbcnx,$s);//еще раз делаем выборку



	 ?>
         

<input   type="button"   name="button"   onclick="qwest=window.confirm('Вы дествительно хотите сохранить разъемы?');  if (qwest) {this.form.action='merchconnector.php?step=1'; this.form.submit();}else {this.form.action='#'; this.form.submit();}" value="Сохранить">      
<input   type="button"    name="button"  onclick="qwest=window.confirm('Вы дествительно хотите очистить разъемы?');  if (qwest) {this.form.action='merchconnector.php?step=2'; this.form.submit();}" value="Очистить"> 

     	
       <br>        
              
  <table WIDTH=100% border=1 cellspacing=0 cellpadding=3>
									<tr>
		<td  ><font color=white>&nbsp;</font></td>                                    
		<td ><h5>Разъём </h5></td>
      
       				
				
		</tr>
        
        
      <?
		 
		 

			for ($i=0;$i<mysqli_num_rows($r);$i++)//вывод данных в цикле по количеству записей
			  {
				$f=mysqli_fetch_array($r);//считывание текующей записи				
				echo "<tr>";
?>
<td>			
<input  type="checkbox" <? if ($f["idmerchconnector"]!=NULL) echo "checked";?>   name="checkbox<? echo $f[idconnector]?>"     >
</td>
<?			
				echo "
				<td> $f[connector] &nbsp; </td>
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
