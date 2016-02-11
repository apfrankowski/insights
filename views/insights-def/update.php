<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InsightsDef */

$this->title = 'Aktualizowanie definicji: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Definicje wniosków', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Aktualizuj';
?>
<div class="insights-def-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
