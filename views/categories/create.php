<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Categories */

$this->title = 'Tworzenie kategorii';
$this->params['breadcrumbs'][] = ['label' => 'Kategorie', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'indicatorsAvailable' => $indicatorsAvailable,
        'indicatorsPresent' => $indicatorsPresent
    ]) ?>

</div>
