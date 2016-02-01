<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\IndicatorNames */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="indicator-names-form">

    <?php $form = ActiveForm::begin([
    	'type' => ActiveForm::TYPE_INLINE
    ]); ?>

    <?= $form->field($model, 'indicator')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lang')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
