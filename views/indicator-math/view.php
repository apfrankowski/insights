<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\IndicatorMath */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rangi wskaźników', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="indicator-math-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Aktualizuj', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Usuń', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Czy na pewno chcesz usunąć wpis?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'indicators.name',
            'id_hospital',
            'id_division',
            'id_specjalizacja',
            'minus2',
            'minus1',
            'plus1',
            'plus2',
        ],
    ]) ?>

</div>
