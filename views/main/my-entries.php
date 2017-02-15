<?php
/*
 * Отображает записи конкретного пользователя
 * */

use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View объект текущего представления */
/* @var $model app\models\Entry[] массив с записями пользователя */
/* @var $pagination yii\data\Pagination объект пагинации */

$this->title = 'Мои записи';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="main-my-entries">
    <?php if(count($model) == 0): ?>
        <p>
            У вас пока нет ни одной записи в нашей гостевой книге. Если вы хотите добавить запись,
            нажмите на кнопку &laquo;Добавить запись&raquo; в навигационном меню.
        </p>
    <?php endif; ?>

    <?php if(count($model) != 0): ?>
        <h1><?= Html::encode($this->title) ?></h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Номер</th>
                    <th>Дата добавления</th>
                    <th>Содержание</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model as $entry): ?>
                    <tr>
                        <td>
                            <?= Html::encode($entry->id) ?>
                        </td>
                        <td>
                            <?= Yii::$app->formatter->asDatetime($entry->created) ?>
                        </td>
                        <td>
                            <?= Yii::$app->formatter->asHtml($entry->message) ?>
                        </td>
                        <td>
                            <?= Html::a(
                                'Редактировать',
                                [
                                    'edit-entry',
                                    'id' => $entry->id
                                ],
                                [
                                    'title' => 'Просмотр',
                                    'class' => 'btn btn-primary btn-sm'
                                ])
                            ?>
                            <?= Html::a(
                                'Удалить',
                                [
                                    'delete-entry',
                                    'id' => $entry->id,
                                ],
                                [
                                    'title' => 'Удалить',
                                    'class' => 'btn btn-danger btn-sm',
                                    'data' => [
                                        'confirm' => 'Вы действительно хотите удалить запись с номером '.$entry->id.' ?',
                                        'method' => 'post',
                                    ],
                                ])
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-center">
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    <?php endif; ?>
</div>