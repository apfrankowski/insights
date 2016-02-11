<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\IndicatorNames */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Wskaźniki złożone', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="indicator-names-view">

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
            'indicator',
            'lang',
            'name',
            'basicNumerator.name',
            'basicDenominator.name',
            'denominator_dec'
        ],
    ]) ?>

</div>
