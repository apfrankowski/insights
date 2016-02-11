<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BasicIndicators */

$this->title = 'Aktualizuj wskaźnik: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Wskaźniki podstawowe', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="basic-indicators-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
