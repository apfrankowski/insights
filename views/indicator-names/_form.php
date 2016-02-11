<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\models\BasicIndicators;

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

    <?= $form->field($model, 'numerator')->dropDownList( BasicIndicators::find()->select(['name', 'id'])->indexBy('id')->column()) ?>

    <?= $form->field($model, 'denominator')->dropDownList(  [0=> ''] + BasicIndicators::find()->select(['name', 'id'])->indexBy('id')->column()) ?>

    <?= $form->field($model, 'denominator_dec')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Utwórz' : 'Aktualizuj', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
