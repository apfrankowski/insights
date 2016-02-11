<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BasicIndicators */

$this->title = 'Tworzenie wskaźnika podstawowego';
$this->params['breadcrumbs'][] = ['label' => 'Wskaźniki podstawowe', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="basic-indicators-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
