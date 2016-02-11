<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Szpitale';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hospitals-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Utwórz szpital', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',

            ['class' => 'yii\grid\ActionColumn', 
                'buttons' => [
                    'generate' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-send"></span>',
                            ['hospitals/insights', 'id' => $model->id], 
                            [
                                'title' => 'Pokaż wnioski',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                    'view' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open"></span>',
                            ['divisions/index', 'id_hospitals' => $model->id], 
                            [
                                'title' => 'Pokaż oddziały',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-refresh"></span>',
                            ['hospitals/update-data', 'id' => $model->id], 
                            [
                                'title' => 'Załaduj dane',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                ],
                'template' => '{view} {update} {delete}'
            ],
        ],
    ]); ?>

</div>
