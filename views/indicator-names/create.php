<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\IndicatorNames */

$this->title = 'Create Indicator Names';
$this->params['breadcrumbs'][] = ['label' => 'Indicator Names', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="indicator-names-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
