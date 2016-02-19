<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InsightsDef */

$this->title = 'Definicje wniosków';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insights-def-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'content' => $content,
        'categories' => $categories,
        'indicatorNames' => $indicatorNames,
        'insights' => $insights
    ]) ?>

</div>
