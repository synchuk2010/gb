<?php
// Дополнительная конфигурация
$params = require(__DIR__ . '/params.php');
// Конфигурация БД для выполнения тестов
$dbParams = require(__DIR__ . '/test_db.php');

/**
 * Конфигурация приложения для разработки тестов (используется всеми тестовыми типами)
 */
return [
    // Идентификатор приложения
    'id' => 'gb-tests',
    // Базовый путь
    'basePath' => dirname(__DIR__),
    // Язык
    'language' => 'ru-RU',
    // Настройка компонентов
    'components' => [
        'db' => $dbParams,
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'user' => [
            'identityClass' => 'app\models\User',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            // Если вам необходимо однозначно указать домен как localhost, раскомментируйте
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
    ],
    // Дополнительные параметры
    'params' => $params,
];