<?php
/*
 * Точка входа для выполнения тестов. Для того, чтобы страница отображалась,
 * нужно отключить ЧПУ в настройках приложения, иначе все запросы будет обрабатывать
 * index.php.
 * */

// Убедитесь что этот файл недоступен, когда выгружен на продакшен
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('У вас отсутствуют права для просмотра этого файла.');
}

// Включаем режим отладки
defined('YII_DEBUG') or define('YII_DEBUG', true);
// Устанавливаем режим приложения в test
defined('YII_ENV') or define('YII_ENV', 'test');

// Подключаем нужные классы
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

// Подключаем конфиг для тестов
$config = require(__DIR__ . '/../config/test.php');

// Запускаем приложение
(new yii\web\Application($config))->run();