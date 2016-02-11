<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Definicje treści';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insights-content-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Utwórz treść wniosku', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'emptyText' => 'Nie znaleziono rekordów',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'content:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
