<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InsightsDef */

$this->title = 'Create Insights Def';
$this->params['breadcrumbs'][] = ['label' => 'Insights Defs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insights-def-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
