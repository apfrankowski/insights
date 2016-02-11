<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Wskaźniki podstawowe';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="basic-indicators-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Utwórz wskaźnik podstawowy', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description',
            'unit',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
