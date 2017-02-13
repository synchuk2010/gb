<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View объект текущего представления */
/* @var $model app\models\Settings настройки для текущего пользователя */
/* @var $form yii\widgets\ActiveForm форма редактирования настроек */

$this->title = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-settings">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="panel panel-info">
        <div class="panel-heading">
            Редактировать настройки
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(['id' => 'settings-form']); ?>

            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'theme')->dropDownList([
                'default' => 'Default',
                'cerulean' => 'Cerulean',
                'cosmo' => 'Cosmo',
                'cyborg' => 'Cyborg',
                'darkly' => 'Darkly',
                'flatly' => 'Flatly',
                'journal' => 'Journal',
                'lumen' => 'Lumen',
                'paper' => 'Paper',
                'readable' => 'Readable',
                'sandstone' => 'Sandstone',
                'simplex' => 'Simplex',
                'slate' => 'Slate',
                'spacelab' => 'Spacelab',
                'superhero' => 'Superhero',
                'united' => 'United',
                'yeti' => 'yeti',
            ]) ?>

            <?= $form->field($model, 'rows')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'add-entry-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
