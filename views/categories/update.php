<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */

$this->title = 'Aktualizuj kategorię: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Kategorie', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Aktualizuj';
?>
<div class="categories-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'indicatorsAvailable' => $indicatorsAvailable,
        'indicatorsPresent' => $indicatorsPresent
    ]) ?>

</div>
