<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\models\BasicIndicators;

/* @var $this yii\web\View */
/* @var $model app\models\Hospitals */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hospitals-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Utwórz' : 'Aktualizuj', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
