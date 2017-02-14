<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View объект текущего представления */
/* @var $model app\models\Entry объект редактируемой записи */
/* @var $form yii\widgets\ActiveForm форма редактирования записи */

$this->title = 'Изменить запись';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="edit-entry">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'edit-entry-form']); ?>

    <?= $form->field($model, 'message')->widget(CKEditor::className(),[
        'options' => ['rows' => 6],
        'preset' => 'basic',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'add-entry-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
