<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rangi wskaźników';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="indicator-math-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Stwórz rangi wskaźnika', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'indicators.name',
            'id_hospital',
            'id_division',
            'id_specjalizacja',
            'minus2',
            'minus1',
            'plus1',
            'plus2',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
