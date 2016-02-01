<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\IndicatorMath */

$this->title = 'Create Indicator Math';
$this->params['breadcrumbs'][] = ['label' => 'Indicator Maths', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="indicator-math-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
