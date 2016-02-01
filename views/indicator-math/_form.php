<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\IndicatorNames;

/* @var $this yii\web\View */
/* @var $model app\models\IndicatorMath */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="indicator-math-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'indicator')->dropDownList( IndicatorNames::find()->select(['name', 'indicator'])->indexBy('indicator')->column()) ?>

    <?= $form->field($model, 'id_hospital')->textInput() ?>

    <?= $form->field($model, 'id_division')->textInput() ?>

    <?= $form->field($model, 'minus2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'minus1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plus1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plus2')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
