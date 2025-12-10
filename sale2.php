<?php
/*
1.Подключение настроек БД
2.Обработка оформления заказа (step=3)
3.HTML-шаблон с CSS
4.Хедер с информацией о пользователе
5.Поиск товаров
6.Навигационное меню
7.Отображение даты и покупателя
8.Форма с товарами в корзине
9.Обработка изменения количества товаров (step=2)
10.Вывод таблицы с товарами в корзине
11.Расчет общей суммы заказа
12.Кнопки управления: Заказать, Сохранить, Очистить
13.Футер и скрипты
*/

// Начинаем сессию для работы с конфигуратором
session_start();

require "option.php";//файл с параметрами подключения к БД

$iduser = $_COOKIE['iduser'] ?? '';
$fio = $_COOKIE['fio'] ?? '';
$Mode = $_COOKIE['Mode'] ?? '';

$step = $_REQUEST['step'] ?? '';

// Проверка авторизации
if ($Mode != "Покупатель" || empty($iduser)) {
    ?>
    <meta charset="utf-8">
    <script language="javascript">
        alert("Для доступа к корзине необходимо авторизоваться!");
        location.href='index.php';
    </script>
    <?php
    exit();
}

// Обработка добавления конфигурации в корзину
if (isset($_GET['add_config'])) {
    // Добавляем все товары из конфигуратора в корзину
    if (!empty($_SESSION['configurator'])) {
        // Находим или создаем корзину
        $cart_query = mysqli_query($dbcnx, "SELECT idsale FROM sale WHERE iduser=$iduser AND kind='Корзина'");
        
        if (mysqli_num_rows($cart_query) > 0) {
            $cart = mysqli_fetch_array($cart_query);
            $idsale = $cart['idsale'];
        } else {
            // Создаем новую корзину
            date_default_timezone_set("Europe/Moscow");
            $Now = date("Y")."-".date("m")."-".date("d")." ".date("H").":".date("i").":".date("s");
            mysqli_query($dbcnx, "INSERT INTO sale (iduser, kind, datesale) VALUES ($iduser, 'Корзина', '$Now')");
            $idsale = mysqli_insert_id($dbcnx);
        }
        
        // Добавляем все товары из конфигурации в корзину
        foreach ($_SESSION['configurator'] as $item) {
            $idmerch = $item['idmerch'];
            
            // Проверяем, есть ли уже такой товар в корзине
            $check_query = mysqli_query($dbcnx, "SELECT * FROM detail WHERE idsale=$idsale AND idmerch=$idmerch");
            
            if (mysqli_num_rows($check_query) > 0) {
                // Увеличиваем количество
                mysqli_query($dbcnx, "UPDATE detail SET countmerch = countmerch + 1 WHERE idsale=$idsale AND idmerch=$idmerch");
            } else {
                // Добавляем новый товар
                mysqli_query($dbcnx, "INSERT INTO detail (idsale, idmerch, countmerch) VALUES ($idsale, $idmerch, 1)");
            }
        }
        
        // Очищаем конфигурацию
        unset($_SESSION['configurator']);
        
        // Перенаправляем обратно в корзину
        header("Location: sale2.php");
        exit();
    }
}

