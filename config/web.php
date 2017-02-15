<?php
/*
 * Файл конфигурации вашего веб-приложения на Yii
 * */

// Добавляем дополнительную конфигурацию
$params = require(__DIR__ . '/params.php');

// Основная конфигурация приложения
$config = [
    // Идентификатор приложения
    'id' => 'gb',
    'name' => 'Гостевая книга',
    // Базовый путь
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
        // Класс, представляющий пользователя
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['main/login'],
        ],
        // Обработчик ошибок
        'errorHandler' => [
            'errorAction' => 'main/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            /*
             * Класс отправки почты. Настроен таким образом,
             * чтобы письма писались в файлы, если приложение в режиме разработки.
             * Если вы хотите изменить это поведение, установите useFileTransport в true,
             * чтобы письма всегда писались в файлы (и, соответственно, false, если наоборот).
             * */
            'useFileTransport' => YII_DEBUG ? true : false,
            // Шаблон для html-сообщений (находится в папке mail)
            'htmlLayout' => 'layouts/html',
        ],
        // Логирование
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        // Конфигурация БД
        'db' => require(__DIR__ . '/db.php'),
        /*
         * Настройка urlManager для отображения ЧПУ.
         * Если вы хотите чтобы приложение отображалось по вызову gb,
         * а не gb/web, вам нужно настроить виртуальные хосты на вашем веб-сервере
         * */
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
    'params' => $params,
];

// Конфигурация для разработки приложения
if (YII_ENV_DEV) {
    // Подключаем модуль отладки
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
    // Подключаем Gii
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}
// Устанавливаем конфигурацию приложения
return $config;