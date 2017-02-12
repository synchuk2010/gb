<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use dosamigos\ckeditor\CKEditor;

// TODO: Добавить CKEditor (не работает Composer, попробуем прееустановить)

/* @var $this yii\web\View объект текущего представления */
/* @var $model app\models\Entry объект создаваемой записи */

$this->title = 'Добавить запись';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="add-entry">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'add-entry-form']); ?>

    <?php if(Yii::$app->user->isGuest) {
        echo $form->field($model, 'name')->textInput(['autofocus' => true]);
        echo $form->field($model, 'email')->textInput();
    }
    ?>

    <?= $form->field($model, 'message')->widget(CKEditor::className(),[
        'options' => ['rows' => 6],
        'preset' => 'basic',
    ]) ?>

    <?php if(Yii::$app->user->isGuest) {
        echo $form->field($model, 'verifyCode')->label('Код подтверждения')->widget(Captcha::className(), [
            // Чтобы предотвратить спам, добавляем капчу
            'captchaAction' => 'main/captcha',
            'template' => '<div class="row"><div class="col-lg-4">{image}</div><div class="col-lg-3">{input}</div></div>',
            'class' => 'form-control input-sm'
        ]);
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'add-entry-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
