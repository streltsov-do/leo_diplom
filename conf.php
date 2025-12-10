<?php
/*
1.Подключение настроек БД
2.HTML-шаблон
3.Хедер с информацией о пользователе
4.Навигационное меню
5.Обработка step и idcategory
6.Создание корзины
7.Добавление товара и переход к следующей категории
8.Проверка окончания конфигурации
9.Форма с товарами и фильтрацией
10.Таблица с товарами
11.Кнопки управления
12.Футер
13.Cкрипты
*/

session_start();
require "option.php";//файл с параметрами подключения к БД

$iduser = $_COOKIE['iduser'] ?? '';
$fio = $_COOKIE['fio'] ?? '';
$Mode = $_COOKIE['Mode'] ?? '';

$step = $_GET["step"] ?? '';
$idcategory = $_GET["idcategory"] ?? 1;

// Инициализация сессии для хранения конфигурации
if (!isset($_SESSION['configurator'])) {
    $_SESSION['configurator'] = [];
}

// Обработка шагов
if ($step == 'add') {
    // Добавление товара в конфигуратор из формы
    $idmerch = $_POST['idmerch'] ?? 0;
    if ($idmerch > 0) {
        // Получаем информацию о товаре
        $query = mysqli_query($dbcnx, "SELECT * FROM merch WHERE idmerch=$idmerch");
        if (mysqli_num_rows($query) > 0) {
            $merch = mysqli_fetch_array($query);
            $_SESSION['configurator'][$idmerch] = [
                'idmerch' => $idmerch,
                'name' => $merch['merch'],
                'price' => $merch['price'],
                'idcategory' => $merch['idcategory'],
                'category_name' => ''
            ];
            
            // Получаем название категории
            $cat_query = mysqli_query($dbcnx, "SELECT category FROM category WHERE idcategory=" . $merch['idcategory']);
            if (mysqli_num_rows($cat_query) > 0) {
                $cat = mysqli_fetch_array($cat_query);
                $_SESSION['configurator'][$idmerch]['category_name'] = $cat['category'];
            }
        }
    }
    
    // Переходим к следующей категории
    $next_category = $idcategory + 1;
    $check_next = mysqli_query($dbcnx, "SELECT * FROM category WHERE idcategory=$next_category");
    if (mysqli_num_rows($check_next) > 0) {
        header("Location: conf.php?idcategory=$next_category");
        exit();
    } else {
        // Если это последняя категория, переходим к завершению
        header("Location: conf.php?step=complete");
        exit();
    }
}

if ($step == 'skip') {
    // Пропуск текущей категории
    // Добавляем специальную запись о пропущенной категории
    $cat_query = mysqli_query($dbcnx, "SELECT * FROM category WHERE idcategory=$idcategory");
    if (mysqli_num_rows($cat_query) > 0) {
        $category = mysqli_fetch_array($cat_query);
        $skip_id = 'skip_' . $idcategory; // Уникальный ID для пропущенной категории
        
        $_SESSION['configurator'][$skip_id] = [
            'idmerch' => 0, // 0 означает пропущенный товар
            'name' => '[Пропущено] ' . $category['category'],
            'price' => 0,
            'idcategory' => $idcategory,
            'category_name' => $category['category'],
            'skipped' => true
        ];
    }
    
    // Переходим к следующей категории
    $next_category = $idcategory + 1;
    $check_next = mysqli_query($dbcnx, "SELECT * FROM category WHERE idcategory=$next_category");
    if (mysqli_num_rows($check_next) > 0) {
        header("Location: conf.php?idcategory=$next_category");
        exit();
    } else {
        // Если это последняя категория, переходим к завершению
        header("Location: conf.php?step=complete");
        exit();
    }
}

