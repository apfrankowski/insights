<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\IndicatorNames;

/* @var $this yii\web\View */
/* @var $model app\models\InsightsDef */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Definicje wniosków', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insights-def-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Aktualizuj', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Usuń', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Czy na pewno usunąć wpis?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge([
            'name',
            'category.name',
            'priority',
        ], IndicatorNames::find()->select(['indicator'])->column())
    ]) ?>

</div>
