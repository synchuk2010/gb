<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'gb',
    'name' => 'Гостевая книга',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    /*
     * Конфигурация языка, которая переводит некоторые стандартные выводы в системе
     * (например главную страницу в хлебных крошках, тексты ошибок и т.д.).
     * Также задаёт свой (не особо приемлемый для приложения) формат даты.
     * Это переопределяется ниже.
     * */
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    // Маршрут по-умолчанию
    'defaultRoute' => 'main',
    // Настройка компонентов
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ib-Uf7SJQ-grTw_r0hBIPCA3562rCWS2',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['main/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'main/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'htmlLayout' => 'layouts/html',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        /*
         * Настройка urlManager для отображения ЧПУ.
         * Если вы хотите чтобы приложение отображалось по вызову articles,
         * а не articles/web, вам нужно настроить виртуальные хосты на вашем веб-сервере
         * */
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        /*
         * Настройка форматирования. Задаём формат передаваемой даты, чтобы работали
         * фильтры даты GridView в панели администрирования.
         * */
        'formatter' => [
            'dateFormat' => 'yyyy-MM-dd',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
