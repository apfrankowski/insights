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

    public function calculateIndicators($id, $idSpec)
    {
        $hospitalIndicators = Divisions::find()->where(['id' => $id])->one();
        $indicatorDefinitions = IndicatorNames::find()->all();

        foreach ($indicatorDefinitions as $indicator) {

            $numeratorName = $indicator->basicNumerator->name;
            if ( $indicator->denominator != 0 ) {
                $denominatorName = $indicator->basicDenominator->name;
            }

            if ( $indicator->denominator != 0 && $hospitalIndicators->{$denominatorName} != 0 ) {
                
                $indicatorValue = ($hospitalIndicators->{$numeratorName} - $hospitalIndicators->{$denominatorName})
                                    / $hospitalIndicators->{$denominatorName};
            } elseif ( $indicator->denominator_dec != 0 ) {
                $indicatorValue = ($hospitalIndicators->{$numeratorName} - $indicator->denominator_dec)
                                    / $indicator->denominator_dec;
            } else {
                $indicatorValue = $hospitalIndicators->{$numeratorName};
            }

            $this->indicatorsArray[$indicator->indicator] = Indicators::_getRank($indicator->indicator, $indicatorValue, $id, $idSpec);
        }
    }

    public function getIndicatorsArray()
    {
        return $this->indicatorsArray;
    }

    private static function _getRank($indicatorName, $indicatorValue, $id, $idSpec)
    {
        echo $indicatorName . " ". $indicatorValue;
        $mathRules = IndicatorMath::find()->where(['indicator' => $indicatorName])->andWhere(['like', 'id_specjalizacja', $idSpec])->one();

        if (!$mathRules) {
            $mathRules = IndicatorMath::find()->where(['indicator' => $indicatorName])->andWhere(['id_specjalizacja' => ''])->one();
        }
        if ($mathRules) {

            if ( $indicatorValue < 0 ) {

                if ( isset($mathRules->minus2) && $indicatorValue < -$mathRules->minus2 ) {

                    return -2;

                } elseif ( $indicatorValue < -$mathRules->minus1 ) {

                    return -1;

                } else {

                    return 0;

                }

            } elseif ( $indicatorValue > 0 ) {

                if ( isset($mathRules->plus2) && $indicatorValue > $mathRules->plus2 ) {

                    return 2;

                } elseif ( $indicatorValue > $mathRules->plus1 ) {

                    return 1;

                } else {

                    return 0;
                    
                }

            } else {

                return 0;

            }
        } else {
            return 0;
        }
    }


}
?>