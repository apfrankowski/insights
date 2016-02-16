<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\touchspin\TouchSpin;
use app\models\IndicatorNames;
use app\models\Categories;
use app\models\InsightsContent;

/* @var $this yii\web\View */
/* @var $model app\models\InsightsDef */
/* @var $form yii\widgets\ActiveForm */

$items = array(
		null => '<i class="fa fa-ban"></i>',
		-2 => '<i class="fa fa-angle-double-down"></i>',
		-1 => '<i class="fa fa-angle-down"></i>',
		0=> '-',
		1 => '<i class="fa fa-angle-up"></i>',
		2 => '<i class="fa fa-angle-double-up"></i>'
	);

?>

<div class="insights-def-form">

    <?php $form = ActiveForm::begin([
    	'type' => ActiveForm::TYPE_HORIZONTAL
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_category')->dropDownList( Categories::find()->select(['name', 'id'])->indexBy('id')->column()) ?>

    <?= $form->field($model, 'priority')->widget(TouchSpin::className(), [
    	'pluginOptions' => ['min' => 0, 'max' => 5, 'step' => 1, 'initval' => '0']
    ]) ?>

    <?php foreach (IndicatorNames::find()->select(['indicator', 'name'])->all() as $indicator): 
    	// $model->{$indicator->indicator} = 'null';
    ?>

	<?= $form->field($model, $indicator->indicator, ['showLabels' => true])->radioButtonGroup($items, ['class' => 'btn-group-xs'])->label($indicator->name); ?>

    <?php endforeach; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Utwórz' : 'Aktualizuj', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
