<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\bootstrap\Alert;

AppAsset::register($this);
$this->theme = 'cosmo';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Добавить запись', 'url' => ['/main/add-entry']],
            Yii::$app->user->isGuest ? (
                [
                    'label' => 'Войти/Зарегистрироваться',
                    'items' => [
                        ['label' => 'Войти', 'url' => '/main/login'],
                        ['label' => 'Зарегистрироваться', 'url' => '/main/register']
                    ]
                ]
            ) : ([
                'label' => Yii::$app->user->identity->name,
                'items' => [
                    ['label' => 'Настройки', 'url' => '/main/settings'],
                    ['label' => 'Мои записи', 'url' => '/main/my-entries'],
                    '<li class="divider"></li>',
                    '<li>' . Html::a('Выйти', '/main/logout',
                        [
                            'title' => 'Выйти',
                            'data' => ['method' => 'post']
                        ])
                    .'</li>',
                ]
            ])
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php
            // Если в сессии передано уведомление
            if(Yii::$app->session->hasFlash('alert')){
                // Получаем его
                $alert = Yii::$app->session->getFlash('alert');
                // И показываем пользователю
                echo Alert::widget([
                    'options' => [
                        'class' => 'alert-success',
                    ],
                    'body' => $alert,
                ]);
            }
        ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Никита Фисун <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
