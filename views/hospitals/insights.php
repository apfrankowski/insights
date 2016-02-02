<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Hospitals */
/* @var $insights app\models\Insights */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Hospitals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hospitals-insights">

    <h1><?= Html::encode($model->name) ?></h1>

    <?php foreach ($insights->insightsArray as $key=>$content):?>

        <p>
        <?=  $content;?>
        </p>

    <?php endforeach;?>

</div>
