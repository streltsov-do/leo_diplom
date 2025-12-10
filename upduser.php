<?
require "option.php";//файл с параметрами подключения к БД
?>

<?
$step=$_REQUEST["step"];
if ($step==2)
{
$upd=$_REQUEST["upd"];

$fio =  $_POST["fio"];
$mail =  $_POST["mail"];
$login =  $_POST["login"];
$parol =  $_POST["parol"];
$permission =  $_POST["permission"];



	if ((strlen($login)>50) or (strlen($login)==0)) 
	{
	?>
		<script language="javascript">
		alert("Не верный ввод!");
		history.back();
		</script>

	<?
		exit();
	}	
	
	

  {//формирование SQL-запроса на добавление данных
	 mysqli_query($dbcnx,"INSERT INTO user (login, parol, mail, permission, fio) VALUES ('$login', '$parol', '$mail', '$permission', '$fio')");
	 	
	?>
	 <script language="javascript">
	 location.href='user.php?filter=0';
	 </script>
	 <?
  }
exit();
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
						<li  class="active"><a href="user.php">Пользователи</a></li>
						<li><a href="category.php">Категории</a></li>
						<li><a href="merch.php">Товары</a></li>
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
                
<h3 class="breadcrumb-header">Добавление пользователя</h3>
<?
		 $s="select * from user";
		 $r=mysqli_query($dbcnx,$s);
?>

<form name="form2" method="post"  >
				  <table width="422" border="0">
                    <tr>
                      <td width="107"><font color="#000000" >   ФИО: </font> </td>
                      <td><input   name="fio"   type="text"  value="<? if ($upd==1) echo "$f[fio]"; else echo(""); ?>" size="40" ></td>
                    </tr>  	          
                    <tr>
                      <td width="107"><font color="#000000" >   Почта: </font> </td>
                      <td><input   name="mail"   type="text"  value="<? if ($upd==1) echo "$f[mail]"; else echo(""); ?>" size="40" ></td>
                    </tr>  	                                                                 
                    <tr>
                      <td><font color="#000000" > Логин: </font> </td>
                      <td><input   name="login"  value="<? if ($upd==1) echo "$f[login]"; else echo(""); ?>"   type="text" ></td>
                    </tr>  				  
                    <tr>
                      <td><font color="#000000" >  Пароль: </font> </td>
                      <td><input   name="parol"  value="<? if ($upd==1) echo "$f[parol]"; else echo(""); ?>"   type="text" ></td>
                    </tr>      
                                 <tr>
                      <td><font color="#000000" >   Права: </font></td>
                      <td>
                 <select  name="permission"  style="height:22; width:auto"    >
					<option   value="Администратор"  >Администратор </option>	       
                    <option  value="Пользователь"    >Пользователь </option>	         																				
				</select>                    
				      </td> 
                      </tr>   
                  </table>
				<br>
				<input  type="button"  name="button"   onclick="this.form.action='upduser.php?step=2&upd=<? echo"$upd";?>&id=<? echo"$Arr[0]";?>'; this.form.submit();"   value="OK" width="500">
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
