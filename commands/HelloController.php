<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;

/**
 * Команда выводит первый введённый вами аргумент.
 *
 * Данная команда приводит пример работы консольного приложения.
 * С помощью консольных приложений можно проводить обслуживание приложения.
 * Создавайте собственные действия контроллера и вызывайте их через
 * yii имя_контроллера/имя_действия в консоли вашей ОС, предварительно перейдя
 * в директорию с приложением. Консольные приложения подключены к той же БД
 * (если вы не переопределили это в файле настроек console.php) и очень удобны для
 * выполнения рядовых операций с приложениями (добавление пользователей, выгрузка логов,
 * настройка приложения), что позволяет создавать задания по расписанию в планировщиках.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * Выводит введённое вами сообщение (либо hello world, если вы ничего не указали)
     * @param string $message сообщение, которое будет отображено.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }
}
