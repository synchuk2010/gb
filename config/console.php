<?php
/*
 * Файл конфигурации консольного приложения
 * */

// Дополнительная конфигурация
$params = require(__DIR__ . '/params.php');
// Конфигурация БД
$db = require(__DIR__ . '/db.php');

// Основная конфигурация приложения
$config = [
    // Идентификатор приложения
    'id' => 'gb-console',
    // Базовый путь
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    // Пространство имён для контроллеров консольного приложения
    'controllerNamespace' => 'app\commands',
    // Настройка компонентов
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        // Логирование
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        // БД
        'db' => $db,
    ],
    // Дополнительные параметры
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

// Конфигурация для разработки приложения
if (YII_ENV_DEV) {
    // Подключаем Gii
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

// Возвращаем конфигурацию приложения
return $config;