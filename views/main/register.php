<?php
/*
 * Отображает форму регистрации пользователя
 * */

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\AuthForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Зарегистрироваться';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Для регистрации, заполните следующие поля:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'register-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'verifyCode')->label('Код')->widget(Captcha::className(), [
            // Чтобы предотвратить спам, добавляем капчу
            'captchaAction' => 'main/captcha',
            'template' => '<div class="row"><div class="col-lg-4">{image}</div><div class="col-lg-4">{input}</div></div>',
            'class' => 'form-control'
    ]) ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Регистрация', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
