<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\LinkPager;

/* @var $this yii\web\View объект текущего представления */
/* @var $model app\models\Entry[] массив записей */
/* @var $pagination yii\data\Pagination объект с пагинацией */

$this->title = 'Гостевая книга';
?>
<div class="main-index">
    <?php if(count($model) == 0): ?>
        <p>
            Судя по всему, никто ещё не добавил ни одной записи. Вы вполне можете быть первым!
        </p>
    <?php endif; ?>

    <?php if(count($model) != 0): ?>
        <h1 class="text-center">Записи в гостевой книге</h1>
        <div class="panel-group" id="entries" role="tablist" aria-multiselectable="true">
            <?php foreach ($model as $entry): ?>
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="heading-<?= $entry->id ?>">
                        <h4 class="panel-title">
                            <a
                                    role="button"
                                    data-toggle="collapse"
                                    data-parent="#entries"
                                    href="#collapse-<?= $entry->id ?>"
                                    aria-expanded="true"
                                    aria-controls="collapse-<?= $entry->id ?>">
                                <?= Html::encode("$entry->name") ?>
                                &nbsp;
                                <?= Yii::$app->formatter->asDatetime($entry->created) ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-<?= $entry->id ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?= $entry->id ?>">
                        <div class="panel-body">
                            <?= HtmlPurifier::process($entry->message) ?>
                        </div>
                        <div class="panel-footer">
                            <?= Yii::$app->formatter->asEmail($entry->email) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center">
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    <?php endif; ?>
</div>
