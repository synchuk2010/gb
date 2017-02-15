<?php
/*
 * Шаблон для письма в формате html
 * */

use yii\helpers\Html;

/* @var $this \yii\web\View объект текущего представления */
/* @var $message \yii\mail\MessageInterface объект сообщения */
/* @var $content string результат отображения вида */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <hr/>
    <p>
        Это письмо было сгенерировано автоматически. Пожалуйста, не отвечайте на него.
        С наилучшими пожеланиями, команда разработки приложения <?= Yii::$app->name ?>
    </p>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
