<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Hospitals;
use app\models\IndicatorNames;
use app\models\IndicatorMath;

/**
 * This is the model class for "indicators".
 *
 * @property array $id
 */
class Indicators extends Model
{

    public $indicatorsArray;

    public function calculateIndicators($hospitalId)
    {
        $hospitalIndicators = Hospitals::find()->where(['id' => $hospitalId])->one();
        $indicatorDefinitions = IndicatorNames::find()->all();

        foreach ($indicatorDefinitions as $indicator) {

            $numeratorName = $indicator->basicNumerator->name;
            $denominatorName = $indicator->basicDenominator->name;

            $indicatorValue = ($hospitalIndicators->{$numeratorName} - $hospitalIndicators->{$denominatorName})
                                / $hospitalIndicators->{$denominatorName};

            $this->indicatorsArray[$indicator->indicator] = Indicators::_getRank($indicator->indicator, $indicatorValue, $hospitalId);
        }
    }

    public function getIndicatorsArray()
    {
        return $this->indicatorsArray;
    }

    private static function _getRank($indicatorName, $indicatorValue, $hospitalId)
    {
        $mathRules = IndicatorMath::find()->where(['indicator' => $indicatorName])->one();
        if ( $indicatorValue < 0 ) {

            if ( $indicatorValue < -$mathRules->minus2 ) {

                return -2;

            } elseif ( $indicatorValue < -$mathRules->minus1 ) {

                return -1;

            } else {

                return 0;

            }

        } elseif ( $indicatorValue > 0 ) {

            if ( $indicatorValue > $mathRules->plus2 ) {

                return 2;

            } elseif ( $indicatorValue > $mathRules->plus1 ) {

                return 1;

            } else {

                return 0;
                
            }

        } else {

            return 0;

        }
    }


}
?>