if ($step == 'complete') {
    // Отображение итоговой конфигурации
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Итоговая конфигурация</title>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>
        <link type="text/css" rel="stylesheet" href="css/slick.css"/>
        <link type="text/css" rel="stylesheet" href="css/slick-theme.css"/>
        <link type="text/css" rel="stylesheet" href="css/nouislider.min.css"/>
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link type="text/css" rel="stylesheet" href="css/style.css"/>
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
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="header-logo">
                                <a href="#" class="logo">
                                    <img src="./img/logo.png" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="header-search">
                                <form>
                                    <input class="input" placeholder="Поиск">
                                    <button class="search-btn">Искать</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-3 clearfix">
                            <div class="header-ctn">
                                <?php if ($Mode=="Покупатель"): 
                                    // Считаем товары в корзине + товары в конфигураторе
                                    $r=mysqli_query($dbcnx,"select detail.iddetail FROM detail, sale  WHERE sale.idsale=detail.idsale and iduser=$iduser and kind='Корзина'");
                                    $count=mysqli_num_rows($r);
                                    $config_count = count($_SESSION['configurator']);
                                    $total_count = $count + $config_count;
                                ?>
                                <div>
                                    <a href="sale2.php">
                                        <i class="fa fa-shopping-cart"></i>
                                        <span>Корзина</span>
                                        <div class="qty"><?php echo $total_count;?></div>
                                    </a>
                                </div>
                                <?php endif; ?>
                                <div class="menu-toggle">
                                    <a href="#">
                                        <i class="fa fa-bars"></i>
                                        <span>Menu</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- NAVIGATION -->
        <nav id="navigation">
            <div class="container">
                <div id="responsive-nav">
                    <ul class="main-nav nav navbar-nav">
                        <li><a href="index.php">На главную</a></li>                                              
                        <li><a href="sale1.php">Выбор товаров</a></li>     
                        <li><a href="sale2.php">Корзина</a></li>
                        <li class="active"><a href="conf.php?idcategory=1">Конфигуратор</a></li>
                        <li><a href="saleuser.php">Заказы</a></li>
                        <li><a href="index.php?step=2">Выход</a></li>                            
                    </ul>
                </div>
            </div>
        </nav>

        <!-- SECTION -->
        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="breadcrumb-header">Итоговая конфигурация</h3>
                        
                        <?php if (empty($_SESSION['configurator'])): ?>
                            <div class="alert alert-warning">
                                <p>Вы не выбрали ни одного комплектующего!</p>
                                <a href="conf.php?idcategory=1" class="btn btn-primary">Начать сборку заново</a>
                            </div>
                        <?php else: ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Товар</th>
                                        <th>Категория</th>
                                        <th>Цена</th>
                                        <th>Статус</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_price = 0;
                                    $selected_items = 0;
                                    $skipped_items = 0;
                                    $i = 1;
                                    
                                    foreach ($_SESSION['configurator'] as $item): 
                                        $is_skipped = isset($item['skipped']) && $item['skipped'];
                                        $price = $is_skipped ? 0 : $item['price'];
                                        $total_price += $price;
                                        
                                        if ($is_skipped) {
                                            $skipped_items++;
                                        } else {
                                            $selected_items++;
                                        }
                                    ?>
                                    <tr <?php echo $is_skipped ? 'style="background-color: #f8f9fa;"' : ''; ?>>
                                        <td><?php echo $i++; ?></td>
                                        <td>
                                            <?php if ($is_skipped): ?>
                                                <span style="color: #6c757d;">
                                                    <i class="fa fa-times-circle"></i> <?php echo htmlspecialchars($item['name']); ?>
                                                </span>
                                            <?php else: ?>
                                                <?php echo htmlspecialchars($item['name']); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($item['category_name']); ?></td>
                                        <td>
                                            <?php if ($is_skipped): ?>
                                                <span style="color: #6c757d;">0 ₽</span>
                                            <?php else: ?>
                                                <?php echo number_format($item['price'], 0, ',', ' '); ?> ₽
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($is_skipped): ?>
                                                <span class="label label-default">Пропущено</span>
                                            <?php else: ?>
                                                <span class="label label-success">Выбрано</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Итого:</th>
                                        <th colspan="2"><?php echo number_format($total_price, 0, ',', ' '); ?> ₽</th>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="background-color: #f8f9fa;">
                                            <small>
                                                Выбрано товаров: <strong><?php echo $selected_items; ?></strong> | 
                                                Пропущено категорий: <strong><?php echo $skipped_items; ?></strong>
                                            </small>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <form method="post" action="conf.php?step=save">
                                        <div class="form-group">
                                            <label for="config_name">Название конфигурации:</label>
                                            <input type="text" class="form-control" id="config_name" name="config_name" 
                                                   value="Моя сборка от <?php echo date('d.m.Y'); ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fa fa-shopping-cart"></i> Добавить выбранные товары в корзину
                                        </button>
                                        <a href="conf.php?idcategory=1" class="btn btn-primary">
                                            <i class="fa fa-edit"></i> Изменить сборку
                                        </a>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <footer id="footer">
            <div id="bottom-footer" class="section">
                <div class="container">
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

        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/main.js"></script>
    </body>
    </html>
    <?php
    exit();
}

