<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Hospitals */
/* @var $insights app\models\Insights */

$this->title = $model->nazwa;
$this->params['breadcrumbs'][] = ['label' => 'Hospitals', 'url' => ['index', 'id_hospitals' => $model->id_szpital]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hospitals-insights">

    <h1><?= Html::encode($model->nazwa) ?></h1>

    <?php foreach ($insights->insightsArray as $key=>$content):?>

        <?=  $content;?> 

    <?php endforeach;?>

</div>
