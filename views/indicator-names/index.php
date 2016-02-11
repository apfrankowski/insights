<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Wskaźniki złożone';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="indicator-names-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Utwórz wskaźnik złożony', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'indicator',
            'lang',
            'name',
            'basicNumerator.name',
            'basicDenominator.name',
            'denominator_dec',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
