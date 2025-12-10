<?php
/*
1.Начало файла и подключение    
2.Обработка авторизации    
3.Обработка выхода    
4.HTML-шаблон и стили    
5.Верхний хедер    
6.Основной хедер    
7.Навигационное меню    
8.Блок «Коллекции»    
9.Блок «Лидеры продаж»    
10.Форма авторизации
11.Футер    
12.Подключение скриптов
*/

// Начинаем сессию для работы с конфигуратором
session_start();

require "option.php";//файл с параметрами подключения к БД

$step=$_REQUEST["step"] ?? '';
$Mode=$_COOKIE['Mode'] ?? '';
$fio=$_COOKIE['fio'] ?? '';
$iduser=$_COOKIE['iduser'] ?? '';

// Инициализируем сессию конфигуратора если не существует
if (!isset($_SESSION['configurator'])) {
    $_SESSION['configurator'] = [];
}

if ($step==1)
{
    $login=$_POST["login"] ?? '';
    $parol=$_POST["parol"] ?? '';
    
    $SET_user=mysqli_query($dbcnx,"select * from user where login='$login' and parol='$parol'");
    $COUNT_user=mysqli_num_rows($SET_user);
    
    if ($COUNT_user==0)
    {
        // Показываем alert и возвращаем назад как в оригинале
        ?>
        <meta charset="utf-8">
        <script language="javascript">
        alert("Не верный ввод!");
        history.back();
        </script>
        <?php
        exit();
    } 
    else if ($COUNT_user>0)
    {
        $f=mysqli_fetch_array($SET_user);//считывание текующей записи
        $fio=$f["fio"];
        setcookie ( 'fio', $fio, time()+3600, '/'); 
        $iduser=$f["iduser"];
        setcookie ( 'iduser', $iduser, time()+3600, '/'); 
        $Mode=$f["permission"];        
        setcookie ( 'Mode', $Mode, time()+3600, '/'); 
        
        // Перенаправляем после успешной авторизации
        ?>
        <script language="javascript">            
            location.href='index.php';
        </script>
        <?php
        exit();
    }
}

