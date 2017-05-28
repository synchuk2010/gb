<?php
/*
 * Шаблон главной страницы приложения
 * */

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
    <?php $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Yii::$app->request->baseUrl . '/gb.png']); ?>
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
            ['label' => 'Добавить запись', 'url' => ['add-entry']],
            Yii::$app->user->isGuest ? (
                [
                    'label' => 'Войти/Зарегистрироваться',
                    'items' => [
                        ['label' => 'Войти', 'url' => 'login'],
                        ['label' => 'Зарегистрироваться', 'url' => 'register']
                    ]
                ]
            ) : ([
                'label' => Yii::$app->user->identity->name,
                'items' => [
                    ['label' => 'Мои записи', 'url' => 'my-entries'],
                    ['label' => 'Настройки', 'url' => 'settings'],
                    '<li class="divider"></li>',
                    '<li>' . Html::a('Выйти', 'logout',
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
