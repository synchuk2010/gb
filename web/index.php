<?php
/*
 * Входная точка для запуска веб-приложения Yii2
 * */

// Если вы хотите перевести приложение в режим разработки, строки ниже должны быть раскомментированы
// defined('YII_DEBUG') or define('YII_DEBUG', true);
// defined('YII_ENV') or define('YII_ENV', 'dev');

// Подключаем автозагрузчик классов
require(__DIR__ . '/../vendor/autoload.php');
// Подключаем Yii
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
// Подключаем конфигурацию
$config = require(__DIR__ . '/../config/web.php');

// Запускаем приложение
(new yii\web\Application($config))->run();