if ($step==2)//выход
{
    // Удаляем куки
    setcookie ( 'Mode', "", time()-3600, '/'); 
    setcookie ( 'fio', "", time()-3600, '/');
    setcookie ( 'iduser', "", time()-3600, '/');
    
    // Очищаем сессию конфигуратора при выходе
    unset($_SESSION['configurator']);
    
    // Перенаправляем после выхода
    ?>
    <script language="javascript">            
        location.href='index.php';
    </script>
    <?php
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
<?php
if ($Mode!="")
{
?>
    <li><a href="#"><i class="fa fa-user-o"></i> <?php echo htmlspecialchars($fio)." ($Mode)";?></a></li>
<?php
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
                                <form method="post" action="sale1.php">
                                    <input name="merch" class="input" placeholder="Поиск">
                                    <input type="hidden" name="filter" value="1">
                                    <button type="submit" class="search-btn">Искать</button>
                                </form>
                            </div>
                        </div>

<?php
if ($Mode=="Покупатель" && isset($iduser))
{
    // Считаем товары в корзине из БД
    $r = mysqli_query($dbcnx, "SELECT detail.iddetail FROM detail, sale WHERE sale.idsale=detail.idsale and iduser=$iduser and kind='Корзина'");
    $count = mysqli_num_rows($r);
    
    // Добавляем товары из конфигуратора (если они есть в сессии)
    $config_count = count($_SESSION['configurator']);
    $total_count = $count + $config_count;
?>
    <!-- ACCOUNT -->
    <div class="col-md-3 clearfix">
        <div class="header-ctn">
            <!-- Cart -->
            <div>
                <a href="sale2.php">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Корзина</span>
                    <div class="qty"><?php echo $total_count;?></div>
                </a>
            </div>
        </div>
    </div>
    <!-- /ACCOUNT -->
<?php
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
<?php
if ($Mode=="Администратор")
{
?>  
    <li><a href="user.php">Пользователи</a></li>
    <li><a href="category.php">Категории</a></li>
    <li><a href="merch.php">Товары</a></li>
    <li><a href="sale.php">Заказы</a></li>
<?php
}
?>                                                
                        <li><a href="sale1.php">Выбор товаров</a></li>     
                        
<?php
if ($Mode=="Покупатель")
{
?>  
    <li><a href="sale2.php">Корзина</a></li>
    <li><a href="conf.php?idcategory=1">Конфигуратор</a></li>
    <li><a href="saleuser.php">Заказы</a></li>
<?php
}
?>                        
<?php
if ($Mode!="")
{
?>  
    <li><a href="index.php?step=2">Выход</a></li>    
<?php
}
else
{
?>
    <li><a href="reg.php">Регистрация</a></li>  
    <li><a href="#auth">Авторизация</a></li>  
<?php
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
                                <img src="./img/proce.jpg" alt="Процессоры">
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
                                <img src="./img/vidio.jpg" alt="Видеокарты">
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
                                <img src="./img/front.jpg" alt="Мониторы">
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
<?php
$s="SELECT idmerch, merch, price, annotation, file, category FROM merch INNER JOIN category ON merch.idcategory=category.idcategory LIMIT 0, 5";
$r=mysqli_query($dbcnx,$s);
?>
                    <!-- Products tab & slick -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="products-tabs">
                                <!-- tab -->
                                <div id="tab2" class="tab-pane fade in active">
                                    <div class="products-slick" data-nav="#slick-nav-2">
<?php
for ($i=0;$i<mysqli_num_rows($r);$i++)//вывод данных в цикле по количеству записей
{
    $f=mysqli_fetch_array($r);//считывание текующей записи    
    $idmerch=$f["idmerch"];
    
    $s="SELECT ROUND(AVG(mark), 2) as avgmark FROM response WHERE idmerch=$idmerch";
    $SET_response=mysqli_query($dbcnx,$s);
    $d=mysqli_fetch_array($SET_response);//считывание текующей записи
    $avgmark = $d["avgmark"] ?? 0;
?>
    <!-- product -->
    <div class="product">
        <div class="product-img">
            <img src="upload/<?php echo htmlspecialchars($f["file"]);?>" alt="<?php echo htmlspecialchars($f["merch"]);?>">
        </div>
        <div class="product-body">
            <p class="product-category"><?php echo htmlspecialchars($f["category"]);?></p>
            <h3 class="product-name"><a href="annotation.php?idmerch=<?php echo $f["idmerch"]?>"><?php echo htmlspecialchars($f["merch"]);?></a></h3>
            <h4 class="product-price"><?php echo number_format($f["price"], 0, ',', ' ');?> ₽</h4>
            <div class="product-rating">
<?php
for ($j=1;$j<=5;$j++)
{
    if ($j <= $avgmark)
    {
?>
        <i class="fa fa-star"></i>
<?php
    }
    else
    {
?>
        <i class="fa fa-star-o"></i>
<?php
    }
}
?>
            </div>
        </div>
        <div class="add-to-cart">
            <button onclick="location.href='sale1.php';" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> Купить</button>
        </div>
    </div>
    <!-- /product -->
<?php
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
        
<?php
if ($Mode=="")
{
?>
    <!-- SECTION -->
    <a name="auth" id="auth"></a>
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="auth-form">
                        <h3>Авторизация</h3>
                        
                        <form name="form2" method="post" action="index.php?step=1">        
                            <div class="form-group">
                                <label for="login">Логин:</label>
                                <input name="login" id="login" value="" type="text" class="form-control" required autofocus>
                            </div>
                            
                            <div class="form-group">
                                <label for="parol">Пароль:</label>
                                <input name="parol" id="parol" value="" type="password" class="form-control" required>
                            </div>    
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Вход</button>
                                <button type="button" onclick="location.href='reg.php?reg=1';" class="btn btn-default">Регистрация</button>
                            </div>
                        </form>    
                    </div>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->
<?php
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
                                Copyright &copy;<script>document.write(new Date().getFullYear());</script> Все права защищены
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