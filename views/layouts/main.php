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
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/main/index']],
            ['label' => 'About', 'url' => ['/main/about']],
            ['label' => 'Contact', 'url' => ['/main/contact']],
            Yii::$app->user->isGuest ? (
                [
                    'label' => 'Войти/Зарегистрироваться',
                    'items' => [
                        ['label' => 'Войти', 'url' => '/main/login'],
                        ['label' => 'Зарегистрироваться', 'url' => '/main/register']
                    ]
                ]
            ) : (
                '<li>'
                . Html::beginForm(['/main/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->name . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
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
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
    <?= Yii::$app->request->absoluteUrl ?>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
