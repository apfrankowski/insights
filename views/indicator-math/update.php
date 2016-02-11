<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\IndicatorMath */

$this->title = 'Aktualizuj rangi dla wskaźnika:' . ' ' . $model->indicator;
$this->params['breadcrumbs'][] = ['label' => 'Rangi wskaźników', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Aktualizuj';
?>
<div class="indicator-math-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
