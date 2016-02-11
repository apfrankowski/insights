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
		null => 'N/D',
		-2 => '<<',
		-1 => '<',
		0=> '=',
		1 => '>',
		2 => '>>'
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

	<?= $form->field($model, $indicator->indicator, ['showLabels' => true])->radioButtonGroup($items)->label($indicator->name); ?>

    <?php endforeach; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Utwórz' : 'Aktualizuj', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
