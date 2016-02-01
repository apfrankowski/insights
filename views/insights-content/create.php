<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InsightsContent */

$this->title = 'Create Insights Content';
$this->params['breadcrumbs'][] = ['label' => 'Insights Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insights-content-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