if ($step == 'save') {
    // Сохранение конфигурации в корзину (только выбранные товары, не пропущенные)
    if (!empty($_SESSION['configurator']) && $iduser > 0) {
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
        
        // Добавляем только выбранные товары из конфигурации в корзину (не пропущенные)
        $added_count = 0;
        foreach ($_SESSION['configurator'] as $item) {
            // Пропускаем товары с флагом 'skipped'
            if (isset($item['skipped']) && $item['skipped']) {
                continue;
            }
            
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
            $added_count++;
        }
        
        // Очищаем конфигурацию
        unset($_SESSION['configurator']);
        
        // Показываем сообщение об успехе
        ?>
        <meta charset="utf-8">
        <script language="javascript">
            alert("Конфигурация сохранена! <?php echo $added_count; ?> товаров добавлено в корзину.");
            location.href='sale2.php';
        </script>
        <?php
        exit();
    }
}

if ($step == 2) {
    // Отмена конфигурации
    unset($_SESSION['configurator']);
    mysqli_query($dbcnx, "DELETE FROM sale WHERE iduser=$iduser AND kind='Корзина'");
    ?>
    <meta charset="utf-8">
    <script language="javascript">
        alert("Конфигурация отменена!");
        location.href='sale1.php';
    </script>
    <?php
    exit();
}

// Основная логика конфигуратора
if ($idcategory == 1) {
    // Начинаем новую конфигурацию - очищаем сессию
    unset($_SESSION['configurator']);
    
    // Создаем корзину для конфигуратора (если еще нет)
    $check_cart = mysqli_query($dbcnx, "SELECT idsale FROM sale WHERE iduser=$iduser AND kind='Корзина'");
    if (mysqli_num_rows($check_cart) == 0) {
        date_default_timezone_set("Europe/Moscow");
        $Now = date("Y")."-".date("m")."-".date("d")." ".date("H").":".date("i").":".date("s");
        mysqli_query($dbcnx, "INSERT INTO sale (iduser, kind, datesale) VALUES ($iduser, 'Корзина', '$Now')");
    }
}

// Обработка выбора товара (старая логика для совместимости)
if ($step == 1) {
    $Arr = $_POST['ArrMerch'] ?? [];
    if (!empty($Arr)) {
        $idmerch = $Arr[0];
        
        // Добавляем в сессию конфигуратора
        $query = mysqli_query($dbcnx, "SELECT * FROM merch WHERE idmerch=$idmerch");
        if (mysqli_num_rows($query) > 0) {
            $merch = mysqli_fetch_array($query);
            
            // Получаем название категории
            $cat_query = mysqli_query($dbcnx, "SELECT category FROM category WHERE idcategory=" . $merch['idcategory']);
            $category_name = '';
            if (mysqli_num_rows($cat_query) > 0) {
                $cat = mysqli_fetch_array($cat_query);
                $category_name = $cat['category'];
            }
            
            $_SESSION['configurator'][$idmerch] = [
                'idmerch' => $idmerch,
                'name' => $merch['merch'],
                'price' => $merch['price'],
                'idcategory' => $merch['idcategory'],
                'category_name' => $category_name
            ];
        }
        
        // Переходим к следующей категории
        $idcategory = $idcategory + 1;
    }
}

