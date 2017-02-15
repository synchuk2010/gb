<?php
/*
 * Отображает страницу ошибки
 * */

/* @var $this yii\web\View объект текущего представления*/
/* @var $name string название исключения */
/* @var $message string сообщение с ошибкой */
/* @var $exception Exception объект исключения */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="main-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        Во время обработки вашего запроса, произошла следующая ошибка.
    </p>
    <p>
        Пожалуйста, свяжитесь с нами, если вы считаете, что это ошибка сервера.
    </p>

</div>
