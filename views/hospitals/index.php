<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Hospitals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hospitals-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Hospitals', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
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
                ],
                'template' => '{view} {update} {delete} {generate}'
            ],
        ],
    ]); ?>

</div>