// Проверяем, есть ли следующая категория
$category_query = mysqli_query($dbcnx, "SELECT category FROM category WHERE idcategory=$idcategory");
if (mysqli_num_rows($category_query) == 0) {
    // Нет следующей категории - переходим к завершению
    header("Location: conf.php?step=complete");
    exit();
}

$category = mysqli_fetch_array($category_query);

// Получаем ID корзины для ограничений по совместимости
$cart_query = mysqli_query($dbcnx, "SELECT idsale FROM sale WHERE iduser=$iduser AND kind='Корзина'");
$idsale = 0;
if (mysqli_num_rows($cart_query) > 0) {
    $cart = mysqli_fetch_array($cart_query);
    $idsale = $cart['idsale'];
}

// Получаем товары текущей категории
$filter = $_GET["filter"] ?? '';
$sort = $_GET["sort"] ?? '';
$value1 = $_POST['FilterValue1'] ?? '';
$fieldsort = $_POST['sortname'] ?? 'avgmark DESC';

$s = "SELECT m.idmerch, m.merch, m.price, m.annotation, m.file, 
             COALESCE((SELECT ROUND(AVG(mark), 2) FROM response WHERE response.idmerch = m.idmerch), 0) as avgmark  
      FROM merch m 
      WHERE m.idcategory = $idcategory";

if ($filter == 1 && !empty($value1)) {
    $s .= " AND UPPER(m.merch) LIKE UPPER('%$value1%')";
}

// Ограничение по совместимости для категорий после первой (только для выбранных товаров, не пропущенных)
if ($idcategory > 1 && $idsale > 0 && !empty($_SESSION['configurator'])) {
    // Получаем ID выбранных товаров (не пропущенных)
    $selected_ids = [];
    foreach ($_SESSION['configurator'] as $key => $item) {
        if (!isset($item['skipped']) || !$item['skipped']) {
            $selected_ids[] = $item['idmerch'];
        }
    }
    
    if (!empty($selected_ids)) {
        $s .= " AND m.idmerch IN (
            SELECT mc.idmerch 
            FROM merchconnector mc 
            WHERE mc.idconnector IN (
                SELECT mc2.idconnector 
                FROM merchconnector mc2 
                WHERE mc2.idmerch IN (" . implode(',', $selected_ids) . ")
            )
        )";
    }
}

if ($sort == 1) {
    $s .= " ORDER BY $fieldsort";
} else {
    $s .= " ORDER BY avgmark DESC";
}

