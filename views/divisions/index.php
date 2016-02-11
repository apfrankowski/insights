<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Oddziały';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hospitals-index">

    <h1><?= Html::encode($this->title) ?></h1>

<!--     <p>
        <?= Html::a('Utwórz szpital', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
 -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nazwa',
            'id_szpital',
            'id_oddzial',
            'id_data',
            'id_specjalizacja',

            ['class' => 'yii\grid\ActionColumn', 
                'buttons' => [
                    'generate' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-send"></span>',
                            ['divisions/insights', 'id' => $model->id], 
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
