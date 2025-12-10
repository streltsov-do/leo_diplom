<?php
// Включаем вывод всех ошибок для диагностики
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h3>Тест подключения к MySQL OpenServer</h3>";
echo "Время: " . date('H:i:s') . "<br>";
echo "----------------------------------------<br><br>";

// Варианты настроек для тестирования
$test_configs = [
    ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'port' => 3306, 'db' => 'comp'],
    ['host' => 'localhost', 'user' => 'root', 'pass' => 'root', 'port' => 3306, 'db' => 'comp'],
    ['host' => 'localhost', 'user' => 'root', 'pass' => '123456', 'port' => 3306, 'db' => 'comp'],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => '', 'port' => 3306, 'db' => 'comp'],
    ['host' => 'localhost:3307', 'user' => 'root', 'pass' => '', 'port' => 3307, 'db' => 'comp'],
    ['host' => 'localhost:3307', 'user' => 'root', 'pass' => 'root', 'port' => 3307, 'db' => 'comp'],
];

foreach ($test_configs as $config) {
    echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
    echo "<strong>Проверяем конфигурацию:</strong><br>";
    echo "Хост: " . $config['host'] . "<br>";
    echo "Пользователь: " . $config['user'] . "<br>";
    echo "Пароль: " . (empty($config['pass']) ? '(пустой)' : '***') . "<br>";
    echo "База данных: " . $config['db'] . "<br>";
    
    // Пытаемся подключиться
    $conn = @mysqli_connect($config['host'], $config['user'], $config['pass']);
    
    if ($conn) {
        echo "<span style='color: green;'>✓ Успешное подключение к MySQL серверу</span><br>";
        
        // Пробуем выбрать базу данных
        if (@mysqli_select_db($conn, $config['db'])) {
            echo "<span style='color: green;'>✓ База данных '{$config['db']}' доступна</span><br>";
            
            // Показываем таблицы
            $result = mysqli_query($conn, "SHOW TABLES");
            $table_count = mysqli_num_rows($result);
            echo "Количество таблиц: " . $table_count . "<br>";
            
            if ($table_count > 0) {
                echo "Список таблиц:<br>";
                echo "<ul>";
                while ($row = mysqli_fetch_array($result)) {
                    echo "<li>" . $row[0] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<span style='color: orange;'>База данных пуста (нет таблиц)</span><br>";
            }
        } else {
            echo "<span style='color: orange;'>База данных '{$config['db']}' не существует</span><br>";
            echo "Доступные базы данных:<br>";
            $result = mysqli_query($conn, "SHOW DATABASES");
            echo "<ul>";
            while ($row = mysqli_fetch_array($result)) {
                echo "<li>" . $row[0] . "</li>";
            }
            echo "</ul>";
        }
        
        mysqli_close($conn);
    } else {
        echo "<span style='color: red;'>✗ Ошибка подключения</span><br>";
        echo "Код ошибки: " . mysqli_connect_errno() . "<br>";
        echo "Текст ошибки: " . mysqli_connect_error() . "<br>";
        
        // Проверяем доступность порта
        $host_parts = explode(':', $config['host']);
        $hostname = $host_parts[0];
        $port = isset($host_parts[1]) ? $host_parts[1] : $config['port'];
        
        $timeout = 2;
        $fp = @fsockopen($hostname, $port, $errno, $errstr, $timeout);
        if ($fp) {
            echo "<span style='color: green;'>Порт $port доступен</span><br>";
            fclose($fp);
        } else {
            echo "<span style='color: red;'>Порт $port НЕ доступен: $errstr ($errno)</span><br>";
        }
    }
    
    echo "</div>";
}

echo "<br><hr><br>";
echo "<h4>Проверка текущих настроек OpenServer:</h4>";

// Проверяем конфигурацию OpenServer
echo "Путь к OpenServer: " . (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : 'не определен') . "<br>";

// Проверяем файл option.php
if (file_exists('option.php')) {
    echo "Файл option.php существует<br>";
    $option_content = file_get_contents('option.php');
    
    // Ищем параметры подключения
    if (preg_match('/\$dblocation\s*=\s*["\']([^"\']+)["\']/', $option_content, $matches)) {
        echo "Хост в option.php: " . $matches[1] . "<br>";
    }
    if (preg_match('/\$dbuser\s*=\s*["\']([^"\']+)["\']/', $option_content, $matches)) {
        echo "Пользователь в option.php: " . $matches[1] . "<br>";
    }
    if (preg_match('/\$dbpasswd\s*=\s*["\']([^"\']+)["\']/', $option_content, $matches)) {
        echo "Пароль в option.php: " . (empty($matches[1]) ? '(пустой)' : 'установлен') . "<br>";
    }
    if (preg_match('/\$dbname\s*=\s*["\']([^"\']+)["\']/', $option_content, $matches)) {
        echo "База данных в option.php: " . $matches[1] . "<br>";
    }
} else {
    echo "<span style='color: red;'>Файл option.php не найден!</span><br>";
}

echo "<br><hr><br>";
echo "<h4>Информация о PHP и MySQL:</h4>";
echo "Версия PHP: " . phpversion() . "<br>";

// Проверяем расширение mysqli
if (extension_loaded('mysqli')) {
    echo "Расширение mysqli: загружено ✓<br>";
} else {
    echo "<span style='color: red;'>Расширение mysqli: НЕ загружено</span><br>";
}

// Показываем текущие настройки MySQL из php.ini
echo "mysql.default_host: " . ini_get('mysql.default_host') . "<br>";
echo "mysql.default_user: " . ini_get('mysql.default_user') . "<br>";
echo "mysqli.default_host: " . ini_get('mysqli.default_host') . "<br>";
echo "mysqli.default_port: " . ini_get('mysqli.default_port') . "<br>";

?>