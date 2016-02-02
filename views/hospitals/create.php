<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Hospitals */

$this->title = 'Create Hospitals';
$this->params['breadcrumbs'][] = ['label' => 'Hospitals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hospitals-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
