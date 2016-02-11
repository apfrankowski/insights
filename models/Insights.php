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

	public $insightsArray = [];

	public function prepareInsights($id, $indicators)
	{
		$hospitalIndicators = Divisions::find()->where(['id' => $id])->one();

		$whereArray = ['and'];
		foreach ($indicators as $key=>$value) {
			array_push($whereArray, ['or', "$key = $value", "$key IS NULL"]);
		}
		if ($insights = InsightsDef::find()->where($whereArray)->all()) {
			foreach ($insights as $insight) {
				foreach ($insight->content as $insightLine) {
					array_push ($this->insightsArray, Insights::_evaluateIndicators($insightLine->content, $hospitalIndicators));
				}
			}
		} else {
			$this->insightsArray = [];
		}
	}

	private static function _evaluateIndicators($content, $hospitalIndicators)
	{
		$returnTable = [];
		preg_match_all("|\[(.+?)\]|m", $content, $matches);
		
		foreach ($matches[1] as $idx=>$match) {
			preg_match_all("|([A-Za-z_]+)|", $match, $matchesIndicators);
			if (count($matchesIndicators) > 0) {
				$unit = $basicIndicators = BasicIndicators::find()->where(['name' => $matchesIndicators[1][0]])->one()->unit;
			}
			$returnTable[$matches[0][$idx]] = eval( 'return ('.preg_replace("|([A-Za-z_]+)|", '$hospitalIndicators->$1', $match). ')'
				.($unit == '%' ? '*100' : '')
				.';') . $unit;
		}
		foreach ($returnTable as $key=>$value) {
			$content = str_replace($key, $value, $content);
		}

		return $content;

	}

}
?>
