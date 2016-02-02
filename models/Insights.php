<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\InsightsDef;
use app\models\InsightsContent;

/**
 * This is the model class for "indicators".
 *
 * @property array $id
 */
class Insights extends Model
{

	public $insightsArray;

	public function prepareInsights($indicators)
	{
		$whereArray = ['and'];
		foreach ($indicators as $key=>$value) {
			array_push($whereArray, ['or', "$key=$value", "$key IS NULL"]);
		}
		if ($insights = InsightsDef::find()->where($whereArray)->all()) {
			foreach ($insights as $insight) {
				$this->insightsArray[$insight->name] = $insight->content->content;
			}
		} else {
			$this->insightsArray = [];
		}
	}


}
?>