// Обработка оформления заказа
if ($step==3) {
    // Обновляем количество товаров
    $s = "SELECT iddetail FROM detail, sale WHERE detail.idsale=sale.idsale AND sale.iduser=$iduser AND kind='Корзина' ORDER BY iddetail";
    $r = mysqli_query($dbcnx,$s);
    
    for ($i=0;$i<mysqli_num_rows($r);$i++) {
        $f = mysqli_fetch_array($r);//считывание текующей записи		
        mysqli_query($dbcnx, "UPDATE detail SET countmerch=".$_REQUEST['ArrCount'][$i]." WHERE iddetail=".$f["iddetail"]);
    }
    
    // Меняем статус корзины на "Заказ"
    mysqli_query($dbcnx, "UPDATE sale SET kind='Заказ' WHERE iduser=$iduser AND kind='Корзина'");
    
    // Очищаем конфигуратор после оформления заказа
    unset($_SESSION['configurator']);
    
    ?>
    <meta charset="utf-8">
    <script language="javascript">
        alert("Ваш заказ принят! Наш менеджер в ближайшее время свяжется с Вами!");
        location.href='sale1.php?category=merch.idcategory';
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
                        <?php if ($Mode!=""): ?>
                            <li><a href="#"><i class="fa fa-user-o"></i> <?php echo htmlspecialchars($fio)." ($Mode)";?></a></li>
                        <?php endif; ?>                        
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
                        <!-- /SEARCH BAR -->

                        <?php if ($Mode=="Покупатель"): 
                            // Считаем товары в корзине
                            $r = mysqli_query($dbcnx, "SELECT detail.iddetail FROM detail, sale WHERE sale.idsale=detail.idsale and iduser=$iduser and kind='Корзина'");
                            $count = mysqli_num_rows($r);
                            
                            // Добавляем товары из конфигуратора (если они есть в сессии)
                            $config_count = count($_SESSION['configurator'] ?? []);
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
                        <?php endif; ?>  
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
                        <li><a href="sale1.php">Выбор товаров</a></li>     
                        <li class="active"><a href="sale2.php">Корзина</a></li>
                        <li><a href="conf.php?idcategory=1">Конфигуратор</a></li>
                        <li><a href="saleuser.php">Заказы</a></li>
                        <li><a href="index.php?step=2">Выход</a></li>                            
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
                    
                    <!-- Уведомление о товарах в конфигураторе -->
                    <?php if (!empty($_SESSION['configurator'])): ?>
                        <div class="alert alert-info" style="margin-bottom: 20px;">
                            <h4><i class="fa fa-info-circle"></i> У вас есть собранная конфигурация!</h4>
                            <p>Вы можете добавить все выбранные комплектующие в корзину одним кликом.</p>
                            <p>Итоговая стоимость конфигурации: 
                                <strong>
                                    <?php 
                                    $config_total = 0;
                                    foreach ($_SESSION['configurator'] as $item) {
                                        $config_total += $item['price'];
                                    }
                                    echo number_format($config_total, 0, ',', ' '); ?> ₽
                                </strong>
                            </p>
                            <a href="sale2.php?add_config=1" class="btn btn-success">
                                <i class="fa fa-cart-plus"></i> Добавить конфигурацию в корзину
                            </a>
                            <a href="conf.php?step=complete" class="btn btn-primary">
                                <i class="fa fa-eye"></i> Просмотреть конфигурацию
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <form name="form2" method="post">
                        <?php
                        // Забираем текущую дату
                        date_default_timezone_set("Europe/Moscow");
                        $Now = date("Y")."-".date("m")."-".date("d");   
                        
                        // Получаем информацию о покупателе
                        $s = "SELECT fio FROM user WHERE iduser=$iduser";
                        $r = mysqli_query($dbcnx,$s);
                        $f = mysqli_fetch_array($r);//считывание текующей записи	
                        ?>
                        
                        <div align="left">	
                            Дата продажи: <?php echo $Now;?> <br>
                            Покупатель: <?php echo htmlspecialchars($f["fio"]);?> <br><br>
                        </div>
                        
                        <!-- Обработка изменения количества товаров -->
                        <?php
                        if ($step==2) {
                            $s = "SELECT iddetail FROM detail, sale, merch, category WHERE merch.idmerch=detail.idmerch AND detail.idsale=sale.idsale AND merch.idcategory=category.idcategory AND sale.iduser=$iduser AND kind='Корзина' ORDER BY iddetail";
                            $r = mysqli_query($dbcnx,$s);
                            
                            for ($i=0;$i<mysqli_num_rows($r);$i++) {
                                $f = mysqli_fetch_array($r);//считывание текующей записи		
                                mysqli_query($dbcnx, "UPDATE detail SET countmerch=".$_REQUEST['ArrCount'][$i]." WHERE iddetail=".$f["iddetail"]);
                            }
                        }
                        
                        // Получаем товары из корзины
                        $s = "SELECT merch.idmerch as idmerch, merch, category, price, countmerch FROM detail, sale, merch, category WHERE merch.idmerch=detail.idmerch AND detail.idsale=sale.idsale AND merch.idcategory=category.idcategory AND sale.iduser=$iduser AND kind='Корзина' ORDER BY iddetail";
                        $r = mysqli_query($dbcnx,$s);
                        
                        $item_count = mysqli_num_rows($r);
                        ?>
                        
                        <table width="100%" border="1" cellspacing="0" cellpadding="3">
                            <tr>
                                <td><font color="white">&nbsp;</font></td>                                    
                                <td><h5>Товар</h5></td>
                                <td><h5>Категория</h5></td>        
                                <td><h5>Цена</h5></td>			
                                <td><h5>Количество</h5></td>
                            </tr>
                            
                            <?php
                            $sumsale = 0;
                            $has_items = false;
                            
                            if ($item_count > 0) {
                                $has_items = true;
                                for ($i=0; $i<$item_count; $i++) {
                                    $f = mysqli_fetch_array($r);//считывание текующей записи
                                    $item_total = $f["countmerch"] * $f["price"];
                                    $sumsale += $item_total;
                                    ?>
                                    <tr>
                                        <td>
                                            <?php if ($i==0): ?>
                                                <input type="radio" name="ArrMerch[]" value="<?php echo $f["idmerch"]; ?>" checked="checked">
                                            <?php else: ?>
                                                <input type="radio" name="ArrMerch[]" value="<?php echo $f["idmerch"]; ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="annotation.php?idmerch=<?php echo $f["idmerch"]; ?>">
                                                <?php echo htmlspecialchars($f["merch"]); ?>
                                            </a>
                                        </td>
                                        <td><?php echo htmlspecialchars($f["category"]); ?></td>
                                        <td><?php echo number_format($f["price"], 0, ',', ' '); ?> ₽</td>
                                        <td width="5%">
                                            <input type="text" name="ArrCount[]" value="<?php echo $f["countmerch"]; ?>" size="5" style="text-align: right;">
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 20px;">
                                        <div class="alert alert-warning">
                                            Ваша корзина пуста. Добавьте товары из <a href="sale1.php">каталога</a> или <a href="conf.php?idcategory=1">соберите конфигурацию</a>.
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            
                            <?php if ($has_items): ?>
                                <tr>
                                    <td colspan="4" align="right" style="font-weight: bold;">
                                        Общая сумма:
                                    </td>
                                    <td align="left" style="font-weight: bold;">
                                        <?php echo number_format($sumsale, 0, ',', ' '); ?> ₽
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>
                        
                        <br>
                        
                        <div class="btn-group">
                            <input type="button" name="button" 
                                   onclick="qwest=window.confirm('Подтверждаете заказ?'); if (qwest) {this.form.action='sale2.php?step=3'; this.form.submit();}" 
                                   value="Заказать" 
                                   <?php if (!$has_items) echo 'disabled="disabled"'; ?>>
                            
                            <input type="button" name="button4" 
                                   onclick="this.form.action='sale2.php?step=2'; this.form.submit();" 
                                   value="Сохранить" 
                                   <?php if (!$has_items) echo 'disabled="disabled"'; ?>>
                            
                            <input type="button" name="button" 
                                   onclick="qwest=window.confirm('Вы действительно хотите очистить корзину?'); if (qwest) {this.form.action='clearsale.php'; this.form.submit();}" 
                                   value="Очистить" 
                                   <?php if (!$has_items) echo 'disabled="disabled"'; ?>>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
                                Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- jQuery Plugins -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/slick.min.js"></script>
        <script src="js/nouislider.min.js"></script>
        <script src="js/jquery.zoom.min.js"></script>
        <script src="js/main.js"></script>

    </body>
</html>