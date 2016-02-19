<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\touchspin\TouchSpin;
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
$counter = 0;
?>

<div class="insights-def-form">

    <?php $form = ActiveForm::begin([
    	'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['showLabels' => false,'labelSpan'=>2, 'deviceSize'=>ActiveForm::SIZE_LARGE]
    ]); ?>

    <div class="form-group kv-fieldset-inline">
        <?= Html::activeLabel($model, 'id_category', [
            'label'=>'Kategoria', 
            'class'=>'col-sm-1 control-label'
        ]); ?>
        <div class="col-sm-2">
            <?= $form->field($model, 'id_category')->dropDownList( $categories ) ?>
        </div>
        <?= Html::activeLabel($model, 'name', [
            'label'=>'Zapisane wnioski', 
            'class'=>'col-sm-1 control-label'
        ]); ?>
        <div class="col-sm-8">
            <?= $form->field($model, 'name')->dropDownList( $insights  ) ?>
        </div>
    </div>
    <div class="form-group kv-fieldset-inline">
        <?= Html::activeLabel($model, 'id_category', [
            'label'=>'Szpital', 
            'class'=>'col-sm-1 control-label'
        ]); ?>
        <div class="col-sm-2">
            <?= $form->field($model, 'hospitals')->textInput() ?>
        </div>
        <?= Html::activeLabel($model, 'id_category', [
            'label'=>'Oddział', 
            'class'=>'col-sm-1 control-label'
        ]); ?>
        <div class="col-sm-2">
            <?= $form->field($model, 'units')->textInput() ?>
        </div>
        <?= Html::activeLabel($model, 'id_category', [
            'label'=>'Specjalność', 
            'class'=>'col-sm-1 control-label'
        ]); ?>
        <div class="col-sm-2">
            <?= $form->field($model, 'specialities')->textInput() ?>
        </div>
    </div>
    <div class="form-group kv-fieldset-inline">

    <?php foreach ($indicatorNames as $indicator): 
    ?>
        <?= $counter%3 == 0 ? '</div><div class="form-group kv-fieldset-inline">' : ''?>
        <?= Html::activeLabel($model, 'id_category', [
            'label'=>$indicator->indicator->name, 
            'class'=>'col-sm-2 control-label'
        ]); ?>
        <div class="col-sm-2">
            <?= $form->field($model, $indicator->indicator->indicator)
                ->radioButtonGroup($items, ['class' => 'btn-group-xs']); ?>
        </div>
    <?php         
        ++$counter;
        endforeach; 
    ?>

    </div>

    <div class="panel panel-info">
      <div class="panel-heading">Podgląd całego wniosku:</div>
      <div class="panel-body"  id="insightsdef-content">
        
      </div>
    </div>

    <div class="panel panel-success">
      <div class="panel-heading">Treść aktywnego wniosku:</div>
      <div class="panel-body">
        <?= $form->field($content, 'id')->hiddenInput() ?>
        <?= $form->field($content, 'content')->textarea() ?>
        
      </div>
    </div>


    <div class="form-group">
        <?= Html::button('Utwórz', ['class' => 'btn btn-success', 'id' => 'insightsdef-submit_button']) ?>
        <?= Html::button('Aktualizuj', ['class' => 'btn btn-primary', 'id' => 'insightsdef-update_button', 'disabled' => 'disabled']) ?>
        <?= Html::button('Wyczyść', ['class' => 'btn btn-warning', 'id' => 'insightsdef-reset_button']) ?>
        <?= Html::button('Usuń', ['class' => 'btn btn-danger', 'id' => 'insightsdef-delete_button', 'disabled' => 'disabled']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