$r = mysqli_query($dbcnx, $s);
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Конфигуратор - <?php echo htmlspecialchars($category['category']); ?></title>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>
        <link type="text/css" rel="stylesheet" href="css/slick.css"/>
        <link type="text/css" rel="stylesheet" href="css/slick-theme.css"/>
        <link type="text/css" rel="stylesheet" href="css/nouislider.min.css"/>
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link type="text/css" rel="stylesheet" href="css/style.css"/>
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
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="header-logo">
                                <a href="#" class="logo">
                                    <img src="./img/logo.png" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="header-search">
                                <form>
                                    <input class="input" placeholder="Поиск">
                                    <button class="search-btn">Искать</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-3 clearfix">
                            <div class="header-ctn">
                                <?php if ($Mode=="Покупатель"): 
                                    // Считаем товары в корзине + товары в конфигураторе
                                    $r2=mysqli_query($dbcnx,"select detail.iddetail FROM detail, sale  WHERE sale.idsale=detail.idsale and iduser=$iduser and kind='Корзина'");
                                    $count=mysqli_num_rows($r2);
                                    $config_count = count($_SESSION['configurator']);
                                    $total_count = $count + $config_count;
                                ?>
                                <div>
                                    <a href="sale2.php">
                                        <i class="fa fa-shopping-cart"></i>
                                        <span>Корзина</span>
                                        <div class="qty"><?php echo $total_count;?></div>
                                    </a>
                                </div>
                                <?php endif; ?>
                                <div class="menu-toggle">
                                    <a href="#">
                                        <i class="fa fa-bars"></i>
                                        <span>Menu</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- NAVIGATION -->
        <nav id="navigation">
            <div class="container">
                <div id="responsive-nav">
                    <ul class="main-nav nav navbar-nav">
                        <li><a href="index.php">На главную</a></li>                                              
                        <li><a href="sale1.php">Выбор товаров</a></li>     
                        <li><a href="sale2.php">Корзина</a></li>
                        <li class="active"><a href="conf.php?idcategory=1">Конфигуратор</a></li>
                        <li><a href="saleuser.php">Заказы</a></li>
                        <li><a href="index.php?step=2">Выход</a></li>                            
                    </ul>
                </div>
            </div>
        </nav>

        <!-- SECTION -->
        <div class="section">
            <div class="container">
                <div class="row">
                    <h3 class="breadcrumb-header">Выберите комплектующие из категории "<?php echo htmlspecialchars($category['category']); ?>"</h3>
                    
                    <!-- Информация о текущей конфигурации -->
                    <?php if (!empty($_SESSION['configurator'])): ?>
                        <div class="panel panel-default" style="margin-bottom: 20px;">
                            <div class="panel-heading">
                                <h4 class="panel-title">Текущая конфигурация</h4>
                            </div>
                            <div class="panel-body">
                                <?php 
                                $current_total = 0;
                                $selected_count = 0;
                                $skipped_count = 0;
                                
                                foreach ($_SESSION['configurator'] as $item) {
                                    if (isset($item['skipped']) && $item['skipped']) {
                                        $skipped_count++;
                                    } else {
                                        $selected_count++;
                                        $current_total += $item['price'];
                                    }
                                }
                                ?>
                                <p>
                                    Выбрано товаров: <strong><?php echo $selected_count; ?></strong><br>
                                    Пропущено категорий: <strong><?php echo $skipped_count; ?></strong><br>
                                    Сумма выбранных товаров: <strong><?php echo number_format($current_total, 0, ',', ' '); ?> ₽</strong>
                                </p>
                                <a href="conf.php?step=complete" class="btn btn-success btn-sm">
                                    <i class="fa fa-check"></i> Завершить сборку
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <form name="form2" method="post">
                        <div align="right">	
                            Сортировка:
                            <select name="sortname" style="height:22; width:auto" 
                                    onChange="this.form.action='conf.php?sort=1&idcategory=<?php echo $idcategory; ?>&step=0&filter=<?php echo $filter; ?>'; this.form.submit();">
                                <option value="avgmark DESC" <?php echo ($fieldsort=="avgmark DESC") ? 'selected="selected"' : ''; ?>>Рейтинг</option>	
                                <option value="price" <?php echo ($fieldsort=="price") ? 'selected="selected"' : ''; ?>>Цена</option>	
                                <option value="merch" <?php echo ($fieldsort=="merch") ? 'selected="selected"' : ''; ?>>Товар</option>						
                            </select>
                            
                            Название: 
                            <input name="FilterValue1" onFocus="if (this.value=='Все') this.value=''" 
                                   value="<?php echo ($filter==1 && !empty($value1)) ? htmlspecialchars($value1) : "Все"; ?>" 
                                   type="text">

                            <br>
                            <input type="button" name="button1" 
                                   onclick="this.form.action='conf.php?filter=1&step=0&idcategory=<?php echo $idcategory; ?>&sort=<?php echo $sort; ?>'; this.form.submit();" 
                                   value="Фильтр">
                            <input type="button" name="button2" 
                                   onclick="this.form.action='conf.php?filter=0&step=0&idcategory=<?php echo $idcategory; ?>&sort=0'; this.form.submit();" 
                                   value="Очистить">
                            <br>            
                        </div>
                        
                        <!-- Новые кнопки для навигации с возможностью пропуска -->
                        <div style="margin: 10px 0;">
                            <input type="button" name="button_next" 
                                   onclick="this.form.action='conf.php?step=1&idcategory=<?php echo $idcategory; ?>'; this.form.submit();" 
                                   value="Выбрать товар и продолжить" 
                                   class="btn btn-primary">
                            
                            <input type="button" name="button_skip" 
                                   onclick="this.form.action='conf.php?step=skip&idcategory=<?php echo $idcategory; ?>'; this.form.submit();" 
                                   value="Пропустить эту категорию" 
                                   class="btn btn-default"
                                   title="Не выбирать товар из этой категории">
                            
                            <input type="button" name="button_cancel" 
                                   onclick="qwest=window.confirm('Вы действительно хотите отменить конфигурацию?'); if (qwest) {this.form.action='conf.php?step=2'; this.form.submit();}" 
                                   value="Отмена" 
                                   class="btn btn-danger">
                        </div>
                        
                        <table width="100%" border="1" cellspacing="0" cellpadding="3" style="margin-top: 20px;">
                            <tr>
                                <td><font color="white">&nbsp;</font></td>
                                <td><h5>Товар</h5></td>
                                <td><h5>Рейтинг</h5></td>
                                <td><h5>Цена</h5></td>
                                <td><h5>Описание</h5></td>
                                <td><h5>Фото</h5></td>
                            </tr>
                            
                            <?php if (mysqli_num_rows($r) > 0): ?>
                                <?php 
                                $i = 0;
                                while ($f = mysqli_fetch_array($r)): 
                                    $i++;
                                ?>
                                <tr>
                                    <td>
                                        <?php if ($i == 1): ?>
                                            <input type="radio" name="ArrMerch[]" value="<?php echo $f['idmerch']; ?>" checked="checked">
                                        <?php else: ?>
                                            <input type="radio" name="ArrMerch[]" value="<?php echo $f['idmerch']; ?>">
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($f['merch']); ?></td>
                                    <td><?php echo number_format($f['avgmark'], 2); ?></td>
                                    <td><?php echo number_format($f['price'], 0, ',', ' '); ?> ₽</td>
                                    <td><?php echo htmlspecialchars($f['annotation']); ?></td>
                                    <td>
                                        <?php if (!empty($f['file'])): ?>
                                            <a href="upload/<?php echo htmlspecialchars($f['file']); ?>" target="_blank">Фото</a>
                                        <?php else: ?>
                                            Нет фото
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 20px;">
                                        <div class="alert alert-info">
                                            <?php if ($idcategory > 1 && !empty($_SESSION['configurator'])): ?>
                                                Нет совместимых товаров в этой категории для выбранной конфигурации.<br>
                                                Вы можете пропустить эту категорию или изменить ранее выбранные комплектующие.
                                            <?php else: ?>
                                                В этой категории нет товаров.
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>
                        
                        <!-- Дублируем кнопки навигации внизу таблицы -->
                        <div style="margin: 20px 0; text-align: center;">
                            <input type="button" name="button_next_bottom" 
                                   onclick="this.form.action='conf.php?step=1&idcategory=<?php echo $idcategory; ?>'; this.form.submit();" 
                                   value="Выбрать товар и продолжить" 
                                   class="btn btn-primary">
                            
                            <input type="button" name="button_skip_bottom" 
                                   onclick="this.form.action='conf.php?step=skip&idcategory=<?php echo $idcategory; ?>'; this.form.submit();" 
                                   value="Пропустить эту категорию" 
                                   class="btn btn-default"
                                   title="Не выбирать товар из этой категории">
                            
                            <input type="button" name="button_cancel_bottom" 
                                   onclick="qwest=window.confirm('Вы действительно хотите отменить конфигурацию?'); if (qwest) {this.form.action='conf.php?step=2'; this.form.submit();}" 
                                   value="Отмена" 
                                   class="btn btn-danger">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <footer id="footer">
            <div id="bottom-footer" class="section">
                <div class="container">
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

        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>