<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\InsightsDef;

/* @var $this yii\web\View */
/* @var $model app\models\InsightsContent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insights-content-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->dropDownList( InsightsDef::find()->select(['name', 'name'])->indexBy('name')->column()) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Utwórz' : 'Aktualizuj', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
