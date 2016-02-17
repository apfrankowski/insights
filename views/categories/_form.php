<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Categories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'indicators_available')->listBox($indicatorsAvailable, ['class' => 'indicators-available-lBox form-control']) ?>

    <?= $form->field($model, 'indicators')->listBox($indicatorsPresent, ['class' => 'indicators-lBox form-control', 'multiple' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Utwórz' : 'Aktualizuj', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
