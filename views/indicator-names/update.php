<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\IndicatorNames */

$this->title = 'Aktualizuj wskaźnik: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Wskaźniki złożone', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Aktualizuj';
?>
<div class="indicator-names-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
